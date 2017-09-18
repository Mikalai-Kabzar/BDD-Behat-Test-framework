<?php
namespace TestFramework\Services;

use Psr\Http\Message\ResponseInterface;

/**
 * Assert services for autotests
 */
class ReportPortalHTTPService
{

    private const formatDate = 'Y-m-d\TH:i:s';

    private static $UUID = 'fb0b93bb-0d0b-4b99-84be-9be85cc9dcb2';

    private static $baseURI = 'http://localhost:8080/api/';

    private static $projectName = 'default_personal';

    private static $launchID;

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
        $status = 'PASSED';
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

    public static function getValueFromJSON($request, ResponseInterface $response)
    {
        $array = json_decode($response->getBody()->getContents());
        return $array->{$request};
    }
}
