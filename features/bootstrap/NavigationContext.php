<?php


use TestFramework\Pages\Application\MainPage;
use TestFramework\Services\WebElementsService;
use TestFramework\Services\AssertService;

/**
 * Defines Application navigation context.
 */
class NavigationContext extends BaseFeatureContext {

    protected static $base_URL = MainPage::BASE_URL;

    /**
     * @Given I am on Application
     */
    public function iAmOnApplication() {
        $this->goToBaseUrl();
        WebElementsService::getWebElement(MainPage::MUSIC_TAB_XPATH);
        WebElementsService::getWebElement(MainPage::ARTS_TAB_XPATH);
    }

    /**
     * @When I click on Create Account button
     */
    public function iClickOnCreateAccountButton() {
        MainPage::clickCreateAccount();
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
        AssertService::assertEquals(true, $isElementExist,'The ('.$pageLabel.') button does not clicked');
    }

    /**
     * @Then the :pageLabel tab is loaded
     */
    public function thePageIsLoaded($pageLabel) {
        $xpath = MainPage::getHeaderXpathByLabel($pageLabel) . MainPage::TAB_SELECTED_ADDON_XPATH;
        $isElementExist = WebElementsService::isElementExists($xpath);
        AssertService::assertEquals(true, $isElementExist,'The ('.$pageLabel.') tab does not loaded');
    }

}
