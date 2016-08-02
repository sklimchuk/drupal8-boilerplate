#!/bin/sh

### Create Codesniffer symlink ###
# Target file.
TARGET=../../../../drupal/coder/coder_sniffer/Drupal
# Link name.
LINK_NAME=vendor/squizlabs/php_codesniffer/CodeSniffer/Standards/Drupal
# Link folder.
LINK_FOLDER=vendor/squizlabs/php_codesniffer

if [ -d "$LINK_FOLDER" ]; then
  # Create symlink
  ln -sf ${TARGET} ${LINK_NAME}

  # Pre commit hook
  cp scripts/tools/pre-commit .git/hooks/pre-commit
  # Make files executable.
  chmod +x .git/hooks/pre-commit
fi
### End Create symlink ####

### Git hooks ####
GIT_HOOKS_FOLDER=.git/hooks
if [ -d "$GIT_HOOKS_FOLDER" ]; then
  # Post merge hook.
  cp scripts/tools/post-merge .git/hooks/post-merge
  # Make files executable.
  # Used only for dev env.
  chmod +x .git/hooks/post-merge
  ### End Git hooks ####
fi
