<?php
use TestFramework\Services\AssertService;
use TestFramework\Services\ReportPortalHTTPService;
use Behat\Behat\Context\Context;
use Behat\Testwork\Hook\Scope\AfterSuiteScope;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;

use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeFeatureScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Testwork\Hook\Call\BeforeSuite;
/**
 * Defines Short context.
 */
class ShortContext extends BaseFeatureContext
{

    /**
     *
     * @var ReportPortalHTTPService
     */
    protected static $httpService;

    public $result = 0;

    // /**
    // *
    // * @var ReportPortalHTTPService
    // */
    // public $httpService;
    
    /**
     * @Given I want to calculate some value
     */
    public function iWantToCalculateSomeValue()
    {
        // $this->httpService = new ReportPortalHTTPService();
        
        // $res = $this->httpService->login();
        
        // echo $client->
        // echo $res->getStatusCode();
        // "200"
        // echo $res->getHeader('content-type')[0];
        // 'application/json; charset=utf8'
        
        // echo $res->getBody();
        
        // echo '\n';
        // echo '\n';
        // echo $this->httpService->getValueFromJSON('email', $res);
        // $res = $this->httpService->launchTestRun();
        // $res = $this->httpService->finishTestRun();
        // finishTestRun()
        
        // echo $this->httpService->getValueFromJSON('id', $res);
        
        // public function request($method, $uri = '', array $options = [])
        // {
        // $options[RequestOptions::SYNCHRONOUS] = true;
        // return $this->requestAsync($method, $uri, $options)->wait();
        // }
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
        ShortContext::$httpService->launchTestRun($suiteName);
    }

    /**
     * @AfterSuite
     */
    public static function finishLaunch(AfterSuiteScope $event)
    {
        $statusCode = $event->getTestResult()->isPassed();
        if($statusCode) {
            $status = 'PASSED';
        } else {
            $status = 'FAILED';
        }
        ShortContext::$httpService->finishTestRun($status);
    }
}
