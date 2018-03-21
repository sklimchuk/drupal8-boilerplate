<?php

/**
 * @file
 * Drupal site-specific configuration file.
 */

/**
 * Access control for update.php script.
 */
$settings['update_free_access'] = FALSE;
$settings['maintenance_theme'] = 'bartik';

/**
 * Fast 404 pages.
 */
$config['system.performance']['fast_404']['exclude_paths'] = '/\/(?:styles)|(?:system\/files)\//';
$config['system.performance']['fast_404']['paths'] = '/\.(?:txt|png|gif|jpe?g|css|js|ico|swf|flv|cgi|bat|pl|dll|exe|asp)$/i';
$config['system.performance']['fast_404']['html'] = '<!DOCTYPE html><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL "@path" was not found on this server.</p></body></html>';

/**
 * Load services definition file.
 */
$settings['container_yamls'][] = __DIR__ . '/services.yml';

/**
 * Customized settings per environment.
 */
/**
 * Trusted host configuration.
 */
$settings['trusted_host_patterns'] = [
  '^.+\.m1$',
  '^.+\.local$',
  '^localhost$'
];

/**
 * Database settings.
 */
$databases = array();
$databases['default']['default'] = array(
    // See .env file for the DB name.
    'database' => '[project_name]_m1',
    'username' => 'drupal',
    'password' => 'drupal',
    'host' => 'localhost',
    'port' => '3306',
    'driver' => 'mysql',
    'prefix' => '',
  );

// Docker Overrides.
$settings['redis.connection']['host'] = 'redis';
$settings['redis.connection']['port'] = '6379';
$settings['redis.connection']['password'] = '';
$settings['redis.connection']['base'] = 0;
$settings['redis.connection']['interface'] = 'PhpRedis';
$settings['cache']['default'] = 'cache.backend.redis';
$settings['cache']['bins']['bootstrap'] = 'cache.backend.chainedfast';
$settings['cache']['bins']['discovery'] = 'cache.backend.chainedfast';
$settings['cache']['bins']['config'] = 'cache.backend.chainedfast';

/**
 * Load local development override configuration, if available.
 *
 * Use settings.local.php to override variables on secondary (staging,
 * development, etc) installations of this site. Typically used to disable
 * caching, JavaScript/CSS compression, re-routing of outgoing emails, and
 * other things that should not happen on development and testing sites.
 *
 * Keep this code block at the end of this file to take full effect.
 */
if (file_exists(__DIR__ . '/settings.local.php')) {
  include __DIR__ . '/settings.local.php';
}
