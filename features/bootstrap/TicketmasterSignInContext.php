<?php

use TestFramework\Pages\Ticketmaster\ErrorMessagesPage;
use TestFramework\Pages\Ticketmaster\SignInToMyAccountPage;
use TestFramework\Services\AssertService;

/**
 * Defines Ticketmaster sign in context.
 */
class TicketmasterSIgnInContext extends BaseFeatureContext {

    /**
     * @When I enter :password password to Ticketmaster Password field on Sign In to My Account page
     */
    public function iEnterPasswordToTicketmasterPasswordFieldOnSignInToMyAccountPage($password) {
        SignInToMyAccountPage::fillPassword($password);
    }

    /**
     * @When I enter :email to My e-mail address is field.
     */
    public function iEnterToMyEMailAddressIsField($email) {
        SignInToMyAccountPage::fillEmail($email);
    }

    /**
     * @Then I dont get There are problems with your submission page
     */
    public function iDontGetThereAreProblemsWithYourSubmissionPage() {
        $isErrorMessageDisplayed = ErrorMessagesPage::isDisplayed();
        AssertService::assertEquals(false, $isErrorMessageDisplayed);
    }

    /**
     * @When I click on Accept and Continue
     */
    public function iClickOnAcceptAndContinue() {
        SignInToMyAccountPage::acceptAndContinue();
    }

    /**
     * @Then I get There are problems with your submission page
     */
    public function iGetThereAreProblemsWithYourSubmissionPage() {
        $isErrorMessageDisplayed = ErrorMessagesPage::isDisplayed();
        AssertService::assertEquals(true, $isErrorMessageDisplayed);
    }

}
