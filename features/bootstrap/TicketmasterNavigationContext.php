<?php

use TestFramework\Pages\Ticketmaster\MainPage;
use TestFramework\Services\WebElementsService;
use TestFramework\Services\AssertService;

/**
 * Defines Ticketmaster navigation context.
 */
class TicketmasterNavigationContext extends BaseFeatureContext {

    protected static $base_URL = MainPage::BASE_URL;

    /**
     * @Given I am on Ticketmaster
     */
    public function iAmOnTicketmaster() {
        $this->goToBaseUrl();
        WebElementsService::getWebElement(MainPage::MUSIC_TAB_XPATH);
        WebElementsService::getWebElement(MainPage::ARTS_TAB_XPATH);
    }

    /**
     * @When I click on :headerButtonLabel
     */
    public function iClickOnHeaderButtonLabel($headerButtonLabel) {
        $xpath = MainPage::getHeaderXpathByLabel($headerButtonLabel);
        WebElementsService::getWebElement($xpath)->click();
    }

    /**
     * @Then the :pageLabel button is clicked
     */
    public function theButtonIsClicked($pageLabel) {
        $xpath = MainPage::getHeaderXpathByLabel($pageLabel) . MainPage::TAB_SELECTED_ADDON_XPATH;
        $isElementExist = WebElementsService::isElementExists($xpath);
        AssertService::assertEquals(true, $isElementExist);
    }

    /**
     * @Then the :pageLabel tab is loaded
     */
    public function thePageIsLoaded($pageLabel) {
        $xpath = MainPage::getHeaderXpathByLabel($pageLabel) . MainPage::TAB_SELECTED_ADDON_XPATH;
        $isElementExist = WebElementsService::isElementExists($xpath);
        AssertService::assertEquals(true, $isElementExist);
    }

}
