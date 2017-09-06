<?php

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use TestFramework\Services\Service;

/**
 * Defines basic application features from the specific context.
 */
abstract class BaseFeatureContext extends RawMinkContext implements Context, SnippetAcceptingContext {

    protected static $base_URL = "base_URL";
    private static $picFolder = "/build/output/pic";

    /**
     * Go to base test URL. 
     */
    public function goToBaseUrl() {
        $this->getSession()->getDriver()->setTimeouts(['page load' => 10000]);
        try {
            $this->visitPath($this::$base_URL);
        } catch (Exception $e) {
            print "Page was not loaded for 10 seconds.";
        }
        $this->setSessionToServices();
    }

    /**
     * Go to custom URL. 
     */
    public function goToUrl($path) {
        $this->visitPath($path);
        $this->setSessionToServices();
    }

    /**
     * Set session instance to Service abstract class.
     */
    public function setSessionToServices() {
        Service::setSession($this->getSession());
    }

    /**
     * @BeforeSuite
     *
     * Clean up $picFolder folder before test suite execution.
     */
    public static function deletePictures() {
        $projectPath = realpath($_SERVER["DOCUMENT_ROOT"]);
        array_map('unlink', glob($projectPath . BaseFeatureContext::$picFolder . "/*"));
    }

    /**
     * @AfterStep
     *
     * Take screenshoot and save to $picFolder.
     */
    public function takeScreenshotAfterFailedStep($event) {
        if ($event->getTestResult()->getResultCode() === \Behat\Testwork\Tester\Result\TestResult::FAILED) {
            $driver = $this->getSession()->getDriver();
            $this->getSession()->getPage();
            if ($driver instanceof \Behat\Mink\Driver\Selenium2Driver) {
                $scenarioName = $event->getFeature()->getTitle();
                $stepText = $event->getStep()->getText();
                $fileName = preg_replace('#[^a-zA-Z0-9\._= -,]#', '', "Feature = " . $scenarioName . ", Step = " . $stepText) . '.png';
                $projectPath = realpath($_SERVER["DOCUMENT_ROOT"]);
                $fullFilePath = $projectPath . BaseFeatureContext::$picFolder . "/" . $fileName;
                if (!file_exists($fullFilePath)) {
                    file_put_contents($fullFilePath, $this->getSession()->getScreenshot());
                    print "Screenshot for '{$stepText}' placed in " . $fullFilePath . "\n";
                }
            }
        }
    }

}
