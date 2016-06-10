<?php

/**
 * Database settings.
 */
$databases = array();

/**
 * Location of the site configuration files.
 */
$config_directories[CONFIG_SYNC_DIRECTORY] = '../sync';

/**
 * The active installation profile.
 */
$settings['install_profile'] = 'standard';

/**
 * Salt for one-time login links, cancel links, form tokens, etc.
 */
// SET SALT.
$settings['hash_salt'] = 'asdfasdfae';

/**
 * Access control for update.php script.
 */
$settings['update_free_access'] = FALSE;

/**
 * Load common services definition file.
 */
$settings['container_yamls'][] = __DIR__ . '/services.yml';

/**
 * Set environment indicator.
 */
include __DIR__ . "/environment.inc";

/**
 * Include settings.
 */
include __DIR__ . "/$environment/settings.inc";
