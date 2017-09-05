<?php

namespace TestFramework\Pages\Ticketmaster;

use TestFramework\Services\WebElementsService;
use TestFramework\Services\AssertService;
use TestFramework\Services\Service;
use TestFramework\Pages\Ticketmaster\Dropdowns\MyAccountDropdown;

/**
 * Defines application features for Ticketmaster.
 */
class MainPage
{
    const BASE_URL = "http://www.ticketmaster.co.uk/";
    const TAB_SELECTED_ADDON_XPATH = "[@class='cat-on']"; 

    const MUSIC_TAB_XPATH = ".//*[@id='music']/a";
    const SPORT_TAB_XPATH = ".//*[@id='sports']/a";
    const ARTS_TAB_XPATH = ".//*[@id='arts']/a";
    const FAMILY_TAB_XPATH = ".//*[@id='family']/a";

    const MY_ACCOUNT_BUTTON_XPATH = ".//*[@id='account-button']";
   
    public static function getMyAccountDropDown(){
        return new MyAccountDropdown();
    }

    public static function getHeaderXpathByLabel($label){
        switch ($label) {
            case 'music':
                return MainPage::MUSIC_TAB_XPATH;
                break;
            case 'sport':
                return MainPage::SPORT_TAB_XPATH;
                break;
            case 'arts':
                return MainPage::ARTS_TAB_XPATH;
                break;
            case 'family':
                return MainPage::FAMILY_TAB_XPATH;
                break;
        }
    }
    
    public static function hoverMusic(){
        WebElementsService::hoverWebElement(MainPage::MUSIC_TAB_XPATH);
    }  
    
    public static function hoverSport(){
        WebElementsService::hoverWebElement(MainPage::SPORT_TAB_XPATH);
    } 

    public static function hoverArts(){
        WebElementsService::hoverWebElement(MainPage::ARTS_TAB_XPATH);
    }  
    
    public static function hoverFamily(){
        WebElementsService::hoverWebElement(MainPage::FAMILY_TAB_XPATH);
    }
}