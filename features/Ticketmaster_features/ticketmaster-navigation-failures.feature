Feature:Test basic navigation of 'Ticketmaster' website using tests with failures
In order to test 'Ticketmaster' website
As a auto-tester and user
I need to be able to navigate using basic nav-buttons using tests with failures

  Scenario Outline:Click on nav-buttons and wait for tab loading
    Given I am on Ticketmaster
    When I click on <navButton>
    Then the <pageLabel> tab is loaded

    Examples:
      | navButton | pageLabel |
      | "music"   | "music"   |
      | "sport"   | "family"  |
      | "arts"    | "arts"    |
      | "family"  | "sport"   |