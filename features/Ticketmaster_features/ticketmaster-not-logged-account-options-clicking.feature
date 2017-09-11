Feature: Test clicking on account dropdown menu without login
In order to test 'Ticketmaster' website
As a new user
I need to be able to click on 'My account' dropdown options and have redirect to 'Sigh in' page

  Scenario Outline: Expand 'My account' dropdown and click on options to be redirected on Sign in page
    Given I am on Ticketmaster
    When I hover on My account button
    And I wait '1' seconds
    And I click on <option> option of My Account dropdown 
    Then Sign in page is displayed
    And Sign in page contains correct Welcome message

    Examples:
      | option                     |
      | "Your Account"             |
      | "Edit Profile"             |
      | "Edit Email Preferences"   |
      | "Edit Billing Information" |
      | "Order History"            |
      | "Print My Tickets"         |

  Scenario: Click on 'Sign In' button and redirect to Sign In page.
    Given I am on Ticketmaster
    When I hover on My account button
    And I wait '1' seconds
    And I click on Sign In button
    Then Sign in page is displayed
    And Sign in page contains correct Welcome message    