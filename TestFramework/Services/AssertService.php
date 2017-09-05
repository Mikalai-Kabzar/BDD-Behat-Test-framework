<?php

namespace TestFramework\Services;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\RawMinkContext;
use TestFramework\Services\WebElementsService;
use PHPUnit_Framework_Assert;

/**
 * Assert services for autotests
 */
class AssertService extends Service
{
    /**
    * Assert two values equality.
    *
    * @param - $expected - expected value.
    * @param - $actual - actual value.
    */
    public static function assertEquals($expected, $actual)
    {
        PHPUnit_Framework_Assert::assertEquals($expected, $actual);
    }

    /**
    * Assert if webelement exist.
    *
    * @param - $expected - expected state of webelement existance.
    * @param - $xpath - xpath of webelement.
    */
    public static function assertWebElementExists($expected, $xpath)
    {
        PHPUnit_Framework_Assert::assertEquals($expected, WebElementsService::isElementExists($xpath));
    }
    
}