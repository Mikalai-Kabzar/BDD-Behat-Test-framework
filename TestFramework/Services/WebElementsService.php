<?php

namespace TestFramework\Services;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\RawMinkContext;

/**
 * WebElement service.
 */
class WebElementsService extends Service
{
    public static function getWebElementText($xpath){
        return WebElementsService::getWebElementByXpath($xpath)->getText();
    } 

    public static function isWebElementVisible($xpath){
        return WebElementsService::getWebElementByXpath($xpath)->isVisible();
    } 

    public static function hoverWebElement($xpath){
        WebElementsService::getWebElementByXpath($xpath)->mouseOver();
    } 

    public static function getWebElementByCSS($CSS)
    {
        return parent::$session->getPage()->find('css', $CSS);
    }

    public static function clickOn($xpath)
    {
        WebElementsService::getWebElementByXpath($xpath)->click();
    }

    public static function getWebElementByXpath($Xpath)
    {
        WebElementsService::waitForElementExists($Xpath);
        return parent::$session->getPage()->find('xpath', $Xpath);
    }

    public static function fillField($locator, $searchString)
    {
        parent::$session->getPage()->fillField($locator, $searchString);
    }
    
    public static function isElementExists($Xpath)
    {
        $WebElement = WebElementsService::getWebElementByXpath($Xpath);
        $isPageContainsWebElement = false;
        if ($WebElement != null) {
            $isPageContainsWebElement = true;
        }
        return $isPageContainsWebElement;
    }

    public static function waitForElementExists($Xpath)
    {
        $isPageContainsWebElement = false;
        $attemptNumber = 0;
        $maxAttempt = 20;
        while (($attemptNumber < $maxAttempt) AND (!$isPageContainsWebElement)) {
            $WebElement = parent::$session->getPage()->find('xpath', $Xpath);
            if ($WebElement != null) {
                $isPageContainsWebElement = true;
                break;
            }
            $attemptNumber++;
            sleep(parent::actionTimeout/1000);
        }
    }
}
