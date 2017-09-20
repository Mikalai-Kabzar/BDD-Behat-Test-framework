<?php
use Behat\Behat\Hook\Scope\AfterFeatureScope;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Hook\Scope\BeforeFeatureScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Hook\Scope\BeforeStepScope;
use Behat\Behat\Hook\Scope\ScenarioScope;
use Behat\Behat\Output\Printer\ConsoleOutputPrinter;
use Behat\Testwork\Hook\Scope\AfterSuiteScope;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use Behat\Testwork\Tester\Result\TestResults;
use TestFramework\Services\AssertService;
use TestFramework\Services\ReportPortalHTTPService;

/**
 * Defines Short context.
 */
class ShortContext extends BaseFeatureContext
{

    private const SCENARIO_OUTLINE_KEYWORD = 'Example';

    private static $arrayWithSteps = array();

    private static $arrayWithScenarios = array();

    private static $arrayWithFeatures = array();

    private const launchMode = 'DEFAULT';

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
    {}

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
        ShortContext::$httpService->launchTestRun('Test Run - ' . $suiteName, '', ShortContext::launchMode, array());
        ShortContext::$httpService->createRootItem($suiteName, '', array());
    }

    /**
     * @BeforeFeature
     */
    public static function startFeature(BeforeFeatureScope $event)
    {
        $featureName = $event->getFeature()->getTitle();
        $keyWord = $event->getFeature()->getKeyword();
        ShortContext::$httpService->createFeatureItem($keyWord . ' : ' . $featureName, '');
    }

    /**
     * @BeforeScenario
     */
    public static function startScenario(BeforeScenarioScope $event)
    {
        ShortContext::$arrayWithSteps = array();
        $keyWord = $event->getScenario()->getKeyword();
        $scenarioTitle = $event->getScenario()->getTitle();
        $description = '';
        if (ShortContext::SCENARIO_OUTLINE_KEYWORD == $keyWord) {
            $scenarios = $event->getFeature()->getScenarios();
            $scenarioLine = 0;
            $scenarioIndex = 0;
            for ($i = 0; $i < sizeof($scenarios); $i ++) {
                if ($event->getScenario()->getLine() >= $scenarios[$i]->getLine()) {
                    $scenarioLine = $scenarios[$i]->getLine();
                    $scenarioIndex = $i;
                }
            }
            $scenario = $event->getFeature()->getScenarios()[$scenarioIndex];
            $scenarioName = $scenario->getKeyword() . ' : ' . $scenario->getTitle();
            $description = $keyWord . ' : ' . $scenarioTitle;
        } else {
            $scenarioName = $keyWord . ' : ' . $scenarioTitle;
            $description = '';
        }
        ShortContext::$httpService->createScenarioItem($scenarioName, $description);
    }

    /**
     * @BeforeStep
     */
    public static function startStep(BeforeStepScope $event)
    {
        $keyWord = $event->getStep()->getKeyword();
        $stepName = $event->getStep()->getText();
        ShortContext::$httpService->createStepItem($keyWord . ' : ' . $stepName);
    }

    /**
     * @AfterStep
     */
    public static function finishStep(AfterStepScope $event)
    {
        array_push(ShortContext::$arrayWithSteps, $event->getStep());
        $statusCode = $event->getTestResult()->getResultCode();
        ShortContext::$httpService->finishStepItem($statusCode, AssertService::getAssertMessage(), AssertService::getStackTraceMessage());
    }

    /**
     * @AfterScenario
     */
    public static function finishScenario(ScenarioScope $event)
    {
        $fullArrayWithStep = $event->getScenario()->getSteps();
        $diffArray = array_udiff($fullArrayWithStep, ShortContext::$arrayWithSteps, function ($obj_a, $obj_b) {
            return strcmp($obj_a->getText(), $obj_b->getText());
        });
        $lastFailedStep = '';
        if (count($diffArray) > 0) {
            $lastFailedStep = end(ShortContext::$arrayWithSteps)->getText();
        }
        foreach ($diffArray as $value) {
            $keyWord = $value->getKeyword();
            $stepName = $value->getText();
            ShortContext::$httpService->createStepItem($keyWord . ' : ' . $stepName);
            ShortContext::$httpService->finishStepItem(TestResults::SKIPPED, 'SKIPPED. Skipped due to failure of \'' . $lastFailedStep . '\'.', AssertService::getStackTraceMessage());
        }
        $statusCode = $event->getTestResult()->getResultCode();
        ShortContext::$httpService->finishScrenarioItem($statusCode);
    }

    /**
     * @AfterFeature
     */
    public static function finishFeature(AfterFeatureScope $event)
    {
        $featureDescription = $event->getFeature()->getDescription();
        $statusCode = $event->getTestResult()->getResultCode();
        ShortContext::$httpService->finishFeatureItem($statusCode, $featureDescription);
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
