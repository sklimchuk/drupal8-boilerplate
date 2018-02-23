#!/bin/sh
## Set global environment variables
# - export THUNDER_DIST_DIR=`echo $(pwd)`
# - export TEST_DIR=`echo ${THUNDER_DIST_DIR}"/../test-dir"`
## Install Test requirements and set global environment variables
# - composer global require "behat/behat:~3.0" "drupal/drupal-extension:^3.2" "devinci/devinci-behat-extension:dev-master"
# - BEHAT_PARAMS='{"extensions":{"Drupal\\DrupalExtension":{"drupal":{"drupal_root":"TEST_DIR_MACRO/docroot"}},"Behat\\MinkExtension":{"base_url":"http://localhost:8080"}}}'
# - BEHAT_PARAMS=`echo $BEHAT_PARAMS | sed -e s#TEST_DIR_MACRO#$TEST_DIR#g`
# - export BEHAT_PARAMS

BASEDIR=$(dirname "$0")
CURRENT_TESTS_DIR=`echo $(pwd)`
IDRUPAL_DOCROOT_DIR=`echo ${CURRENT_TESTS_DIR}"/"${BASEDIR}"/../../docroot"`
echo "INFO: Web Docroot is set to $IDRUPAL_DOCROOT_DIR"

BEHAT_PARAMS='{"extensions":{"Drupal\\\DrupalExtension":{"drupal":{"drupal_root":"/data/www/logements/docroot"}},"Behat\\\MinkExtension":{"base_url":"http://logements.local"}}}'
# export BEHAT_PARAMS
BEHAT_PARAMS='{"extensions":{"Drupal\\\DrupalExtension":{"drupal":{"drupal_root":"TEST_DIR_MACRO"}},"Behat\\\MinkExtension":{"base_url":"https://localhost:8888"}}}'
BEHAT_PARAMS=`echo $BEHAT_PARAMS | sed -e s#TEST_DIR_MACRO#$IDRUPAL_DOCROOT_DIR#g`
export BEHAT_PARAMS

echo "INFO: BEHAT_PARAMS are set to $BEHAT_PARAMS"
# start the built-in php web server (mysql is already started)
#php -S localhost:8888 -t $IDRUPAL_DOCROOT_DIR > /dev/null &
cd $IDRUPAL_DOCROOT_DIR
php -S localhost:8888 ../vendor/drush/drush/misc/d8-rs-router.php &> /dev/null &

#Start phantomjs driver
phantomjs --webdriver=4444 > /dev/null &

#Execute tests.
cd $IDRUPAL_DOCROOT_DIR/../tests/behat
../../bin/behat --verbose
