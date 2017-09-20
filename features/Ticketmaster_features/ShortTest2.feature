Feature: Test basic navigation of 'Ticketmaster' website 1232 
	In order to test 'Ticketmaster' website
As a auto-tester and user
I need to be able to navigate using basic nav-buttons

Background: 
	Given I want to calculate some value 
	
Scenario Outline: Calculate something 45 
	When I calculate <value1> and <value2> 
	Then I get <value3> 
	
	Examples: 
		| value1  | value2 | value3 |
		| 1       | 2      | 3      |
		| 1       | 3      | 3      |
		| 1       | 4      | 3      |
		| 1       | 5      | 6      |
		
Scenario Outline: Calculate something 4 
	When I calculate <value1> and <value2> 
	Then I get <value3> 
	
	Examples: 
		| value1  | value2 | value3 |
		| 1       | 2      | 3      |
		| 1       | 3      | 3      |
		| 1       | 4      | 3      |
		| 1       | 5      | 6      |				
		
Scenario: Calculate something 2 
	When I calculate 15 and 1 
	Then I get 6 
	And I get 5	