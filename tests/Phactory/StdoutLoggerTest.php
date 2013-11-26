<?php
/**
 * @author Konstantin G Romanov
 */

class StdoutLoggerTest extends PHPUnit_Framework_TestCase {
    /** @var \Phactory\StdoutLogger */
    private $cut;
    private $varname = 'test';

    protected function setUp()
    {
        stream_wrapper_register("var", "VariableStream");
        $GLOBALS[$this->varname] = '';
        $this->cut = new \Phactory\StdoutLogger();
        $this->cut->setStdout(fopen('var://test', 'w'));
    }

    protected function tearDown()
    {
        $existed = in_array("var", stream_get_wrappers());
        if ($existed) {
            stream_wrapper_unregister("var");
        }

        VariableStream::reset($this->varname);
    }

    public function testLog_infoLevelWoContext_matchExpected()
    {
        $this->cut->log(\Psr\Log\LogLevel::INFO, 'test');
        $out = $GLOBALS[$this->varname];
        $this->assertEquals("[info] test\n", $out);
    }

    public function testLog_infoLevelWContext_matchExpected()
    {
        $this->cut->log(\Psr\Log\LogLevel::INFO, 'test {c}', array('c' => 1));
        $out = $GLOBALS[$this->varname];
        $this->assertEquals("[info] test 1\n", $out);
    }

    public function testSetStdout_resource_noException()
    {
        $this->cut->setStdout(STDOUT);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetStdout_string_IAE()
    {
        $this->cut->setStdout('test');
    }
}

class VariableStream {
    var $position;
    var $varname;

    public static function reset($varname) {
        if (isset($GLOBALS[$varname])) {
            unset($GLOBALS[$varname]);
        }
    }

    function stream_open($path, $mode, $options, &$opened_path)
    {
        $url = parse_url($path);
        $this->varname = $url["host"];
        $this->position = 0;

        return true;
    }

    function stream_read($count)
    {
        $ret = substr($GLOBALS[$this->varname], $this->position, $count);
        $this->position += strlen($ret);
        return $ret;
    }

    function stream_write($data)
    {
        $left = substr($GLOBALS[$this->varname], 0, $this->position);
        $right = substr($GLOBALS[$this->varname], $this->position + strlen($data));
        $GLOBALS[$this->varname] = $left . $data . $right;
        $this->position += strlen($data);
        return strlen($data);
    }

    function stream_tell()
    {
        return $this->position;
    }

    function stream_eof()
    {
        return $this->position >= strlen($GLOBALS[$this->varname]);
    }

    function stream_seek($offset, $whence)
    {
        switch ($whence) {
            case SEEK_SET:
                if ($offset < strlen($GLOBALS[$this->varname]) && $offset >= 0) {
                    $this->position = $offset;
                    return true;
                } else {
                    return false;
                }
                break;

            case SEEK_CUR:
                if ($offset >= 0) {
                    $this->position += $offset;
                    return true;
                } else {
                    return false;
                }
                break;

            case SEEK_END:
                if (strlen($GLOBALS[$this->varname]) + $offset >= 0) {
                    $this->position = strlen($GLOBALS[$this->varname]) + $offset;
                    return true;
                } else {
                    return false;
                }
                break;

            default:
                return false;
        }
    }

    function stream_metadata($path, $option, $var)
    {
        if($option == STREAM_META_TOUCH) {
            $url = parse_url($path);
            $varname = $url["host"];
            if(!isset($GLOBALS[$varname])) {
                $GLOBALS[$varname] = '';
            }
            return true;
        }
        return false;
    }
}
