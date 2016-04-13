#!/bin/sh

cp tools/pre-commit .git/hooks/pre-commit
chmod +x .git/hooks/pre-commit

# Create symlink
if [ ! -L vendor/squizlabs/php_codesniffer/CodeSniffer/Standards/Drupal/Drupal ]
  then
    ln -s ../../../../drupal/coder/coder_sniffer/Drupal vendor/squizlabs/php_codesniffer/CodeSniffer/Standards/Drupal
fi