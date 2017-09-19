<?php
namespace TestFramework\Services;

use Psr\Http\Message\ResponseInterface;

/**
 * Assert services for autotests
 */
class ReportPortalHTTPService
{

    private const formatDate = 'Y-m-d\TH:i:s';

    private static $UUID = '07fa681f-31ea-438d-8711-628c14020c5e';

    private static $baseURI = 'http://localhost:8080/api/';

    private static $projectName = 'default_personal';

    private static $launchID;

    private static $rootItemID;

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

    public static function finishTestRun($status)
    {
        $result = ReportPortalHTTPService::$client->put('v1/' . ReportPortalHTTPService::$projectName . '/launch/' . ReportPortalHTTPService::$launchID . '/finish', array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'bearer ' . ReportPortalHTTPService::$UUID
            ),
            'json' => array(
                "end_time" => date(ReportPortalHTTPService::formatDate),
                "status" => $status
            )
        ));
        ReportPortalHTTPService::$launchID = '';
        return $result;
    }

    
    public static function createRootItem($name)
    {
        print ReportPortalHTTPService::$launchID;
        $result = ReportPortalHTTPService::$client->post('v1/' . ReportPortalHTTPService::$projectName . '/item', array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'bearer ' . ReportPortalHTTPService::$UUID
            ),      
            'json' => array(
                'description' => "Suite description",
                'launch_id' => ReportPortalHTTPService::$launchID,
                'name' => $name,
                'start_time' => date(ReportPortalHTTPService::formatDate),
                "tags"=> array(
                    "@Tag of root item"
                ),
                "type"=> "SUITE",
                "uniqueId"=> "string"
            )
        ));
        ReportPortalHTTPService::$rootItemID = ReportPortalHTTPService::getValueFromJSON('id', $result);
        return $result;
    }
    
    //http://localhost:8080/api/v1/${#TestCase#projectName}/item/${#TestCase#RootTestID}
    
    public static function finishRootItem()
    {
        $result = ReportPortalHTTPService::$client->put('v1/' . ReportPortalHTTPService::$projectName . '/item/'.ReportPortalHTTPService::$rootItemID, array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'bearer ' . ReportPortalHTTPService::$UUID
            ),
            'json' => array(
                'end_time' => date(ReportPortalHTTPService::formatDate),
                'status'=> 'PASSED'
            )
        ));

        
        ReportPortalHTTPService::$rootItemID = '';
        return $result;
    }
    
    public static function getValueFromJSON($request, ResponseInterface $response)
    {
        $array = json_decode($response->getBody()->getContents());
        return $array->{$request};
    }
}
