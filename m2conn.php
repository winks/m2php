<?php
namespace m2php;

function xhttp_response($body, $code, $status, $headers) {
    $http = "HTTP/1.1 %s %s\r\n%s\r\n\r\n%s";

    if (is_null($headers)) {
        $headers = array();
    }
    $headers['Content-Length'] = strlen($body);
    $hd = "\r\n";
    foreach($headers as $k => $v) {
        $hd .= sprintf('%s: %s', $k, $v);
    }
    return sprintf($http, $code, $status, $hd, $body);
}

class Connection {
    private $sender_id;

    public function __construct($sender_id, $sub_addr, $pub_addr) {
        $this->sender_id = $sender_id;

        $ctx = new \ZMQContext();
        $reqs = $ctx->getSocket(\ZMQ::SOCKET_UPSTREAM);
        $reqs->connect($sub_addr);

        $resp = $ctx->getSocket(\ZMQ::SOCKET_PUB);
        $resp->connect($pub_addr);
        $resp->setSockOpt(\ZMQ::SOCKOPT_IDENTITY, $sender_id);

        $this->sub_addr = $sub_addr;
        $this->pub_addr = $pub_addr;

        $this->reqs = $reqs;
        $this->resp = $resp;
    }

    public function recv() {
        return \m2php\Request::parse($this->reqs->recv());
    }

    public function recv_json() {
        $req = $this->recv();
        if (!isset($req->data)) {
            $req->data = json_decode($req->body);
        }
        return $req;
    }

    public function reply($req, $msg) {
        $this->send($req->conn_id, $msg);
    }

    public function send($conn_id, $msg) {
        $this->resp->send($conn_id . " " . $msg);
    }

    public function reply_json($req, $data) {
        $this->send($req->conn_id, json_encode($msg));
    }
    public function reply_http($req, $body, $code = 200, $status = "OK", $headers = null) {
        $this->reply($req, \m2php\http_response($body, $code, $status, $headers));
    }
    public function deliver($idents, $data) {
        $this->resp->send(join(' ', $idents) . ' ' . $data);
    }
    public function deliver_json($idents, $data) {
        $this->deliver($idents, json_encode($data));
    }
    public function deliver_http($idents, $body, $code = 200, $status = "OK", $headers = null) {
        $this->deliver($idents, \m2php\http_response($body, $code, $status, $headers));
    }

}
