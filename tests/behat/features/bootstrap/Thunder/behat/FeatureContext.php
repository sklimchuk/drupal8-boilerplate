<?php

namespace Thunder\behat;

use Behat\Behat\Context\SnippetAcceptingContext;
use Drupal\DrupalExtension\Context\RawDrupalContext;
use Drupal\node\Entity\Node;
use rdx\behatvars\BehatVariablesDatabase;

/**
 * Class FeatureContext.
 *
 * Defines application features from the specific context.
 */
class FeatureContext extends RawDrupalContext implements SnippetAcceptingContext {

  public $scenarioStart;


  /**
   * Initializes context.
   *
   * Every scenario gets its own context instance.
   * You can also pass arbitrary arguments to the
   * context constructor through behat.yml.
   */
  public function __construct() {
    $this->scenarioStart = time();
  }

  /**
   * Returns whether the scenario is running in a browser that can run
   * Javascript or not.
   *
   * @return boolean
   */
  protected function running_javascript() {
    return get_class($this->getSession()
        ->getDriver()) !== 'Behat\Mink\Driver\GoutteDriver';
  }

  /**
   * Set screen size before test.
   *
   * @BeforeScenario
   */
  public function beforeScenario() {
    if (!$this->running_javascript()) {
      return;
    }
    $this->getSession()->getDriver()->resizeWindow(1400, 2150);
  }

  /**
   * Setting custom size of the screen using width and height parameters
   *
   * @Given /^the custom size is "([^"]*)" by "([^"]*)"$/
   */
  public function theCustomSizeIs($width, $height)
  {
    $this->getSession()->resizeWindow($width, $height, 'current');
  }

  /**
   * Setting screen size to 1400x900 (desktop)
   *
   * @Given /^the size is desktop/
   */
  public function theSizeIsDesktop()
  {
    $this->getSession()->resizeWindow(1400, 900, 'current');
  }

  /**
   * Setting screen size to 1024x900 (tablet landscape)
   *
   * @Given /^the size is tablet landscape/
   */
  public function theSizeIsTabletLandscape()
  {
    $this->getSession()->resizeWindow(1024, 900, 'current');
  }

  /**
   * Setting screen size to 768x900 (tablet portrait)
   *
   * @Given /^the size is tablet portrait/
   */
  public function theSizeIsTabletPortrait()
  {
    $this->getSession()->resizeWindow(768, 900, 'current');
  }

  /**
   * Setting screen size to 640x900 (mobile landscape)
   *
   * @Given /^the size is mobile landscape/
   */
  public function theSizeIsMobileLandscape()
  {
    $this->getSession()->resizeWindow(640, 900, 'current');
  }

  /**
   * Setting screen size to 320x900 (mobile portrait)
   *
   * @Given /^the size is mobile portrait/
   */
  public function theSizeIsMobilePortrait()
  {
    $this->getSession()->resizeWindow(320, 900, 'current');
  }

  /**
   * Check if the port is 443(https) or 80(http) / secure or not.
   *
   * @Then /^the page is secure$/
   */
  public function thePageIsSecure()
  {
    $current_url = $this->getSession()->getCurrentUrl();
    if(strpos($current_url, 'https') === false) {
      throw new Exception('Page is not using SSL and is not Secure');
    }
  }

  /**
   * Run scheduler cron.
   *
   * @throws \Exception
   *
   * @Given I run scheduler cron
   */
  public function runSchedulerCron() {
    scheduler_cron();
  }

  /**
   * Wait for AJAX to finish, so that content on page is updated.
   *
   * @Given I wait for page to load content
   */
  public function iWaitForPageToUpdate() {
    $this->getSession()
      ->wait(5000, '(typeof(jQuery)=="undefined" || (0 === jQuery.active && 0 === jQuery(\':animated\').length))');
  }

  /**
   * Set a waiting time in seconds
   *
   * @Given /^I wait for "([^"]*)" seconds$/
   */
  public function iWaitForSeconds($arg1) {
    sleep($arg1);
  }

  /**
   * Upload file to drop zone of Entity selector.
   *
   * Mimic functionality by exposing file field and uploading over it.
   *
   * @param string $path
   *   File name used to be uploaded in Drop files field.
   *
   * @throws \Exception
   *   If file field is not found for drop down.
   *
   * @When I drop the file :path in drop zone and select it
   */
  public function dropFileInSelectEntities($path) {

    // Select entity browser iframe.
    $iframe = $this->getSession()
      ->getPage()
      ->find('css', 'iframe.entity-browser-modal-iframe');
    if (empty($iframe)) {
      throw new \Exception(
        sprintf(
          'Unable to find entity browser iframe on page %s',
          $this->getSession()->getCurrentUrl()
        )
      );
    }
    $iframeName = $iframe->getAttribute('name');

    // Go into iframe scope from Entity Browsers.
    $this->getSession()->switchToIFrame($iframeName);

    // Wait that iframe is loaded and jQuery is available.
    $this->getSession()->wait(10000, '(typeof jQuery !== "undefined")');

    // Click all tabs until we find upload Tab.
    $tabLinks = $this->getSession()->getPage()->findAll('css', '.eb-tabs a');
    if (empty($tabLinks)) {
      throw new \Exception(
        sprintf(
          'Unable to find tabs in entity browser iframe on page %s',
          $this->getSession()->getCurrentUrl()
        )
      );
    }

    // Click all tabs until input file field for upload is found.
    $fileFieldSelector = "input[type='file'].dz-hidden-input";
    foreach ($tabLinks as $tabLink) {
      /* @var \Behat\Mink\Element\NodeElement $tabLink */
      $tabLink->click();

      $fileField = $this->getSession()
        ->getPage()
        ->find('css', $fileFieldSelector);

      if (!empty($fileField)) {
        break;
      }
    }

    if (empty($fileField)) {
      throw new \Exception(
        sprintf(
          'The drop-down file field was not found on the page %s',
          $this->getSession()->getCurrentUrl()
        )
      );
    }

    // Make file field visible and isolate possible problems with "multiple".
    $this->getSession()
      ->executeScript('jQuery("' . $fileFieldSelector . '").show(0).css("visibility","visible").width(200).height(30).removeAttr("multiple");');

    // Generate full path to file.
    /*if ($this->getMinkParameter('files_path')) {
    $fullPath = rtrim(realpath($this->getMinkParameter('files_path')),
    DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $path;
    if (is_file($fullPath)) {
    $path = $fullPath;
    }
    }*/

    $fileField->attachFile($path);
    // Wait for file to upload and use press Select button.
    $this->iWaitForPageToUpdate();

    // Wait up to 10 sec that "Submit" button is active.
    $this->getSession()->wait(
      10000,
      '(typeof jQuery === "undefined" || !jQuery(\'input[name="op"]\').is(":disabled"))'
    );

    // Go back to Page scope.
    $this->getSession()->switchToWindow();

    // Click Select button - inside iframe.
    $this->getSession()
      ->executeScript('document.querySelector(\'iframe[name="' . $iframeName . '"]\').contentWindow.jQuery(\'input[name="op"]\').click();');

    // Wait up to 10 sec that main page is loaded with new selected images.
    $this->getSession()->wait(
      10000,
      '(typeof jQuery === "undefined" || jQuery(\'.button.js-form-submit.form-submit[value="Remove"]\').length > 0)'
    );
  }

  /**
   * @Given I give focus to the css selector :selector
   */
  public function iGiveFocusToTheCssSelector($css_selector) {
    $element = $this->getSession()->getPage()->find("css", $css_selector);
    if ($css_selector === NULL) {
      throw new \Exception(sprintf('Could not evaluate CSS Selector: "%s"', $element, $this->getSession()
        ->getCurrentUrl()));
    }

    $element->click();
  }

  /**
   * Entity browser.
   *
   * @When I click drupal-data-selector :drupal_data_selector
   */
  public function entityBrowserClick($selector) {
    $session = $this->getSession()->getPage();
    $session->find('css', "input[data-drupal-selector='$selector']")->click();
  }

  /**
   * @Given I click the :arg1 element
   */
  public function iClickTheElement($selector) {
    $page = $this->getSession()->getPage();
    $element = $page->find('css', $selector);

    if (empty($element)) {
      throw new \Exception("No html element found for the selector ('$selector')");
    }

    $element->click();
  }

  /**
   * @Given I click the :arg1 element :arg2 times
   */
  public function iClickTheElementSeveralTimes($selector, $clicks_number) {
    if (!is_numeric($clicks_number)) {
      throw new \Exception("Need to use numbers (int) for clicks number. Now provided '$clicks_number'");
    }
    $page = $this->getSession()->getPage();
    $element = $page->find('css', $selector);

    if (empty($element)) {
      throw new \Exception("No html element found for the selector ('$selector')");
    }

    for ($i = 0; $i < (int) $clicks_number; $i++) {
      $element->click();
    }
  }

  /**
   * Y would be the way to up and down the page. A good default for X is 0
   *
   * @Given /^I scroll to x "([^"]*)" y "([^"]*)" coordinates of page$/
   */
  public function iScrollToXYCoordinatesOfPage($arg1, $arg2) {
    $function = "(function(){
              window.scrollTo($arg1, $arg2);
            })()";
    try {
      $this->getSession()->executeScript($function);
    }
    catch(Exception $e) {
      throw new \Exception("ScrollIntoView failed");
    }
  }

  /**
   * Scrolling to the particular element(arg1 - Nav menu selector, arg2 - element's selector to scroll to)
   *
   * @Given /^I scroll to element "([^"]*)" "([^"]*)"$/
   */
  public function iScrollToElement($arg1, $arg2) {
    $function = <<<JS
     var headerHeight = jQuery('$arg2').outerHeight(true),
          scrollBlock = jQuery('$arg1').offset().top;
 jQuery('body, html').scrollTo(scrollBlock - headerHeight);

JS;
    try {
      $this->getSession()->executeScript($function);
    }
    catch(Exception $e) {
      throw new \Exception("ScrollIntoElement failed");
    }
  }

  /**
   * Check if field contains value or not.
   *
   * @Then field :arg1 should be filled
   */
  public function checkFieldIsFilled($field) {
    $value = $this->getSession()->getPage()->findField($field)->getValue();
    if (empty($value)) {
      throw new \Exception($field . ' field has no value');
    }
  }


  /**
   * @Then I save current timestamp email into :variable
   */
  public function iSaveCurrentTimestampEmail($variable) {
    $timestamp = time();
    $email = 'JackWhite' . $timestamp . '@yopmail.com';
    BehatVariablesDatabase::set($variable, (string) $email);
  }

  /**
  * =================BB HOTELS functional logic===========
  */

  /**
   * @Then I should see :text in :css_selector
   */
  public function iShouldSeeIn($text, $css_selector) {
    $fields = $this->getSession()
      ->getPage()
      ->findAll('css', $css_selector);
    foreach ($fields as $field) {
      if ($field->getText() === $text) {
        return TRUE;
      }
    }

    // Finally throw exception if text was not found.
    throw new \Exception("The text `$text` is not found");
  }

  /**
   * Fills in a form field identified by css_selector with value.
   *
   * @Given I fill in :css_selector field with :value
   *
   * @throws Exception
   *
   * @see NodeElement::setValue
   */
  public function iFillinFieldByCSS($css_selector, $value) {

    $el = $this->getSession()->getPage()->find('css', $css_selector);
    if($el) {
      $el->setValue($value);
    } else {
      throw new \Exception('Element not found');
    }

  }

}
