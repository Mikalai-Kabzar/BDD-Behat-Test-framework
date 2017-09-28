Feature: Test basic navigation of 'Application' website 1232
  In order to test 'Application' website
  As a auto-tester and user
  I need to be able to navigate using basic nav-buttons

  Scenario: Calculate something 1
    Given I want to calculate some value
    When I calculate 5 and 1
    Then I get 6
    And I get 6
    And I get 6
    And I get 5
    And I get 2
    And I get 3

  Scenario: Calculate something 2
    Given I want to calculate some value
    When I calculate 15 and 1
    Then I get 6
    And I get 6

  Scenario: Calculate something 3
    Given I want to calculate some value
    When I calculate 15 and 1
    Then I get 6
    And I get 6


  Scenario Outline: Calculate something 4 outline
    Given I want to calculate some value
    When I calculate <value1> and <value2>
    Then I get <value3>

    Examples:
      | value1 | value2 | value3 |
      | 1      | 2      | 3      |

		

	