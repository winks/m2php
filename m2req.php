<?php
namespace m2php;

class Request{
    public function __construct($sender, $conn_id, $path, $headers, $body) {
        $this->sender = $sender;
        $this->path = $path;
        $this->conn_id = $conn_id;
        $this->headers = $headers;
        $this->body = $body;

        if ($this->headers['METHOD'] == 'JSON') {
            $this->data = json_decode($body);
        } else {
            $this->data = array();
        }
    }

    public static function parse($msg) {
        list($sender, $conn_id, $path, $rest) = explode(' ', $msg, 3);
        print_r($msg, $rest);
        $hd = parse_netstring($rest);
        $headers = $hd[0];
        $rest = $hd[1];
        $hd = \m2php\parse_netstring($rest);
        $body = $hd[0];

        $headers = json_decode($headers);
        
        return new \m2php\Request($sender, $conn_id, $path, $headers, $body);
    }

    public function is_disconnect() {
        if ($this->headers['METHOD'] == 'JSON') {
            return $this->data['type'] == 'disconnect';
        }
    }
}
