<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Gherkin\Node\PyStringNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use TestFramework\Services\WebElementsService;
use TestFramework\Services\AssertService;
use TestFramework\Services\Service;

/**
 * Defines application features from the specific context.
 */
class BaseFeatureContext extends RawMinkContext implements Context, SnippetAcceptingContext
{
    protected static $base_URL = "base_URL";
    private static $picFolder = "/build/output/pic";

    public function goToBaseUrl()
    {
        $this->getSession()->getDriver()->setTimeouts(['page load' => 10000]); 
        try {
            $this->visitPath($this::$base_URL);
        }
        catch(Exception $e) {
            print "Page was not loaded for 10 seconds.";
        }
        $this->setSessionToServices();
    }

    public function goToUrl($path)
    {
        $this->visitPath($path);
        $this->setSessionToServices();
    }

    public function setSessionToServices()
    {
        Service::setSession($this->getSession());
    }

    /**
    * @AfterStep
    */
    public function takeScreenshotAfterFailedStep($event)
    {
        if ($event->getTestResult()->getResultCode() === \Behat\Testwork\Tester\Result\TestResult::FAILED) {
            $driver = $this->getSession()->getDriver();
            $this->getSession()->getPage();
            if ($driver instanceof \Behat\Mink\Driver\Selenium2Driver) {
                $scenarioName = $event->getFeature()->getTitle();
                $stepText = $event->getStep()->getText();
                $fileName = preg_replace('#[^a-zA-Z0-9\._= -,]#', '', "Feature = ".$scenarioName.", Step = ".$stepText) . '.png';
                $projectPath = realpath($_SERVER["DOCUMENT_ROOT"]);
                $fullFilePath = $projectPath . BaseFeatureContext::$picFolder . "/" . $fileName;
                if (!file_exists($fullFilePath)){
                    $this->saveScreenshotToFolder($fullFilePath);
                    //file_put_contents($fileName, $this->getSession()->getScreenshot());
                    print "Screenshot for '{$stepText}' placed in " . $fullFilePath . "\n";                    
                }
            }
        }
    }

    /**
    * @BeforeSuite
    */
    public static function deletePictures()
    {
        $projectPath = realpath($_SERVER["DOCUMENT_ROOT"]);
        array_map('unlink', glob($projectPath.BaseFeatureContext::$picFolder."/*"));
    }

    private function saveScreenshotToFolder($fileName)
    {
        file_put_contents($fileName, $this->getSession()->getScreenshot()); 
    }
}