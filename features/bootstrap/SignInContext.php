<?php

use TestFramework\Pages\Application\ErrorMessagesPage;
use TestFramework\Pages\Application\SignInToMyAccountPage;
use TestFramework\Services\AssertService;

/**
 * Defines Application sign in context.
 */
class SignInContext extends BaseFeatureContext {

    /**
     * @When I enter :password password to Application Password field on Sign In to My Account page
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
