<?php
namespace TestFramework\Services;

use Behat\Testwork\Tester\Result\TestResults;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Assert services for autotests
 */
class ReportPortalHTTPService
{
    private const EMPTY_ID = 'empty id';
    
    private const DEFAULT_FEATURE_DESCRIPTION = '';

    private const DEFAULT_SCENARIO_DESCRIPTION = '';

    private const DEFAULT_STEP_DESCRIPTION = '';

    private const FORMAT_DATE = 'Y-m-d\TH:i:s';
    
    private const ZONE = '.000Z+3';

    private const BASE_URI_TEMPLATE = 'http://%s/api/';

    private static $UUID;

    private static $baseURI;

    private static $host;

    private static $projectName;

    private static $launchID = ReportPortalHTTPService::EMPTY_ID;

    private static $rootItemID = ReportPortalHTTPService::EMPTY_ID;

    private static $featureItemID = ReportPortalHTTPService::EMPTY_ID;

    private static $scenarioItemID = ReportPortalHTTPService::EMPTY_ID;

    private static $stepItemID = ReportPortalHTTPService::EMPTY_ID;
    
    /**
     *
     * @var \GuzzleHttp\Client
     */
    private static $client;
    
    function __construct()
    {
        ReportPortalHTTPService::$client = new \GuzzleHttp\Client([
            'base_uri' => ReportPortalHTTPService::$baseURI
        ]);
    }
    
    public static function isSuiteRunned() {
        return ReportPortalHTTPService::$rootItemID != ReportPortalHTTPService::EMPTY_ID;
    }
    
    public static function isStepRunned() {
        return ReportPortalHTTPService::$stepItemID != ReportPortalHTTPService::EMPTY_ID;
    }
    
    public static function isScenarioRunned() {
        return ReportPortalHTTPService::$scenarioItemID != ReportPortalHTTPService::EMPTY_ID;
    }
    
    public static function isFeatureRunned() {
        return ReportPortalHTTPService::$featureItemID != ReportPortalHTTPService::EMPTY_ID;
    }
    
    public static function configureReportPortalHTTPService(string $yamlFilePath)
    {
        $yamlArray = YAML::parse($yamlFilePath);
        ReportPortalHTTPService::$UUID = $yamlArray['UUID'];
        ReportPortalHTTPService::$host = $yamlArray['host'];
        ReportPortalHTTPService::$baseURI = sprintf(ReportPortalHTTPService::BASE_URI_TEMPLATE, ReportPortalHTTPService::$host);
        ReportPortalHTTPService::$projectName = $yamlArray['projectName'];
    }

    public static function launchTestRun($name, $description, $mode, array $tags)
    {
        $result = ReportPortalHTTPService::$client->post('v1/' . ReportPortalHTTPService::$projectName . '/launch', array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'bearer ' . ReportPortalHTTPService::$UUID
            ),
            'json' => array(
                'description' => $description,
                'mode' => $mode,
                'name' => $name,
                'start_time' => ReportPortalHTTPService::getTime(),
                'tags' => $tags
            )
        ));
        ReportPortalHTTPService::$launchID = ReportPortalHTTPService::getValueFromJSON('id', $result);
        return $result;
    }

    public static function finishTestRun($statusCode)
    {
        $status = ReportPortalHTTPService::calculateStatus($statusCode);
        $result = ReportPortalHTTPService::$client->put('v1/' . ReportPortalHTTPService::$projectName . '/launch/' . ReportPortalHTTPService::$launchID . '/finish', array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'bearer ' . ReportPortalHTTPService::$UUID
            ),
            'json' => array(
                'end_time' => ReportPortalHTTPService::getTime(),
                'status' => $status
            )
        ));       
        return $result;
    }

    public static function createRootItem($name, $description, array $tags)
    {
        $result = ReportPortalHTTPService::$client->post('v1/' . ReportPortalHTTPService::$projectName . '/item', array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'bearer ' . ReportPortalHTTPService::$UUID
            ),
            'json' => array(
                'description' => $description,
                'launch_id' => ReportPortalHTTPService::$launchID,
                'name' => $name,
                'start_time' => ReportPortalHTTPService::getTime(),
                "tags" => $tags,
                "type" => "SUITE",
                "uniqueId" => "string"
            )
        ));
        ReportPortalHTTPService::$rootItemID = ReportPortalHTTPService::getValueFromJSON('id', $result);
        return $result;
    }

    public static function finishRootItem($statusCode)
    {
        $result = ReportPortalHTTPService::finishItem(ReportPortalHTTPService::$rootItemID, TestResults::PASSED, '');
        ReportPortalHTTPService::$rootItemID = ReportPortalHTTPService::EMPTY_ID;
        return $result;
    }

    public static function createFeatureItem($name)
    {
        $result = ReportPortalHTTPService::createChildItem(ReportPortalHTTPService::$rootItemID, ReportPortalHTTPService::DEFAULT_FEATURE_DESCRIPTION, $name, 'SUITE', array());
        ReportPortalHTTPService::$featureItemID = ReportPortalHTTPService::getValueFromJSON('id', $result);
        return $result;
    }

    public static function createScenarioItem($name, $description)
    {
        $result = ReportPortalHTTPService::createChildItem(ReportPortalHTTPService::$featureItemID, $description, $name, 'TEST', array());
        ReportPortalHTTPService::$scenarioItemID = ReportPortalHTTPService::getValueFromJSON('id', $result);
        return $result;
    }

    public static function createStepItem($name)
    {
        $result = ReportPortalHTTPService::createChildItem(ReportPortalHTTPService::$scenarioItemID, ReportPortalHTTPService::DEFAULT_STEP_DESCRIPTION, $name, 'STEP', array());
        ReportPortalHTTPService::$stepItemID = ReportPortalHTTPService::getValueFromJSON('id', $result);
        return $result;
    }

    public static function finishStepItem($statusCode, $description, $stackTrace)
    {
        $actualDescription = '';
        if ($statusCode == TestResults::SKIPPED) {
            ReportPortalHTTPService::addLogMessage(ReportPortalHTTPService::$stepItemID, $description, 'info');
            $actualDescription = $description;
        }
        if ($statusCode == TestResults::FAILED) {
            ReportPortalHTTPService::addLogMessage(ReportPortalHTTPService::$stepItemID, $stackTrace, 'error');
            $actualDescription = $description;
        }
        
        $result = ReportPortalHTTPService::finishItem(ReportPortalHTTPService::$stepItemID, $statusCode, $actualDescription);
        ReportPortalHTTPService::$stepItemID = ReportPortalHTTPService::EMPTY_ID;
        return $result;
    }

    public static function finishScrenarioItem($statusCode)
    {
        $result = ReportPortalHTTPService::finishItem(ReportPortalHTTPService::$scenarioItemID, $statusCode, '');
        ReportPortalHTTPService::$scenarioItemID = ReportPortalHTTPService::EMPTY_ID;
        return $result;
    }

    public static function finishFeatureItem($statusCode, $description)
    {
        $result = ReportPortalHTTPService::finishItem(ReportPortalHTTPService::$featureItemID, $statusCode, $description);
        ReportPortalHTTPService::$featureItemID = ReportPortalHTTPService::EMPTY_ID;
        return $result;
    }

    private static function addLogMessage($item_id, $message, $logLevel)
    {
        $result = ReportPortalHTTPService::$client->post('v1/' . ReportPortalHTTPService::$projectName . '/log', array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'bearer ' . ReportPortalHTTPService::$UUID
            ),
            'json' => array(
                'item_id' => $item_id,
                'message' => $message,
                'time' => ReportPortalHTTPService::getTime(),
                'level' => $logLevel
            )
        ));
        return $result;
    }

    private static function getValueFromJSON($request, ResponseInterface $response)
    {
        $array = json_decode($response->getBody()->getContents());
        return $array->{$request};
    }

    private static function createChildItem($rootItemID, $description, $name, $type, array $tags)
    {
        $result = ReportPortalHTTPService::$client->post('v1/' . ReportPortalHTTPService::$projectName . '/item/' . $rootItemID, array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'bearer ' . ReportPortalHTTPService::$UUID
            ),
            'json' => array(
                'description' => $description,
                'launch_id' => ReportPortalHTTPService::$launchID,
                'name' => $name,
                'start_time' => ReportPortalHTTPService::getTime(),
                'tags' => $tags,
                'type' => $type,
                'uniqueId' => 'string'
            )
        ));
        return $result;
    }

    private static function finishItem($itemID, $statusCode, $description)
    {
        $status = ReportPortalHTTPService::calculateStatus($statusCode);
        $result = ReportPortalHTTPService::$client->put('v1/' . ReportPortalHTTPService::$projectName . '/item/' . $itemID, array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'bearer ' . ReportPortalHTTPService::$UUID
            ),
            'json' => array(
                'description' => $description,
                'end_time' => ReportPortalHTTPService::getTime(),
                'status' => $status
            )
        ));
        return $result;
    }

    private static function calculateStatus($statusCode)
    {
        if ($statusCode == TestResults::PASSED) {
            $status = 'PASSED';
        } elseif ($statusCode == TestResults::FAILED) {
            $status = 'FAILED';
        } elseif ($statusCode == TestResults::SKIPPED) {
            $status = 'SKIPPED';
        }
        return $status;
    }
    
    private static function getTime() {
        return date(ReportPortalHTTPService::FORMAT_DATE).ReportPortalHTTPService::ZONE;
    }
}
