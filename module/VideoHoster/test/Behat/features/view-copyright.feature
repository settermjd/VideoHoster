Feature: view copyright
  In order to view the copyright notice for the site
  As a normal user
  I need to be able to open the copyright page
Scenario: view the copyright page
  Given I am on any page in the site
  And I go to the copyright route
  Then I should see the copyright notice