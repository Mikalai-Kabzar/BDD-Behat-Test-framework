<?php
namespace TestFramework\Services;

use PHPUnit_Framework_AssertionFailedError;

/**
 * Assert services for autotests
 */
class AssertService
{

    private static $assertMessage = '';

    private static $stackTrace = '';

    /**
     * Get assert message.
     */
    public static function getAssertMessage()
    {
        return AssertService::$assertMessage;
    }

    /**
     * Get stack trace message.
     */
    public static function getStackTraceMessage()
    {
        return AssertService::$stackTrace;
    }

    /**
     * Assert two values equality.
     *
     * @param - $expected
     *            - expected value.
     * @param - $actual
     *            - actual value.
     */
    public static function assertEquals($expected, $actual)
    {
        AssertService::handleException('PHPUnit_Framework_Assert::assertEquals', $expected, $actual);
    }

    /**
     * Assert if webelement exist.
     *
     * @param - $expected
     *            - expected state of webelement existance.
     * @param - $xpath
     *            - xpath of webelement.
     */
    public static function assertWebElementExists($expected, $xpath)
    {
        AssertService::handleException('PHPUnit_Framework_Assert::assertEquals', $expected, WebElementsService::isElementExists($xpath));
    }

    private static function handleException($func, ...$params)
    {
        AssertService::$assertMessage = '';
        AssertService::$stackTrace = '';
        try {
            $func(...$params);
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            AssertService::$assertMessage = $e->getMessage();
            AssertService::$stackTrace = $e->getTraceAsString();
            throw new PHPUnit_Framework_AssertionFailedError();
        }
    }
}
