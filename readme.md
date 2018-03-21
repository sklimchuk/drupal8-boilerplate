# Drupal Boilerplate

This project template provides a kickstart for managing your site
dependencies with [Composer](https://getcomposer.org/).

Drupal boilerplate is not a module. Instead it just serves as a directory structure for
starting a new drupal site. The idea behind Drupal boilerplate came from working on so many
different sites which each follow their own development practice, directory structure,
deployment guidelines, etc...

Drupal boilerplate tries to simplify starting a new site by having the most common
directory structures and files already included and set up. It contains initial structure and useful features for development Drupal project:
* config and install script to setup tools for static code analys locally
on developers machines (per project),
* pre-commit hook to analys files affected in the commits
(only custom code is taken in account),
* settings per environment,
* useful utils for writing casperjs autotests.

## Getting started 
You can start by [downloading](https://code.adyax.com/common/drupal8-boilerplate/repository/archive.zip?ref=composer-based)
this project. Once you download it you will find that every folder contains a readme.md file.
This readme.md file has been extensively documented to explain what belongs
in that specific directory.

Here's a breakdown for what each directory/file is used for. If you want to know more please
read the readme inside the specific directory.

* docroot -  Where your drupal root should start.
* results - This directory is just used to export test results to. A good example of this
   is when running drush test-run with the --xml option. You can export the xml
   to this directory for parsing by external tools.
* scripts -  A directory for other non-drupal scripts (post-installation, etc).
* test -  A directory for external tests. This is great for non drupal specific tests
 such as selenium, qunit, casperjs.
* sync - Configuration directory
* patches - Directory for patches
* enviroments/* - Per environment configurations. @see settings.php, services.yml, etc

## Docker integration

The docker integration is based on https://github.com/wodby/docker4drupal.
Please see original documentation if you have any questions https://wodby.com/stacks/drupal/docs/local/ .

Steps todo during project initiation.
* Rename the file `.env.default` to `.env`
* Update configuration in the file `.env` ( project_name, versions of images, etc)
* Update `environments/docker/settings.php` with correct DB name, add additional local configuration if required.
* Use `make up`, `make down` to start and stop Docker services (see docker.mk for details)

## Usage

First you need to [install composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).

> Note: The instructions below refer to the [global composer installation](https://getcomposer.org/doc/00-intro.md#globally).
You might need to replace `composer` with `php composer.phar` (or similar) for your setup.

## Installation instructions.
1. Lead Developer clone the repo into new Project repo.
2. Dev clones the new Project repository and runs `composer install` in 
the repo’s root directory.

## Enable pre-commit hooks
1. Run "composer install" from the repository root


## Composer Knowledge Base

If you want to know how to use it as replacement for
[Drush Make](https://github.com/drush-ops/drush/blob/master/docs/make.md) visit
the [Documentation on drupal.org](https://www.drupal.org/node/2471553).


With `composer require ...` you can download new dependencies to your installation.

```
cd some-dir
composer require drupal/devel:8.*
```

## What does the template do?

When installing the given `composer.json` some tasks are taken care of:

* Drupal will be installed in the `docroot`-directory.
* Autoloader is implemented to use the generated composer autoloader in `vendor/autoload.php`,
  instead of the one provided by Drupal (`docroot/vendor/autoload.php`).
* Modules (packages of type `drupal-module`) will be placed in `docroot/modules/contrib/`
* Theme (packages of type `drupal-theme`) will be placed in `docroot/themes/contrib/`
* Profiles (packages of type `drupal-profile`) will be placed in `docroot/profiles/contrib/`
* Creates default writable versions of `settings.php` and `services.yml`.
* Creates `sites/default/files`-directory.
* Latest version of drush is installed locally for use at `vendor/bin/drush`.
* Latest version of DrupalConsole is installed locally for use at `vendor/bin/drupal`.

## Updating Drupal Core

Updating Drupal core is a two-step process.

1. Update the version number of `drupal/core` in `composer.json`.
1. Run `composer update drupal/core`.
1. Run `./scripts/drupal/update-scaffold [drush-version-spec]` to update files
   in the `docroot` directory, where `drush-version-spec` is an optional identifier
   acceptable to Drush, e.g. `drupal-8.0.x` or `drupal-8.1.x`, corresponding to
   the version you specified in `composer.json`. (Defaults to `drupal-8`, the
   latest stable release.) Review the files for any changes and restore any
   customizations to `.htaccess` or `robots.txt`.
1. Commit everything all together in a single commit, so `docroot` will remain in
   sync with the `core` when checking out branches or running `git bisect`.

## Generate composer.json from existing project

With using [the "Composer Generate" drush extension](https://www.drupal.org/project/composer_generate)
you can now generate a basic `composer.json` file from an existing project. Note
that the generated `composer.json` might differ from this project's file.


## FAQ

### Should I commit the contrib modules I download

Composer recommends **no**. They provide [argumentation against but also workrounds if a project decides to do it anyway](https://getcomposer.org/doc/faqs/should-i-commit-the-dependencies-in-my-vendor-directory.md).

### How can I apply patches to downloaded modules?

If you need to apply patches (depending on the project being modified, a pull request is often a better solution), you can do so with the [composer-patches](https://github.com/cweagans/composer-patches) plugin.

To add a patch to drupal module foobar insert the patches section in the extra section of composer.json:
```json
"extra": {
    "patches": {
        "drupal/foobar": {
            "Patch description": "URL to patch"
        }
    }
}
```

Have fun.

Contributors: https://code.adyax.com/itsekhmistro/awesome-repo
