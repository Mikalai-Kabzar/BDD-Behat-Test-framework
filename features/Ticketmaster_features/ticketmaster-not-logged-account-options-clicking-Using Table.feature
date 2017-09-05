Feature:Test clicking on account dropdown menu without login (using table)
In order to test 'Ticketmaster' website
As a new user
I need to be able to click on 'My account' dropdown options and have redirect to 'Sigh in' page

  Scenario:Expand 'My account' dropdown and click on options to be redirected on Sign in page (using table)
    Given I am on Ticketmaster
    When I hover on My account button
    And I wait '1' seconds
    And I click on option of My Account dropdown 
      | "Edit Billing Information" |
    Then Sign in page is displayed
    And Sign in page contains correct Welcome message   