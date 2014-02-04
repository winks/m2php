<?php

namespace Mongrel2;

class Tool
{

    /* tnetstring parser from https://github.com/jessedp/tnetstrings-php
     * Besides some minor changes to accomodate this class, it only
     * differs in tns_decode_pair: ($value===null) instead of (!$value)
     */
    public static function parse_tnetstring($data){
        list($payload, $payload_type, $remain) = self::tns_decodePayload($data);
        switch($payload_type){
            case '#': $value = (int)$payload; break;
            case '}': $value = self::tns_decode_dict($payload); break;
            case ']': $value = self::tns_decode_list($payload); break;
            case '!': $value = $payload==='true'; break;
            case '~':
                if (strlen($payload)!=0){
                    throw new \Exception("Payload must be 0 length for null.");
                }
                $value = null;
                break;
            case ',': $value = (string)$payload;break;
            default:
                throw new \Exception("Invalid payload type: ".$payload_type);
        }
        return array($value, $remain);
    }
    private static function tns_decodePayload($data){
        if (empty($data)){
            throw new \Exception("Invalid data to parse, it's empty.");
        }
        list($len, $extra) = explode(':',$data,2);
        $payload = substr($extra,0,$len);
        $extra = substr($extra,$len);
        if (!$extra){
            throw new \Exception(sprintf("No payload type: %s, %s.",$payload,$extra));
        }
        $payload_type = $extra[0];
        $remain = substr($extra,1);
        if (strlen($payload)!=$len){
            throw new \Exception(sprintf("Data is wrong length %d vs %d",strlen($payload),$len));
        }
        return array($payload, $payload_type, $remain);
    }

    private static function tns_decode_list($data){
        if (strlen($data) == 0) return array();

        $result = array();
        list($value, $extra) = self::parse_tnetstring($data);
        $result[] = $value;

        while ($extra){
            list($value, $extra) = self::parse_tnetstring($extra);
            $result[] = $value;
        }
        return $result;
    }

    private static function tns_decode_pair($data){
        list($key, $extra) = self::parse_tnetstring($data);
        if (!$extra){
            throw new \Exception("Unbalanced dictionary store.");
        }
        list($value, $extra) = self::parse_tnetstring($extra);
        if ($value===null){
            throw new \Exception("Got an invalid value, null not allowed.");
        }

        return array($key, $value, $extra);
    }

    private static function tns_decode_dict($data){
        if (strlen($data) == 0) return array();

        list($key, $value, $extra) = self::tns_decode_pair($data);
        $result = array($key=>$value);

        while ($extra){
            list($key, $value, $extra) = self::tns_decode_pair($extra);
            $result[$key] = $value;
        }
        return $result;
    }

    static public function http_response($body, $code, $status, $headers)
    {
        $http = "HTTP/1.1 %s %s\r\n%s\r\n%s";

        if (is_null($headers)) {
            $headers = array();
        }
        $headers['Content-Length'] = strlen($body);
        $hd = "";
        foreach($headers as $k => $v) {
            $hd .= sprintf("%s: %s\r\n", $k, $v);
        }
        return sprintf($http, $code, $status, $hd, $body);
    }
}
