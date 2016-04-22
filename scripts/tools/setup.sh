#!/bin/sh

cp scripts/tools/pre-commit .git/hooks/pre-commit
chmod +x .git/hooks/pre-commit

# Create symlink
ln -sf ../../../../drupal/coder/coder_sniffer/Drupal vendor/squizlabs/php_codesniffer/CodeSniffer/Standards/Drupal
