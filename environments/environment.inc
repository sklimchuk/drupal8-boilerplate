<?php

/**
 * Set environment indicator.
 */
if (isset($_SERVER['ENVIRONMENT_INDICATOR'])) {
  $environment = $_SERVER['ENVIRONMENT_INDICATOR'];
}
else {
  switch (gethostname()) {
    // This is required for drush to work correctly.
    // Specify correct hosts.
    case 'web-01':
    case 'web-02':
      $environment = 'prod';
      break;
    case 'web-01-pp':
      $environment = 'preprod';
      break;
    case 'dev.adyax.net':
      $environment = 'dev';
      break;
    default:
      $environment = 'local';
      break;
  }
}
