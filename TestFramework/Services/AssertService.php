<?php

namespace TestFramework\Services;

use PHPUnit_Framework_AssertionFailedError;
use BehatReportPortal\BehatReportPortalService;

/**
 * Assert services for autotests
 */
class AssertService
{
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
        self::asserEquals($expected, $actual);
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
        self::asserEquals($expected, WebElementsService::isElementExists($xpath));
    }

    private static function asserEquals($func, ...$params)
    {
        self::handleException('PHPUnit_Framework_Assert::assertEquals', ...$params);
    }

    private static function handleException($func, ...$params)
    {
        try {
            $func(...$params);
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            BehatReportPortalService::setAssertMessage($e->getMessage());
            BehatReportPortalService::setStackTraceMessage($e->getTraceAsString());
            throw new PHPUnit_Framework_AssertionFailedError();
        }
    }
}
