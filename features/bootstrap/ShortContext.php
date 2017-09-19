<?php
use TestFramework\Services\AssertService;
use TestFramework\Services\ReportPortalHTTPService;
use Behat\Testwork\Hook\Scope\AfterSuiteScope;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use Behat\Testwork\Tester\Result\TestResults;

use Behat\Behat\Hook\Scope\AfterFeatureScope;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Hook\Scope\BeforeFeatureScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Hook\Scope\BeforeStepScope;

/**
 * Defines Short context.
 */
class ShortContext extends BaseFeatureContext
{

    //private static $arrayWithSteps = array();
    private static $arrayWithSteps = array();
    /**
     *
     * @var ReportPortalHTTPService
     */
    protected static $httpService;

    public $result = 0;
    
    /**
     * @Given I want to calculate some value
     */
    public function iWantToCalculateSomeValue()
    {
    }

    /**
     * @When I calculate :value1 and :value2
     */
    public function iCalculateAnd($value1, $value2)
    {
        $this->result = $value1 + $value2;
    }

    /**
     * @Then I get :value1
     */
    public function iGet($value1)
    {
        AssertService::assertEquals($value1, $this->result);
    }
 
    /**
     * @BeforeSuite
     */
    public static function startLaunch(BeforeSuiteScope $event)
    {
        $suiteName = $event->getSuite()->getName();
        ShortContext::$httpService = new ReportPortalHTTPService();
        ShortContext::$httpService->launchTestRun('Test Run - '.$suiteName);
        ShortContext::$httpService->createRootItem($suiteName);
    }
    
    /**
     * @BeforeFeature
     */
    public static function startFeature(BeforeFeatureScope $event)
    {
        $featureName = $event->getFeature()->getTitle();
        ShortContext::$httpService->createFeatureItem($featureName);
    }
    
    /**
     * @BeforeScenario
     */
    public static function startScenario(BeforeScenarioScope $event)
    {
        ShortContext::$arrayWithSteps = array();
        $scenarioName = $event->getScenario()->getTitle();
        ShortContext::$httpService->createScenarioItem($scenarioName);
    }
    
    /**
     * @BeforeStep
     */
    public static function startStep(BeforeStepScope $event)
    {
        $keyWord = $event->getStep()->getKeyword();
        $stepName = $event->getStep()->getText();
        ShortContext::$httpService->createStepItem($keyWord.' : '.$stepName);
    }

    /**
     * @AfterStep
     */
    public static function finishStep(AfterStepScope $event)
    {
        array_push(ShortContext::$arrayWithSteps, $event->getStep());
        //ShortContext::$arrayWithSteps = array();
        $statusCode = $event->getTestResult()->getResultCode();
        ShortContext::$httpService->finishStepItem($statusCode,AssertService::getAssertMessage());
    }
    
    /**
     * @AfterScenario
     */
    public static function finishScenario(AfterScenarioScope $event)
    {
        $fullArrayWithStep =  $event->getScenario()->getSteps();
        //$fullArrayWithText =  array();
        //$diffArray = array_diff($fullArrayWithStep, ShortContext::$arrayWithSteps );
        
        
        
        $diffArray = array_udiff($fullArrayWithStep, ShortContext::$arrayWithSteps,
            function ($obj_a, $obj_b) {
                return strcmp($obj_a->getText(), $obj_b->getText());
            }
            );
        
        
        
        
//         //array_push($fullArrayWithText, $event->getStep()->getText());
//         foreach ($fullArrayWithStep as $step) {
//             array_push($fullArrayWithText, $step->getText());
//         }
        
        
//         //ShortContext::$arrayWithSteps = $array;
//         //ShortContext::$arrayWithSteps = array();
//         foreach (ShortContext::$arrayWithSteps as $value) {
//             print '____________________'.$value;
//         }
//         print '______!!!!!!!!!!!!!!!!!!!!!!!!!!!______';
//         foreach ($fullArrayWithText as $value) {
//             print '____________________'.$value;
//         }
        
        
        
        print '______!!!!!!!!!!!!!!_______=============================______________!!!!!!!!!!!!!______';
        foreach ($diffArray as $value) {
            print '__________--__________'.$value->getText();
            
            $keyWord = $value->getKeyword();
            $stepName = $value->getText();
            ShortContext::$httpService->createStepItem($keyWord.' : '.$stepName);
            
            ShortContext::$httpService->finishStepItem(TestResults::SKIPPED,'SKIPPED');
            
            
        }
        
        
        
        
        $statusCode = $event->getTestResult()->getResultCode(); 
        ShortContext::$httpService->finishScrenarioItem($statusCode);
    }
    
    /**
     * @AfterFeature
     */
    public static function finishFeature(AfterFeatureScope $event)
    {
        $statusCode = $event->getTestResult()->getResultCode(); 
        ShortContext::$httpService->finishFeatureItem($statusCode);
    }
    
    /**
     * @AfterSuite
     */
    public static function finishLaunch(AfterSuiteScope $event)
    {
        $statusCode = $event->getTestResult()->getResultCode(); 
        ShortContext::$httpService->finishRootItem($statusCode);
        ShortContext::$httpService->finishTestRun($statusCode);
    }
}
