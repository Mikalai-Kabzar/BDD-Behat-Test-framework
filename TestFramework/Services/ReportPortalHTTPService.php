<?php
namespace TestFramework\Services;

use Psr\Http\Message\ResponseInterface;
use Behat\Testwork\Tester\Result\TestResults;

/**
 * Assert services for autotests
 */
class ReportPortalHTTPService
{

    private const DEFAULT_FEATURE_DESCRIPTION = '';

    private const DEFAULT_SCENARIO_DESCRIPTION = '';

    private const DEFAULT_STEP_DESCRIPTION = '';

    private const FORMAT_DATE = 'Y-m-d\TH:i:s';

    private static $UUID = '07dd1f6d-d2d5-474f-90f8-fc14a59b49ad';

    private static $baseURI = 'http://localhost:8080/api/';

    private static $projectName = 'default_personal';

    private static $launchID;

    private static $rootItemID;

    private static $featureItemID;

    private static $scenarioItemID;

    private static $stepItemID;

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
                'start_time' => date(ReportPortalHTTPService::FORMAT_DATE),
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
                'end_time' => date(ReportPortalHTTPService::FORMAT_DATE),
                'status' => $status
            )
        ));
        ReportPortalHTTPService::$launchID = '';
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
                'start_time' => date(ReportPortalHTTPService::FORMAT_DATE),
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
        ReportPortalHTTPService::$rootItemID = '';
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
        ReportPortalHTTPService::$stepItemID = '';
        return $result;
    }

    public static function finishScrenarioItem($statusCode)
    {
        $result = ReportPortalHTTPService::finishItem(ReportPortalHTTPService::$scenarioItemID, $statusCode, '');
        ReportPortalHTTPService::$scenarioItemID = '';
        return $result;
    }

    public static function finishFeatureItem($statusCode, $description)
    {
        $result = ReportPortalHTTPService::finishItem(ReportPortalHTTPService::$featureItemID, $statusCode, $description);
        ReportPortalHTTPService::$featureItemID = '';
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
                'time' => date(ReportPortalHTTPService::FORMAT_DATE),
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
                'start_time' => date(ReportPortalHTTPService::FORMAT_DATE),
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
                'end_time' => date(ReportPortalHTTPService::FORMAT_DATE),
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
}
