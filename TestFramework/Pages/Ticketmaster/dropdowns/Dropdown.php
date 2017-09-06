<?php

namespace TestFramework\Pages\Ticketmaster\Dropdowns;

use TestFramework\Services\WebElementsService;

/**
 * Defines abstract dropdown.
 */
abstract class Dropdown {

    protected static $keyWord = "keyWord";

    /**
     * Hover dropdown button.
     */
    public function hoverButton() {
        WebElementsService::hoverWebElement($this->getButtonXpath());
    }

    /**
     * Check if options displayed.
     *
     * @return true if options displayed.
     */
    public function isOptionsVisible() {
        return WebElementsService::isWebElementVisible($this->getOptionsXpath());
    }

    /**
     * Click on $xpath menu element.
     *
     * @param $xpath - xpath of menu element to click.
     */
    public function clickOnMenuElement($xpath) {
        WebElementsService::clickOn($xpath);
    }

    /**
     * Return xpath of $label option.
     *
     * @param $label - label of option.
     *
     * @return xpath of $label option.
     */
    protected function getOptionXpath($label) {
        return $this->getOptionsListXpath() . "/li/a[text()='" . $label . "']";
    }

    /**
     * Return xpath of dropdown button.
     *
     * @return xpath of dropdown button.
     */
    private function getButtonXpath() {
        return ".//button[@id='" . $this::$keyWord . "-button']";
    }

    /**
     * Return xpath of options.
     *
     * @return xpath of options.
     */
    private function getOptionsXpath() {
        return ".//div[@id='" . $this::$keyWord . "-options']";
    }

    /**
     * Return xpath of options list.
     *
     * @return xpath of options list.   
     */
    private function getOptionsListXpath() {
        return ".//ul[@id='" . $this::$keyWord . "-list-options']";
    }

}
