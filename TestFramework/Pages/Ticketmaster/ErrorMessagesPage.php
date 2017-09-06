<?php

namespace TestFramework\Pages\Ticketmaster;

use TestFramework\Services\WebElementsService;

/**
 * Defines Error messages Page.
 */
class ErrorMessagesPage {

    const ERROR_MESSAGES_PAGE_XPATH = ".//*[@id='error_messages']";

    /**
     * Check if ErrorMessagesPage displayed.
     *
     * @return true if ErrorMessagesPage displayed.
     */
    public static function isDisplayed() {
        return WebElementsService::isElementExists(ErrorMessagesPage::ERROR_MESSAGES_PAGE_XPATH);
    }

}
