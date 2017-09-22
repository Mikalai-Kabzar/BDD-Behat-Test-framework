<?php
use TestFramework\Services\AssertService;

/**
 * Defines Short context.
 */
class ShortContext extends BaseFeatureContext
{

    /**
     * @Given I want to calculate some value
     */
    public function iWantToCalculateSomeValue()
    {
        //ReportPortalHTTPService::configureReportPortalHTTPService('config.yaml');
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
}
