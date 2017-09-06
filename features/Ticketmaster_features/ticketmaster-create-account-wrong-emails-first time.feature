Feature:Test account creation features with wrong e-mails (first opening of a page)
In order to test 'Ticketmaster' website
As a new user
I need to get error message caused by using not existed e-mail.

  Scenario:Try to create account error message caused by using not existed e-mail (first opening of a page).
    Given I am on Ticketmaster
    When I click on Create Account button 
    And I enter "password123456" password to Ticketmaster Password field on Sign In to My Account page
    And I enter "atata.email.without@lastPart" to My e-mail address is field.
    Then I dont get There are problems with your submission page
    When I click on Accept and Continue 
    Then I get There are problems with your submission page