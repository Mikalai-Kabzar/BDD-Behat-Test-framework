<?php
namespace TestFramework\Services;

use Behat\Behat\Hook\Scope\AfterFeatureScope;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Hook\Scope\BeforeFeatureScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Hook\Scope\BeforeStepScope;
use Behat\Testwork\Hook\Scope\AfterSuiteScope;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use Behat\Testwork\Tester\Result\TestResults;

class ReportPortalHelper
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

    public static function startLaunch(BeforeSuiteScope $event)
    {
        $suiteName = $event->getSuite()->getName();
        ReportPortalHelper::$httpService = new ReportPortalHTTPService();
        ReportPortalHelper::$httpService->launchTestRun('Test Run - ' . $suiteName, '', ReportPortalHelper::launchMode, array());
        ReportPortalHelper::$httpService->createRootItem($suiteName, '', array());
    }

    public static function startFeature(BeforeFeatureScope $event)
    {
        $featureName = $event->getFeature()->getTitle();
        $keyWord = $event->getFeature()->getKeyword();
        ReportPortalHelper::$httpService->createFeatureItem($keyWord . ' : ' . $featureName, '');
    }

    public static function startScenario(BeforeScenarioScope $event)
    {
        ReportPortalHelper::$arrayWithSteps = array();
        $keyWord = $event->getScenario()->getKeyword();
        $scenarioTitle = $event->getScenario()->getTitle();
        $description = '';
        if (ReportPortalHelper::SCENARIO_OUTLINE_KEYWORD == $keyWord) {
            $scenarios = $event->getFeature()->getScenarios();
            $scenarioLine = $event->getScenario()->getLine();
            $scenarioIndex = 0;
            for ($i = 0; $i < sizeof($scenarios); $i ++) {
                if ($scenarioLine >= $scenarios[$i]->getLine()) {
                    // $scenarioLine = $scenarios[$i]->getLine();
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
        ReportPortalHelper::$httpService->createScenarioItem($scenarioName, $description);
    }

    public static function startStep(BeforeStepScope $event)
    {
        $keyWord = $event->getStep()->getKeyword();
        $stepName = $event->getStep()->getText();
        ReportPortalHelper::$httpService->createStepItem($keyWord . ' : ' . $stepName);
    }

    public static function finishStep(AfterStepScope $event)
    {
        array_push(ReportPortalHelper::$arrayWithSteps, $event->getStep());
        $statusCode = $event->getTestResult()->getResultCode();
        ReportPortalHelper::$httpService->finishStepItem($statusCode, AssertService::getAssertMessage(), AssertService::getStackTraceMessage());
    }

    public static function finishScenario(AfterScenarioScope $event)
    {
        $fullArrayWithStep = $event->getScenario()->getSteps();
        $diffArray = array_udiff($fullArrayWithStep, ReportPortalHelper::$arrayWithSteps, function ($obj_a, $obj_b) {
            return strcmp($obj_a->getText(), $obj_b->getText());
        });
        $lastFailedStep = '';
        if (count($diffArray) > 0) {
            $lastFailedStep = end(ReportPortalHelper::$arrayWithSteps)->getText();
        }
        foreach ($diffArray as $value) {
            $keyWord = $value->getKeyword();
            $stepName = $value->getText();
            ReportPortalHelper::$httpService->createStepItem($keyWord . ' : ' . $stepName);
            ReportPortalHelper::$httpService->finishStepItem(TestResults::SKIPPED, 'SKIPPED. Skipped due to failure of \'' . $lastFailedStep . '\'.', AssertService::getStackTraceMessage());
        }
        $statusCode = $event->getTestResult()->getResultCode();
        ReportPortalHelper::$httpService->finishScrenarioItem($statusCode);
    }

    public static function finishFeature(AfterFeatureScope $event)
    {
        $featureDescription = $event->getFeature()->getDescription();
        $statusCode = $event->getTestResult()->getResultCode();
        ReportPortalHelper::$httpService->finishFeatureItem($statusCode, $featureDescription);
    }

    public static function finishLaunch(AfterSuiteScope $event)
    {
        $statusCode = $event->getTestResult()->getResultCode();
        ReportPortalHelper::$httpService->finishRootItem($statusCode);
        ReportPortalHelper::$httpService->finishTestRun($statusCode);
    }
}

