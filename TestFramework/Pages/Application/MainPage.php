<?php

namespace TestFramework\Pages\Application;

use TestFramework\Pages\Application\Dropdowns\MyAccountDropdown;
use TestFramework\Services\WebElementsService;


/**
 * Defines Main page.
 */
class MainPage {

    const BASE_URL = "http://www.ticketmaster.co.uk/";
    const TAB_SELECTED_ADDON_XPATH = "[@class='cat-on']";
    const MUSIC_TAB_XPATH = ".//*[@id='music']/a";
    const SPORT_TAB_XPATH = ".//*[@id='sports']/a";
    const ARTS_TAB_XPATH = ".//*[@id='arts']/a";
    const FAMILY_TAB_XPATH = ".//*[@id='family']/a";
    const MY_ACCOUNT_BUTTON_XPATH = ".//*[@id='account-button']";
    const CREATE_ACCOUNT_BUTTON_XPATH = ".//*[@id='mytm_link']/a[contains(@href,'create')]";

    /**
     * Return new MyAccountDropDown instance.
     *
     * @return MyAccountDropDown instance.
     */
    public static function getMyAccountDropDown() {
        return new MyAccountDropdown();
    }

    /**
     * Get header xpath by label.
     *
     * @param - $label to find xpath.
     *
     * @return string with xpath to find button with $label.
     */
    public static function getHeaderXpathByLabel($label) {
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

    /**
     * Hover 'Music' navigation button.
     */
    public static function hoverMusic() {
        WebElementsService::hoverWebElement(MainPage::MUSIC_TAB_XPATH);
    }

    /**
     * Hover 'Sport' navigation button.
     */
    public static function hoverSport() {
        WebElementsService::hoverWebElement(MainPage::SPORT_TAB_XPATH);
    }

    /**
     * Hover 'Arts' navigation button.
     */
    public static function hoverArts() {
        WebElementsService::hoverWebElement(MainPage::ARTS_TAB_XPATH);
    }

    /**
     * Hover 'Family' navigation button.
     */
    public static function hoverFamily() {
        WebElementsService::hoverWebElement(MainPage::FAMILY_TAB_XPATH);
    }

    /**
     * Click 'Create Account' button.
     */
    public static function clickCreateAccount() {
        WebElementsService::clickOn(MainPage::CREATE_ACCOUNT_BUTTON_XPATH);
    }

}
