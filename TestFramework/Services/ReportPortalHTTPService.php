<?php
namespace TestFramework\Services;

use Psr\Http\Message\ResponseInterface;
use Behat\Testwork\Tester\Result\TestResults;

/**
 * Assert services for autotests
 */
class ReportPortalHTTPService
{

    private const FEATURE_DESCRIPTION = 'Feature description';

    private const SCENARIO_DESCRIPTION = 'Scenario description';

    private const STEP_DESCRIPTION = 'Step description';

    private const formatDate = 'Y-m-d\TH:i:s';

    private static $UUID = '07fa681f-31ea-438d-8711-628c14020c5e';

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

    public static function launchTestRun($name)
    {
        $result = ReportPortalHTTPService::$client->post('v1/' . ReportPortalHTTPService::$projectName . '/launch', array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'bearer ' . ReportPortalHTTPService::$UUID
            ),
            'json' => array(
                'description' => "Test run, Creation of client for behat",
                'mode' => "DEFAULT",
                'name' => $name,
                'start_time' => date(ReportPortalHTTPService::formatDate),
                'tags' => array(
                    "@TestRun",
                    "@Second tag"
                )
            )
        ));
        ReportPortalHTTPService::$launchID = ReportPortalHTTPService::getValueFromJSON('id', $result);
        return $result;
    }

    public static function finishTestRun($statusCode)
    {
//         if ($statusCode) {
//             $status = 'PASSED';
//         } else {
//             $status = 'FAILED';
//         }
        $status = ReportPortalHTTPService::calculateStatus($statusCode);
        $result = ReportPortalHTTPService::$client->put('v1/' . ReportPortalHTTPService::$projectName . '/launch/' . ReportPortalHTTPService::$launchID . '/finish', array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'bearer ' . ReportPortalHTTPService::$UUID
            ),
            'json' => array(
                'end_time' => date(ReportPortalHTTPService::formatDate),
                'status' => $status
            )
        ));
        ReportPortalHTTPService::$launchID = '';
        return $result;
    }

    public static function createRootItem($name)
    {
        $result = ReportPortalHTTPService::$client->post('v1/' . ReportPortalHTTPService::$projectName . '/item', array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'bearer ' . ReportPortalHTTPService::$UUID
            ),
            'json' => array(
                'description' => "Test run description",
                'launch_id' => ReportPortalHTTPService::$launchID,
                'name' => $name,
                'start_time' => date(ReportPortalHTTPService::formatDate),
                "tags" => array(
                    "@Tag of root item"
                ),
                "type" => "SUITE",
                "uniqueId" => "string"
            )
        ));
        ReportPortalHTTPService::$rootItemID = ReportPortalHTTPService::getValueFromJSON('id', $result);
        return $result;
    }

    public static function finishRootItem($statusCode)
    {
        $result = ReportPortalHTTPService::finishItem(ReportPortalHTTPService::$rootItemID, TestResults::PASSED, "Test run description");
        ReportPortalHTTPService::$rootItemID = '';
        return $result;
    }

    public static function createFeatureItem($name)
    {
        $result = ReportPortalHTTPService::createChildItem(ReportPortalHTTPService::$rootItemID, ReportPortalHTTPService::FEATURE_DESCRIPTION, $name, 'SUITE');
        ReportPortalHTTPService::$featureItemID = ReportPortalHTTPService::getValueFromJSON('id', $result);
        return $result;
    }

    public static function createScenarioItem($name)
    {
        $result = ReportPortalHTTPService::createChildItem(ReportPortalHTTPService::$featureItemID, ReportPortalHTTPService::SCENARIO_DESCRIPTION, $name, 'TEST');
        ReportPortalHTTPService::$scenarioItemID = ReportPortalHTTPService::getValueFromJSON('id', $result);
        return $result;
    }

    public static function createStepItem($name)
    {
        $result = ReportPortalHTTPService::createChildItem(ReportPortalHTTPService::$scenarioItemID, ReportPortalHTTPService::STEP_DESCRIPTION, $name, 'STEP');
        ReportPortalHTTPService::$stepItemID = ReportPortalHTTPService::getValueFromJSON('id', $result);
        return $result;
    }

    public static function finishStepItem($statusCode, $trace)
    {
        $result = ReportPortalHTTPService::finishItem(ReportPortalHTTPService::$stepItemID, $statusCode, 'Description : ' . $statusCode . '. Stack trace: ' . $trace);
        ReportPortalHTTPService::$stepItemID = '';
        return $result;
    }

    public static function finishScrenarioItem($statusCode)
    {
        $result = ReportPortalHTTPService::finishItem(ReportPortalHTTPService::$scenarioItemID, $statusCode, 'Description : ' . $statusCode);
        ReportPortalHTTPService::$scenarioItemID = '';
        return $result;
    }

    public static function finishFeatureItem($statusCode)
    {
        $result = ReportPortalHTTPService::finishItem(ReportPortalHTTPService::$featureItemID, $statusCode, 'Description : ' . $statusCode);
        ReportPortalHTTPService::$featureItemID = '';
        return $result;
    }

    private static function getValueFromJSON($request, ResponseInterface $response)
    {
        $array = json_decode($response->getBody()->getContents());
        return $array->{$request};
    }

    private static function createChildItem($rootItemID, $description, $name, $type)
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
                'start_time' => date(ReportPortalHTTPService::formatDate),
                'tags' => array(
                    '@Tag of feature item'
                ),
                'type' => $type,
                'uniqueId' => 'string'
            )
        ));
        return $result;
    }

    private static function finishItem($itemID, $statusCode, $description)
    {
//         if ($statusCode == TestResults::PASSED) {
//             $status = 'PASSED';
//         } elseif ($statusCode == TestResults::FAILED) {
//             $status = 'FAILED';
//         } elseif ($statusCode == TestResults::SKIPPED) {
//             $status = 'SKIPPED';
//         } else {
//             print $statusCode;
//         }
        $status = ReportPortalHTTPService::calculateStatus($statusCode);
 
        $result = ReportPortalHTTPService::$client->put('v1/' . ReportPortalHTTPService::$projectName . '/item/' . $itemID, array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'bearer ' . ReportPortalHTTPService::$UUID
            ),
            'json' => array(
                'description' => $description,
                'end_time' => date(ReportPortalHTTPService::formatDate),
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
