<?php

use Behat\Testwork\Tester\Result\TestResult;
use BehatReportPortal\BehatReportPortalAnnotations;
use BehatReportPortal\BehatReportPortalService;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Testwork\Hook\Scope\HookScope;
use ReportPortalBasic\Service\ReportPortalHTTPService;
use TestFramework\Services\Service;
use WebDriver\Exception;

/**
 * Defines basic application features from the specific context.
 */
abstract class BaseFeatureContext extends RawMinkContext implements Context, SnippetAcceptingContext, BehatReportPortalAnnotations
{
    protected static $base_URL = "base_URL";

    /**
     * Go to base test URL.
     */
    public function goToBaseUrl()
    {
        $this->goToUrl($this::$base_URL);
    }

    /**
     * Go to custom URL.
     * @param string $path - path to url
     */
    public function goToUrl(string $path)
    {
        $this->getSession()
            ->getDriver()
            ->setTimeouts([
                'page load' => 10000
            ]);
        try {
            $this->visitPath($path);
        } catch (Exception $e) {
            print "Page was not loaded for 10 seconds.";
        }
        $this->getSession()
            ->getDriver()
            ->maximizeWindow();
        $this->getSession()
            ->getDriver()
            ->setTimeouts([
                'page load' => 30000
            ]);
        Service::setSession($this->getSession());
    }

    public static function startLaunch(HookScope $event)
    {
        if (!ReportPortalHTTPService::isSuiteRunned()) {
            ReportPortalHTTPService::configureReportPortalHTTPService('config.yaml');
            BehatReportPortalService::startLaunch($event);
        }
    }

    public static function startFeature(HookScope $event)
    {
        if (!ReportPortalHTTPService::isFeatureRunned()) {
            BehatReportPortalService::startFeature($event);
        }
    }

    public static function startScenario(HookScope $event)
    {
        if (!ReportPortalHTTPService::isScenarioRunned()) {
            BehatReportPortalService::startScenario($event);
        }
    }

    public static function startStep(HookScope $event)
    {
        if (!ReportPortalHTTPService::isStepRunned()) {
            BehatReportPortalService::startStep($event);
        }
    }

    public static function finishStep(HookScope $event)
    {

        if (ReportPortalHTTPService::isStepRunned()) {
            $pictureAsString = '';
            if ($event->getTestResult()->getResultCode() === TestResult::FAILED) {
                $session = Service::getSession();
                if ($session != null) {
                    $pictureAsString = $session->getDriver()->getScreenshot();
                }
            }
            BehatReportPortalService::finishStep($event, $pictureAsString);
        }
    }

    public static function finishScenario(HookScope $event)
    {
        if (ReportPortalHTTPService::isScenarioRunned()) {
            Service::sessionToNull();
            BehatReportPortalService::finishScenario($event);
        }
    }

    public static function finishFeature(HookScope $event)
    {
        if (ReportPortalHTTPService::isFeatureRunned()) {
            BehatReportPortalService::finishFeature($event);
        }
    }

    public static function finishLaunch(HookScope $event)
    {
        if (ReportPortalHTTPService::isSuiteRunned()) {
            BehatReportPortalService::finishLaunch($event);
        }
    }
}
