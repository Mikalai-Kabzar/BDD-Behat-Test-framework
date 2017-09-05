<?php

namespace TestFramework\Services;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Abstract service.
 */
class Service
{
    protected static $session;
    const actionTimeout = 500;

    public static function setSession($sessionToSet) {
        Service::$session = $sessionToSet;
    }
}