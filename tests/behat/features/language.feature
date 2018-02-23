@api @d8
Feature: Language support
  In order to demonstrate the language integration
  As a developer for the Behat Extension
  I need to provide test cases for the language support

  # These test scenarios assume to have a clean installation of the "standard"
  # profile and that the "behat_test" module from the "fixtures/" folder is
  # enabled on the site.

  Scenario: Enable multiple languages
    Given the following languages are available:
      | languages |
      | en        |
      | fr        |
      | ca        |
      | de        |
      | es        |
      | it        |
      | pl        |
      | pt-br     |
      | cs        |
    When I visit "/user/login"
    Then I should see "Email address"
    Then I should see "Password"
    And I fill in the following:
      | Email address | admin |
      | Password | admin |
    And press "Sign in"
    When I go to "admin/config/regional/language"
    Then I should see "English"
    And I should see "Français"
    And I should see "Català"
    And I should see "Deutsch"
    And I should see "Español"
    And I should see "Italiano"
    And I should see "Português"
    And I should see "Česky"


