<?php
namespace BehatReportPortal;

use Psr\Http\Message\ResponseInterface;
use ReportPortal\ItemStatusesEnum;
use ReportPortal\ReportPortalHTTPService;
use ReportPortal\ItemTypesEnum;

/**
 * Report portal HTTP/BDD service.
 * Provides basic methods to collaborate with Report portal with BDD framework.
 */
class ReportPortalHTTP_BDDService extends ReportPortalHTTPService
{

    /**
     * Create feature item
     *
     * @param string $name
     *            - feature name
     * @return ResponseInterface - result of request
     */
    public static function createFeatureItem(string $name)
    {
        $result = ReportPortalHTTPService::startChildItem(ReportPortalHTTPService::$rootItemID, ReportPortalHTTPService::DEFAULT_FEATURE_DESCRIPTION, $name, ItemTypesEnum::SUITE, array());
        ReportPortalHTTPService::$featureItemID = ReportPortalHTTPService::getValueFromResponse('id', $result);
        return $result;
    }

    /**
     * Create scenario item
     *
     * @param string $name
     *            - scenario name
     * @param string $description
     *            - sceanrio description
     * @return ResponseInterface - result of request
     */
    public static function createScenarioItem(string $name, string $description)
    {
        $result = ReportPortalHTTPService::startChildItem(ReportPortalHTTPService::$featureItemID, $description, $name, ItemTypesEnum::TEST, array());
        ReportPortalHTTPService::$scenarioItemID = ReportPortalHTTPService::getValueFromResponse('id', $result);
        return $result;
    }

    /**
     * Create step item
     *
     * @param string $name
     *            - step name
     * @return ResponseInterface - result of request
     */
    public static function createStepItem(string $name)
    {
        $result = ReportPortalHTTPService::startChildItem(ReportPortalHTTPService::$scenarioItemID, ReportPortalHTTPService::DEFAULT_STEP_DESCRIPTION, $name, ItemTypesEnum::STEP, array());
        ReportPortalHTTPService::$stepItemID = ReportPortalHTTPService::getValueFromResponse('id', $result);
        return $result;
    }

    /**
     * Finish step item
     *
     * @param string $itemStatus
     *            - step item status
     * @param string $description
     *            - step description
     * @param string $stackTrace
     *            - stack trace
     * @return ResponseInterface - result of request
     */
    public static function finishStepItem(string $itemStatus, string $description, string $stackTrace)
    {
        $actualDescription = '';
        if ($itemStatus == ItemStatusesEnum::SKIPPED) {
            ReportPortalHTTPService::addLogMessage(ReportPortalHTTPService::$stepItemID, $description, 'info');
            $actualDescription = $description;
        }
        if ($itemStatus == ItemStatusesEnum::FAILED) {
            ReportPortalHTTPService::addLogMessage(ReportPortalHTTPService::$stepItemID, $stackTrace, 'error');
            $actualDescription = $description;
        }
        $result = ReportPortalHTTPService::finishItem(ReportPortalHTTPService::$stepItemID, $itemStatus, $actualDescription);
        ReportPortalHTTPService::$stepItemID = ReportPortalHTTPService::EMPTY_ID;
        return $result;
    }

    /**
     * Finish scenario item
     *
     * @param string $scenarioStatus
     *            - scenario status
     * @return ResponseInterface - result of request
     */
    public static function finishScrenarioItem(string $scenarioStatus)
    {
        $result = ReportPortalHTTPService::finishItem(ReportPortalHTTPService::$scenarioItemID, $scenarioStatus, '');
        ReportPortalHTTPService::$scenarioItemID = ReportPortalHTTPService::EMPTY_ID;
        return $result;
    }

    /**
     * Finish feature item
     *
     * @param string $testStatus
     *            - feature status
     * @param string $description
     *            - feature item description
     * @return ResponseInterface - result of request
     */
    public static function finishFeatureItem(string $testStatus, string $description)
    {
        $result = ReportPortalHTTPService::finishItem(ReportPortalHTTPService::$featureItemID, $testStatus, $description);
        ReportPortalHTTPService::$featureItemID = ReportPortalHTTPService::EMPTY_ID;
        return $result;
    }
}