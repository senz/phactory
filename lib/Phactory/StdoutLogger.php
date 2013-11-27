<?php
namespace Phactory;

use Psr\Log\AbstractLogger;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;

/**
 * @author Konstantin G Romanov
 */
class StdoutLogger extends AbstractLogger {
    private static $_supported_levels = array(
        LogLevel::EMERGENCY,
        LogLevel::ALERT,
        LogLevel::CRITICAL,
        LogLevel::ERROR,
        LogLevel::WARNING,
        LogLevel::NOTICE,
        LogLevel::INFO,
        LogLevel::DEBUG,
    );

    private $_stdout;

    public function __construct()
    {
        $this->_stdout = STDOUT;
    }

    public function setStdout($resource) {
        if (!is_resource($resource)) {
            throw new \InvalidArgumentException("Argument is not a resource");
        }
        $this->_stdout = $resource;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        self::checkLevel($level);
        $interpolatedMessage = empty($context) ? $message : self::replaceContext($message, $context);
        $interpolatedMessage = "[{$level}] {$interpolatedMessage}\n";

        fwrite($this->_stdout, $interpolatedMessage);
    }

    private static function replaceContext($message, array $context)
    {
        $placeholders = array();
        foreach ($context as $placeholder => $value) {
            if (preg_match('/^[A-Za-z0-9_\.]+$/', $placeholder)) {
                $placeholders["{{$placeholder}}"] = $value;
            }
        }
        return strtr($message, $placeholders);
    }

    private static function checkLevel($level)
    {
        if (!in_array($level, self::$_supported_levels)) {
            throw new InvalidArgumentException("Level '{$level}' is not supported");
        }
    }
}
