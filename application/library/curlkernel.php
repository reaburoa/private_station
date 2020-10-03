<?php

namespace Library;

class CurlKernel
{
    private $_error = [
        'error_no' => null,
        'error_msg' => null,
    ];

    private $_httpcode_white_list = [
        200,
        201,
        204,
    ];

    private $_config = [];//扩展使用，后续可能会考虑到是否使用代理、统计计数等等
    private static $_instance = null;

    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct($ar_config = [])
    {
        if (!empty($ar_config) && is_array($ar_config)) {
            foreach ($ar_config as $key => $value) {
                if (array_keys($this->_config, $key)) {
                    $this->_config[$key] = $value;
                }
            }
        }
    }

    /**
     * 获取错误信息
     */
    public function getErrorInfo()
    {
        return $this->_error;
    }

    /**
     * 获取错误号
     */
    public function getErrorNo()
    {
        return $this->_error['error_no'];
    }

    /**
     * 通过curl获取远程接口数据
     * @param string $request_url 请求地址
     * @param mixed $params 页面请求参数列表(支持数组array('id'=>123456,'name'=>'张三')和字符串(id=123456&name=张三))
     * @param bool $post 请求方式（T:post;F:get,默认get）
     * @param string $parse_mode 返回结果解析模式：json、xml、none（不做解析）
     * @param int $timeout 请求超时时间
     * @param string $ua 请求useragent
     * @param array $header 请求头信息,数据格式：array('key' => 'value')
     * @param string $cookie_file 请求cookie存放的文件地址
     * @param array $cert 请求证书，e.g array('cert' => 'path', 'key' => 'path'),php的curl使用证书时使用的pem证书
     * @return mixed  array('header'=>'array 返回远程接口的头信息', 'body'=>'mixed,根据parse_mode来定，json、xml返回array，none返回string');
     */
    public function getData($request_url, $params = [], $post = false, $parse_mode = 'json', $timeout = 25, $ua = '', $header = [], $cookie_file = '', $cert = [])
    {
        $ret = $this->_curlHttpData($request_url, $params, $post, $parse_mode, $timeout, $ua, $header, $cookie_file, $cert);
        if (false === $ret) {
            return false;
        }
        return $ret;
    }

    private function _curlHttpData($request_url, $params = [], $post = false, $parse_mode = 'json', $timeout = 5, $ua = '', $header = [], $cookie_file = '', $cert = [])
    {
        $start_time = microtime(true);
        $url_info = parse_url($request_url);
        $http_host = $url_info['host'];

        $ch = curl_init();
        if ($url_info['scheme'] == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        if (!$post && !empty($params) && is_array($params)) {
            $curl_data = $this->_buildQuery($params);
        } else {
            $curl_data = $params;
        }

        if ($post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $curl_data);
        } elseif ($curl_data) {
            $request_url .= (strpos($request_url, '?') !== false ? '&' : '?') . $curl_data;
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        if (!empty($cookie_file) && is_file($cookie_file)) {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
        }

        if ($header && is_array($header)) {
            foreach ($header as $key => $value) {
                $headers[] = $key . ':' . $value;
            }
        }
        $headers[] = 'Host:' . $http_host;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if ($ua) {
            curl_setopt($ch, CURLOPT_USERAGENT, $ua);
        }
        curl_setopt($ch, CURLOPT_ENCODING, 'deflate');
        if ($cert) {
            if (isset($cert['cert'])) {
                curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
                curl_setopt($ch, CURLOPT_SSLCERT, $cert['cert']);
            }
            if (isset($cert['key'])) {
                curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
                curl_setopt($ch, CURLOPT_SSLKEY, $cert['key']);
            }
        }
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, false);
        $ret_data = curl_exec($ch);
        if (false === $ret_data) {
            $this->_error['error_no'] = curl_errno($ch);
            $this->_error['error_msg'] = curl_error($ch);
            curl_close($ch);
            return false;
        }
        $ar_info = curl_getinfo($ch);
        curl_close($ch);

        if (!in_array($ar_info['http_code'], $this->_httpcode_white_list)) {
            $this->_error['error_no'] = $ar_info['http_code'];
            return false;
        }
        $ar_ret = [];
        $ar_ret['header'] = $this->_getHeader($ret_data, $ar_info['header_size']);
        $ar_ret['body'] = $this->_getBody($ret_data, $ar_info['header_size'], $parse_mode);

        return $ar_ret;
    }

    private function _getHeader($response, $header_size)
    {
        $s_header = substr($response, 0, $header_size);
        $lines = explode("\r\n", trim($s_header));
        $ar_header = [];
        $cookies = [];
        foreach ($lines as $k => $line) {
            if (0 === $k) {
                continue;
            }
            $line_data = explode(': ', $line, 2);
            $key = $line_data[0];
            $value = isset($line_data[1]) ? $line_data[1] : '';
            if ($key == 'Set-Cookie') {
                $cookies[] = $this->_getCookies($value);
            } else {
                $value = trim($value);
            }
            $ar_header[trim($key)] = is_numeric($value) ? ($value > 2147483647 ? $value : intval($value)) : $value;
        }
        $ar_header['cookie'] = $cookies;
        unset($ar_header['Set-Cookie']);
        return $ar_header;
    }

    private function _getCookies($cookie)
    {
        if (!$cookie) {
            return [];
        }

        $cookies = explode('; ', $cookie);
        $keys = explode('=', array_shift($cookies));
        if (count($keys) > 2) {
            $temp = [];
            for ($i = 1, $length = count($keys); $i < $length; $i++) {
                $temp[] = $keys[$i];
            }
            $keys[1] = implode('=', $temp);
        }
        $result = [];
        $result[$keys[0]] = $keys[1];
        foreach ($cookies as $key => $value) {
            list($k, $val) = explode('=', $value);
            $result[$k] = $val;
        }
        return $result;
    }

    private function _getBody($response, $header_size, $parse_mode)
    {
        $s_body = ltrim(substr($response, $header_size));
        if (empty($s_body)) {
            return '';
        }

        $ret = $s_body;
        switch ($parse_mode) {
            case 'json':
                $ret = $this->_jsonDecode($s_body);
                break;
            case 'xml':
                $xml_parser = xml_parser_create();
                if (xml_parse($xml_parser, $s_body, true)) {
                    $ret = $this->_parseXml($s_body);
                } else {
                    $ret = $s_body;
                }
                break;
            case 'none':
                $ret = $s_body;
                break;
        }
        return $ret;
    }


    private function _jsonDecode($data)
    {
        return json_decode($data, true) ? json_decode($data, true) : $data;
    }

    private function _parseXml($s_xml)
    {
        $dom = new \DOMDocument();
        $dom->loadXML($s_xml);
        $ar_xml = $this->_dom2Array($dom);
        return $ar_xml;
    }

    private function _buildQuery($query_params)
    {
        $result = '';
        if ($query_params && is_array($query_params)) {
            foreach ($query_params as $key => $value) {
                $result .= $key . '=' . $value . '&';
            }
            $result = substr($result, 0, -1);
        } else {
            $result = $query_params;
        }

        return $result;
    }

    /**
     * 将dom对象转换为数组
     * @param object $root dom节点
     * @return array|mixed
     */
    public static function _dom2Array($root)
    {
        $result = [];
        if ($root->hasAttributes()) {
            $attrs = $root->attributes;
            foreach ($attrs as $attr) {
                $result[$attr->name] = is_numeric($attr->value) ? intval($attr->value) : $attr->value;
            }
        }
        $children = $root->childNodes;
        if ($children->length == 1) {
            $child = $children->item(0);
            if ($child->nodeType == XML_TEXT_NODE) {
                $result['_value'] = is_numeric($child->nodeValue) ? intval($child->nodeValue) : $child->nodeValue;
                if (count($result) == 1) {
                    return $result['_value'];
                } else {
                    return $result;
                }
            }
        }
        $group = [];
        for ($i = 0; $i < $children->length; $i++) {
            $child = $children->item($i);
            if (!isset($result[$child->nodeName])) {
                $result[$child->nodeName] = self::_dom2Array($child);
            } else {
                if (!isset($group[$child->nodeName])) {
                    $tmp = $result[$child->nodeName];
                    $result[$child->nodeName] = [$tmp];
                    $group[$child->nodeName] = 1;
                }
                $result[$child->nodeName][] = self::_dom2Array($child);
            }
        }
        return $result;
    }
}