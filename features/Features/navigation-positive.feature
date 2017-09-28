Feature: Test basic navigation of 'Ticketmaster' website
In order to test 'Ticketmaster' website
As a auto-tester and user
I need to be able to navigate using basic nav-buttons

  Scenario Outline: Click on nav-buttons and wait for tab loading
    Given I am on Application
    When I click on <navButton>
    Then the <pageLabel> tab is loaded

    Examples:
      | navButton | pageLabel |
      | "music"   | "music"   |
      | "sport"   | "sport"   |
      | "arts"    | "arts"    |
      | "family"  | "family"  |

  Scenario: Click on nav-buttons and wait for nav-button status changing.
    Given I am on Application
    When I click on "music"
    Then the "music" button is clicked
    When I click on "sport"
    Then the "sport" button is clicked
    When I click on "arts"
    Then the "arts" button is clicked
    When I click on "family"
    Then the "family" button is clicked