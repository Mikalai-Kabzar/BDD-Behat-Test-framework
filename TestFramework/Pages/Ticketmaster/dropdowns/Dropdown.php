<?php

namespace TestFramework\Pages\Ticketmaster\Dropdowns;

use TestFramework\Services\WebElementsService;

/**
 * Defines abstract dropdown.
 */
abstract class Dropdown {

    const BUTTON_XPATH = ".//button[@id='%s-button']";
    const OPTION_XPATH = Dropdown::OPTIONS_LIST_XPATH."/li/a[text()='%s']";
    const OPTIONS_XPATH = ".//div[@id='%s-options']";
    const OPTIONS_LIST_XPATH = ".//ul[@id='%s-list-options']";
    
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
     * @return string with xpath of $label option.
     */
    protected function getOptionXpath($label) {
        return sprintf(Dropdown::OPTION_XPATH,$this::$keyWord,$label);
    }

    /**
     * Return xpath of dropdown button.
     *
     * @return string with xpath of dropdown button.
     */
    private function getButtonXpath() {
        return sprintf(Dropdown::BUTTON_XPATH,$this::$keyWord);
    }

    /**
     * Return xpath of options.
     *
     * @return string with xpath of options.
     */
    private function getOptionsXpath() {
        return sprintf(Dropdown::OPTIONS_XPATH,$this::$keyWord);
    }

    /**
     * Return xpath of options list.
     *
     * @return string with xpath of options list.   
     */
    private function getOptionsListXpath() {
        return sprintf(Dropdown::OPTIONS_LIST_XPATH,$this::$keyWord);
    }

}
