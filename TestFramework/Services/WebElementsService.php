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
    /**
    * Get webelement text.
    *
    * @param - $xpath of web element.
    *
    * @return - text of webelement.
    */
    public static function getWebElementText($xpath){
        return WebElementsService::getWebElementByXpath($xpath)->getText();
    } 

    /**
    * Check if webelement visible.
    *
    * @param - $xpath of web element.
    *
    * @return - true if webelement visible.
    */
    public static function isWebElementVisible($xpath){
        return WebElementsService::getWebElementByXpath($xpath)->isVisible();
    } 

    /**
    * Hover over webelement.
    *
    * @param - $xpath of web element.
    */
    public static function hoverWebElement($xpath){
        WebElementsService::getWebElementByXpath($xpath)->mouseOver();
    } 

    /**
    * Get webelement by CSS.
    *
    * @param - $CSS of web element.
    *
    * @return - webelement.
    */
    public static function getWebElementByCSS($CSS)
    {
        return parent::$session->getPage()->find('css', $CSS);
    }

    /**
    * Click on webelement.
    *
    * @param - $xpath of web element.
    */
    public static function clickOn($xpath)
    {
        WebElementsService::getWebElementByXpath($xpath)->click();
    }

    /**
    * Get webelement by Xpath.
    *
    * @param - $xpath of web element.
    *
    * @return - webelement.
    */
    public static function getWebElementByXpath($xpath)
    {
        WebElementsService::waitForElementExists($xpath);
        return parent::$session->getPage()->find('xpath', $xpath);
    }

    /**
    * Fill field with value.
    *
    * @param - $locator of web element.
    * @param - $searchString string to set.   
    */
    public static function fillField($locator, $searchString)
    {
        parent::$session->getPage()->fillField($locator, $searchString);
    }

    /**
    * Check if webelement exist.
    *
    * @param - $xpath of web element.
    *
    * @return - true if webelement exist.
    */
    public static function isElementExists($xpath)
    {
        $WebElement = WebElementsService::getWebElementByXpath($xpath);
        $isPageContainsWebElement = false;
        if ($WebElement != null) {
            $isPageContainsWebElement = true;
        }
        return $isPageContainsWebElement;
    }

    /**
    * Wait for webelement exist on page.
    *
    * @param - $xpath of web element.
    */
    public static function waitForElementExists($xpath)
    {
        $isPageContainsWebElement = false;
        $attemptNumber = 0;
        $maxAttempt = 20;
        while (($attemptNumber < $maxAttempt) AND (!$isPageContainsWebElement)) {
            $WebElement = parent::$session->getPage()->find('xpath', $xpath);
            if ($WebElement != null) {
                $isPageContainsWebElement = true;
                break;
            }
            $attemptNumber++;
            sleep(parent::actionTimeout/1000);
        }
    }
}
