<?php

namespace TestFramework\Services;

use PHPUnit_Framework_Assert;
use PHPUnit_Extensions_PhptTestCase_Logger;
use PHPUnit_Framework_ExpectationFailedException;

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';
/**
 * Assert services for autotests
 */
class AssertService {

    private static $assertMessage = '';

    /**
     * Get assert message.
     */
    public static function getAssertMessage() {
        return AssertService::$assertMessage;
    }
    
    /**
     * Assert two values equality.
     *
     * @param - $expected - expected value.
     * @param - $actual - actual value.
     */
    public static function assertEquals($expected, $actual) {
        AssertService::$assertMessage = '';
        try {
            PHPUnit_Framework_Assert::assertEquals($expected, $actual);   
        } catch (PHPUnit_Framework_ExpectationFailedException $e) {
            AssertService::$assertMessage = $e->getMessage();
        }
        if (AssertService::$assertMessage != '')  {
            PHPUnit_Framework_Assert::assertEquals($expected, $actual);
        }  
    }

    /**
     * Assert if webelement exist.
     *
     * @param - $expected - expected state of webelement existance.
     * @param - $xpath - xpath of webelement.
     */
    public static function assertWebElementExists($expected, $xpath) {
        AssertService::$assertMessage = '';
        
        try {
            PHPUnit_Framework_Assert::assertEquals($expected, WebElementsService::isElementExists($xpath));
        } catch (PHPUnit_Framework_ExpectationFailedException $e) {
            AssertService::$assertMessage = $e->getMessage();
        }
        if (AssertService::$assertMessage != '')  {
            PHPUnit_Framework_Assert::assertEquals($expected, WebElementsService::isElementExists($xpath));
        } 
    }

}
