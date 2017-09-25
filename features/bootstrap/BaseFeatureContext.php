<?php

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

    private static $picFolder = "build" . DIRECTORY_SEPARATOR . "output" . DIRECTORY_SEPARATOR . "pic";

    // function __construct() {
    // BaseFeatureContext::$httpService = new ReportPortalHTTPService();
    // }
    
    /**
     * Go to base test URL.
     */
    public function goToBaseUrl()
    {
        $this->goToUrl($this::$base_URL);
    }

    /**
     * Go to custom URL.
     */
    public function goToUrl($path)
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
            'page load' => 15000
        ]);
        $this->setSessionToServices();
    }

    /**
     * Set session instance to Service abstract class.
     */
    public function setSessionToServices()
    {
        Service::setSession($this->getSession());
    }

    /**
     * @BeforeSuite
     *
     * Clean up $picFolder folder before test suite execution.
     */
    public static function deletePictures()
    {
        $projectPath = dirname(__FILE__, 3);
        $path = BaseFeatureContext::$picFolder;
        if (file_exists($path)) {
            BaseFeatureContext::delTree($path);
        }
        mkdir($path);
    }

    public static function delTree($dir)
    {
        $files = array_diff(scandir($dir), array(
            '.',
            '..'
        ));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? BaseFeatureContext::delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

     
    /**
     * 
     * Take screenshoot and save to $picFolder.
     */
    public function takeScreenshotAfterFailedStep($event)
    {
        if ($event->getTestResult()->getResultCode() === \Behat\Testwork\Tester\Result\TestResult::FAILED) {
            $regexp = '#[^a-zA-Z0-9 ,_\.=]#';
            $driver = $this->getSession()->getDriver();
            if ($driver instanceof \Behat\Mink\Driver\Selenium2Driver) {
                $scenarioName = $event->getFeature()->getTitle();
                $projectPath = dirname(__FILE__, 3);
                $folderName = BaseFeatureContext::$picFolder . DIRECTORY_SEPARATOR . preg_replace($regexp, '', $scenarioName);
                if (! file_exists($folderName)) {
                    mkdir($folderName);
                }
                $stepText = $event->getStep()->getText();
                $fileName = preg_replace($regexp, '', "Feature (" . $scenarioName . "), Step (" . $stepText . ")") . ".png";
                $fullFilePath = $folderName . DIRECTORY_SEPARATOR . $fileName;
                if (! file_exists($fullFilePath)) {
                    file_put_contents($fullFilePath, $this->getSession()->getScreenshot());
                    print "Screenshot for '{$stepText}' placed in " . $fullFilePath . "\n";
                }
            }
        }
    }

    public static function startLaunch(HookScope $event)
    { 
        
        if (! ReportPortalHTTPService::isSuiteRunned()) {
            print 'start launch';
            ReportPortalHTTPService::configureReportPortalHTTPService('config.yaml');
            BehatReportPortalService::startLaunch($event);
        }
    }

    public static function startFeature(HookScope $event)
    {
        if (! ReportPortalHTTPService::isFeatureRunned()) {
            BehatReportPortalService::startFeature($event);
        }
    }

    public static function startScenario(HookScope $event)
    {
        if (! ReportPortalHTTPService::isScenarioRunned()) {
            BehatReportPortalService::startScenario($event);
        }
    }

    public static function startStep(HookScope $event)
    {
        if (! ReportPortalHTTPService::isStepRunned()) {
            BehatReportPortalService::startStep($event);
        }
    }

    public static function finishStep(HookScope $event)
    {
        if (ReportPortalHTTPService::isStepRunned()) {
            BehatReportPortalService::finishStep($event);
        }
    }

    public static function finishScenario(HookScope $event)
    {
        if (ReportPortalHTTPService::isScenarioRunned()) {
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
