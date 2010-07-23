<?php
require '../m2tools.php';
//require 'PHPUnit/Framework.php';

class ToolsTest extends PHPUnit_Framework_TestCase {

    public function testHttpResponse() {

        $fix = "HTTP/1.1 200 OK\r\nX-foo: Yo\r\nContent-Length: 3\r\n\r\nfoo";

        $ret = \m2php\http_response("foo", 200, "OK", array('X-foo' => 'Yo'));

        $this->assertEquals($ret, $fix);

    }
    public function testParseNetstring() {

        $fix = array("bar", "5:fnord,");
        $ret = \m2php\parse_netstring("3:bar,5:fnord,");
        $this->assertEquals($ret, $fix);
   }
}
