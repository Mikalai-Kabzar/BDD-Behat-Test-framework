<?php

namespace TestFramework\Services;

use Behat\Mink\Session;

/**
 * Abstract service.
 */
class Service {
    
    /**
     * @var Session
     */
    protected static $session;

    const actionTimeout = 500;

    /**
     * Set current session instance to $session variable.
     *
     * @param - $sessionToSet session to set to variable.
     */
    public static function setSession($sessionToSet) {
        Service::$session = $sessionToSet;
    }

}
