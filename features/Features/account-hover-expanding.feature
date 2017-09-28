Feature: Test account features (access to blocks)
In order to test 'Ticketmaster' website
As a new user
I need to be able to have access to account pages

  Scenario: Hover "My account" button and check this one is opened.
    Given I am on Application

    When I hover music nav-button 
    And I wait '1' seconds
    Then My account dropdown is not opened

    When I hover on My account button
    And I wait '1' seconds
    Then My account dropdown is opened

    When I hover music nav-button 
    And I wait '1' seconds
    Then My account dropdown is not opened 
    
   Scenario: Hover "My account" button and check this one is opened1.
    Given I am on Application

    When I hover music nav-button 
    And I wait '1' seconds
    Then My account dropdown is not opened
    
       Scenario: Hover "My account" button and check this one is opened2.
    Given I am on Application
