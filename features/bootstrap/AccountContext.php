<?php

use Behat\Gherkin\Node\TableNode;

use TestFramework\Pages\Application\MainPage;
use TestFramework\Pages\Application\SignInPage;
use TestFramework\Services\AssertService;
use TestFramework\Services\WebElementsService;

/**
 * Defines Application account context.
 */
class AccountContext extends BaseFeatureContext {

    /**
     * @When I hover on My account button
     */
    public function iHoverOnMyAccountButton() {
        MainPage::getMyAccountDropDown()->hoverButton();
    }

    /**
     * @When I wait :timeout seconds
     */
    public function iWaitSecond($timeout) {
        sleep($timeout);
    }

    /**
     * @When I hover music nav-button
     */
    public function iHoverMusicNavButton() {
        MainPage::hoverMusic();
    }

    /**
     * @When I click on :option option of My Account dropdown
     */
    public function iClickOnOptionOfMyAccountDropdown($option) {
        MainPage::getMyAccountDropDown()->clickOnOption($option);
    }

    /**
     * @When I click on option of My Account dropdown
     */
    public function iClickOnOptionOfMyAccountDropdownTable(TableNode $table) {
        //Get cell value of first column and first row
        $row = $table->getRow(0)[0];
        $value = preg_replace("/\"/", '', $row);
        MainPage::getMyAccountDropDown()->clickOnOption($value);
    }

    /**
     * @When I click on Sign in button
     */
    public function iClickOnSignInButton() {
        MainPage::getMyAccountDropDown()->clickSignIn();
    }

    /**
     * @Then My account dropdown is opened
     */
    public function myAccountDropdownIsOpened() {
        $isDropDownOpened = MainPage::getMyAccountDropDown()->isOptionsVisible();
        AssertService::assertEquals(true, $isDropDownOpened);
    }

    /**
     * @Then My account dropdown is not opened
     */
    public function myAccountDropdownIsNotOpened() {
        $isDropDownOpened = MainPage::getMyAccountDropDown()->isOptionsVisible();
        AssertService::assertEquals(false, $isDropDownOpened);
    }

    /**
     * @Then Sign in page is displayed
     */
    public function signInPageIsDisplayed() {
        $xpath = SignInPage::WELCOME_MESSAGE_XPATH;
        $isSignInPage = WebElementsService::isElementExists($xpath);
        AssertService::assertEquals(true, $isSignInPage);
    }

    /**
     * @Then Sign in page contains correct Welcome message
     */
    public function signInPageContainsCorrectWelcomeMessage() {
        $xpath = SignInPage::WELCOME_MESSAGE_XPATH;
        $actualMessage = WebElementsService::getWebElementText($xpath);
        AssertService::assertEquals(SignInPage::WELCOME_MESSAGE, $actualMessage);
    }

}
