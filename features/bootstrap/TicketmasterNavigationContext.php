<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\RawMinkContext;
use TestFramework\Pages\Ticketmaster\MainPage;
use TestFramework\Services\WebElementsService;
use TestFramework\Services\AssertService;
use TestFramework\Services\Service;

/**
 * Defines application features for Ticketmaster to navigate between tabs.
 */
class TicketmasterNavigationContext extends BaseFeatureContext
{
    protected static $base_URL = MainPage::BASE_URL;

    /**
     * @Given I am on Ticketmaster
     */
    public function iAmOnTicketmaster()
    {
        $this->goToBaseUrl();
        WebElementsService::getWebElementByXpath(MainPage::MUSIC_TAB_XPATH);
        WebElementsService::getWebElementByXpath(MainPage::ARTS_TAB_XPATH);
    }

    /**
     * @When I click on :headerButtonLabel
     */
    public function iClickOnHeaderButtonLabel($headerButtonLabel)
    {
        $xpath = MainPage::getHeaderXpathByLabel($headerButtonLabel);
        WebElementsService::getWebElementByXpath($xpath)->click();
    }

    /**
     * @Then the :pageLabel button is clicked
     */
    public function theButtonIsClicked($pageLabel)
    {
        $isElementExist = WebElementsService::isElementExists(MainPage::getHeaderXpathByLabel($pageLabel).MainPage::TAB_SELECTED_ADDON_XPATH);
        AssertService::assertEquals(true, $isElementExist);
    }

    /**
     * @Then the :pageLabel tab is loaded
     */
     public function thePageIsLoaded($pageLabel)
     {
         $isElementExist = WebElementsService::isElementExists(MainPage::getHeaderXpathByLabel($pageLabel).MainPage::TAB_SELECTED_ADDON_XPATH);
         AssertService::assertEquals(true, $isElementExist);
     }
}