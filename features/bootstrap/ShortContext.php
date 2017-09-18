<?php

use TestFramework\Services\ReportPortalHTTPService;
/**
 * Defines Short context.
 */
class ShortContext extends BaseFeatureContext {

    /**
     *
     * @var ReportPortalHTTPService
     */
    private static $httpService = new ReportPortalHTTPService();
    
    public function method1() {
        $this->httpService = new ReportPortalHTTPService();
    }
    
    /**
     * @BeforeSuite
     */
    public static function startLaunch() {
        BaseFeatureContext::$httpService->printStr("before launch");
        //print "before launch";
        
    }
    
    /**
     * @AfterSuite
     */
    public static function finishLaunch() {
        BaseFeatureContext::$httpService->printStr("after launch");
        //print "after launch";
        
    }
}
