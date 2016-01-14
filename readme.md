# Drupal Boilerplate

Drupal boilerplate is not a module. Instead it just serves as a directory structure for
starting a new drupal site. The idea behind Drupal boilerplate came from working on so many
different sites which each follow their own development practice, directory structure,
deployment guidelines, etc...

Drupal boilerplate tries to simplify starting a new site by having the most common
directory structures and files already included and set up.

## Getting started 
You can start by [downloading](https://code.adyax.com/common/drupal8-boilerplate/repository/archive.zip?ref=master)
this project. Once you download it you will find that every folder contains a readme.md file.
This readme.md file has been extensively documented to explain what belongs
in that specific directory.

Here's a breakdown for what each directory/file is used for. If you want to know more please
read the readme inside the specific directory.

* www -  Where your drupal root should start.
* results - This directory is just used to export test results to. A good example of this
   is when running drush test-run with the --xml option. You can export the xml
   to this directory for parsing by external tools.
* scripts -  A directory for other non-drupal scripts.
* test -  A directory for external tests. This is great for non drupal specific tests
 such as selenium, qunit, casperjs.
* sync - Configuration directory
* patches - Directory for patches
* dev/enviroments/* - Per environment configurations. @see settings.php, services.yml, etc

Merged content of _Awesome Repository_ https://code.adyax.com/itsekhmistro/awesome-repo .

It contains initial structure and useful features for development Drupal project.

These are:
* config and install script to setup tools for static code analys locally
on developers machines (per project),
* pre-commit hook to analys files affected in the commits
(only custom code is taken in account),
* settings per environment,
* useful utils for writing casperjs autotests.

## Installation instructions.
1. LeadDev clone the repo into new Project repo.
2. Dev clones the new Project repository and runs `composer install` in 
the repo’s root directory.

Have fun.
