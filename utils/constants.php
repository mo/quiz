<?php

#
# DO NOT EDIT THIS FILE
#


#
# Use the web setup to configure QuizMaster, it will be automatically
# activated the first time you surf to index.php
#
# script/config.php contains functions and configuration DEFAULT VALUES.
# If you want to customize QuizMaster you should NOT edit this file, but
# instead change the values in the SETTINGS_FILE. If the SETTINFS_FILE
# does not exist you need to surf to the index.php and complete the web
# setup!
#

# The QuizMaster script version.
define("QUIZMASTER_VERSION", "v0.01");

# This file will be overwritten by QuizMaster when the web setup guide runs.
define("SETTINGS_FILE", "prefs/settings.php");

# Directories
define("THEMES_DIRECTORY", "theme/");
define("PAGES_DIRECTORY", "pages/");
define("MAINPAGES_DIRECTORY", "pages/main/");
define("INCLUDES_DIRECTORY", "pages/includes/");

# This e-mail is not used to something fishy; it's only printed
# as part of the HTML error message when an assertion fails.
define("ORIGINAL_QUIZMASTER_AUTHOR_EMAIL", "opencode@minimum.se");

# Misc global constants
define("QM_COOKIE_NAME__USERNAME", "qmUsername");
define("QM_COOKIE_NAME__PASSWORD", "qmPassword");
define("QM_COOKIE_NAME__GUEST",    "qmGuest");     # equals '1' for guests

# In order to check if cookie functionality is available QuizMasters sets
# this cookie, redirects to itself and looks if the cookie it set properly.
define("QM_COOKIE_NAME__TEST_COOKIE", "qmTestCookie");
define("QM_TEST_COOKIE_CONTENT", "This is the test cookie content.");

# Make sure QuizMaster settings from the $SETTINGS_FILE is loaded,
# typically all default values are changed when this file is loaded
# so the default values are usually just used during the first time
# QuizMaster is used, ie under the web setup stage.

if (file_exists(SETTINGS_FILE))
  @include(SETTINGS_FILE);
else {
  # Default values used before the setup form data has been saved
  define("MYSQL_HOSTNAME",    "localhost");
  define("MYSQL_DATABASE",    "");
  define("MYSQL_USERNAME",    "");
  define("MYSQL_PASSWORD",    "");
  define("MYSQL_ADMIN_EMAIL", "");
  define("CURRENT_THEME",     "miniblue");
}

?>
