<?php

namespace TestFramework\Services;

/**
 * WebElement service.
 */
class WebElementsService extends Service {

    /**
     * Get webelement text.
     *
     * @param - $xpath of web element.
     *
     * @return - text of webelement.
     */
    public static function getWebElementText($xpath) {
        return WebElementsService::getWebElement($xpath)->getText();
    }

    /**
     * Check if webelement visible.
     *
     * @param - $xpath of web element.
     *
     * @return - true if webelement visible.
     */
    public static function isWebElementVisible($xpath) {
        return WebElementsService::getWebElement($xpath)->isVisible();
    }

    /**
     * Hover over webelement.
     *
     * @param - $xpath of web element.
     */
    public static function hoverWebElement($xpath) {
        WebElementsService::getWebElement($xpath)->mouseOver();
    }

    /**
     * Get webelement by CSS.
     *
     * @param - $CSS of web element.
     *
     * @return - webelement.
     */
    public static function getWebElementByCSS($CSS) {
        return parent::$session->getPage()->find('css', $CSS);
    }

    /**
     * Click on webelement.
     *
     * @param - $xpath of web element.
     */
    public static function clickOn($xpath) {
        WebElementsService::getWebElement($xpath)->click();
    }

    /**
     * Get webelement by xpath.
     *
     * @param - $xpath of web element.
     *
     * @return - webelement.
     */
    public static function getWebElement($xpath) {
        WebElementsService::waitForElementExists($xpath);
        return parent::$session->getPage()->find('xpath', $xpath);
    }

    /**
     * Fill field with value.
     *
     * @param - $locator of web element.
     * @param - $value string to set.   
     */
    public static function fillField($locator, $value) {
        parent::$session->getPage()->fillField($locator, $value);
    }

    /**
     * Check if webelement exists.
     *
     * @param - $xpath of web element.
     *
     * @return - true if webelement exist.
     */
    public static function isElementExists($xpath) {
        $WebElement = WebElementsService::getWebElement($xpath);
        $isPageContainsWebElement = false;
        if ($WebElement != null) {
            $isPageContainsWebElement = true;
        }
        return $isPageContainsWebElement;
    }

    /**
     * Wait for webelement existence on page.
     *
     * @param - $xpath of web element.
     */
    public static function waitForElementExists($xpath) {
        $isPageContainsWebElement = false;
        $attemptNumber = 0;
        $maxAttempt = 20;
        while (($attemptNumber < $maxAttempt) AND ( !$isPageContainsWebElement)) {
            $WebElement = WebElementsService::$session->getPage()->find('xpath', $xpath);
            if ($WebElement != null) {
                $isPageContainsWebElement = true;
                break;
            }
            $attemptNumber++;
            sleep(WebElementsService::actionTimeout / 1000);
        }
    }

}
