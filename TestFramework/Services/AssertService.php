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
    public static function assertEquals($expected, $actual)
    {
        PHPUnit_Framework_Assert::assertEquals($expected, $actual);
    }

    public static function assertWebElementExists($expected, $Xpath)
    {
        PHPUnit_Framework_Assert::assertEquals($expected, WebElementsService::isElementExists($Xpath));
    }
    
}