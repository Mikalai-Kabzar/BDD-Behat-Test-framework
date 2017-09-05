<?php

namespace TestFramework\Pages\Ticketmaster\Dropdowns;

use TestFramework\Services\WebElementsService;
use TestFramework\Services\AssertService;
use TestFramework\Services\Service;

/**
 * Defines dropdown.
 */
abstract class Dropdown
{
    protected static $keyWord = "keyWord";

    private function getButtonXpath(){
        return ".//button[@id='".$this::$keyWord."-button']";
    }

    private function getOptionsXpath(){
        return ".//div[@id='".$this::$keyWord."-options']";
    }

    private function getOptionsListXpath(){
        return ".//ul[@id='".$this::$keyWord."-list-options']";
    }

    public function hoverButton(){
        WebElementsService::hoverWebElement($this->getButtonXpath());
    } 

    public function isOptionsVisible(){
        return WebElementsService::isWebElementVisible($this->getOptionsXpath());
    }  
    
    public function clickOnMenuElement($xpath){
        WebElementsService::clickOn($xpath);
    } 

    protected function getOptionXpath($label){
        return $this->getOptionsListXpath()."/li/a[text()='".$label."']";
    } 
}