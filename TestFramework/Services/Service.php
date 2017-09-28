<?php

namespace TestFramework\Services;

use Behat\Mink\Session;

/**
 * Abstract service.
 */
class Service
{
    /**
     * @var Session
     */
    protected static $session = null;

    const actionTimeout = 500;

    /**
     * @return Session
     */
    public static function getSession()
    {
        return self::$session;
    }

    /**
     * Set current session instance to $session variable.
     *
     * @param - $sessionToSet session to set to variable.
     */
    public static function setSession($sessionToSet)
    {
        Service::$session = $sessionToSet;
    }

    /**
     * Set session variable to null
     */
    public static function sessionToNull()
    {
        self::$session = null;
    }

}
