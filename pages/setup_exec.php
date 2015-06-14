<?php

# 
# This script is called by the setup form which is displayed by page_setup.php
# It will create/write another PHP script, namely config/settings.php, which holds
# all QuizMaster configuration such as database hostname/name/username/password
# and which theme is the current theme etc.
#
# Security Note 1: It's important that the file written is not readable to
# external users because it contains vital database login credentials!
#


$form_mysql_hostname = get_param('form_mysql_hostname');
$form_mysql_database = get_param('form_mysql_database');
$form_mysql_username = get_param('form_mysql_username');
$form_mysql_password = get_param('form_mysql_password');
$form_theme          = get_param('form_theme');
$form_admin_email    = get_param('form_admin_email');
$btnSubmit           = get_param('btnSubmit');



# Was this a preview request or a we done with this form?
if ($btnSubmit == 'PREVIEW THEME') {
  $GLOBALS['theme_override'] = $form_theme;
  include(INCLUDES_DIRECTORY . 'inc_header.php');
  include(PAGES_DIRECTORY . 'setup_page.php'); 
  include(INCLUDES_DIRECTORY . 'inc_footer.php');
  die();
}



# Validate form data
if ($form_mysql_hostname == '' || $form_mysql_database == '' ||
    $form_mysql_username == '' || $form_mysql_password == '') {
  $GLOBALS['error_message'] = "All the MySQL fields are mandatory.";
  include(INCLUDES_DIRECTORY . 'inc_header.php');
  include(PAGES_DIRECTORY . 'setup_page.php'); 
  include(INCLUDES_DIRECTORY . 'inc_footer.php');
  die();
}



$settings_php = @fopen(SETTINGS_FILE, 'w');
if ($settings_php === FALSE)
  serve_error('Could not write settings file.',
              'QuizMaster tried to create (or overwrite) the file ' . SETTINGS_FILE . ' but failed. ' .
              'Please check that the file and/or directory has the right permissions set.');
fputs($settings_php, '<?php' . "\n");
fputs($settings_php, "\n");
fputs($settings_php, "#\n");
fputs($settings_php, "# This file is automatically edited by pages/setup_exec.php\n");
fputs($settings_php, "#\n");
fputs($settings_php, "\n");
fputs($settings_php, 'define("MYSQL_HOSTNAME", "' . $form_mysql_hostname . '");'. "\n");
fputs($settings_php, 'define("MYSQL_DATABASE", "' . $form_mysql_database . '");'. "\n");
fputs($settings_php, 'define("MYSQL_USERNAME", "' . $form_mysql_username . '");'. "\n");
fputs($settings_php, 'define("MYSQL_PASSWORD", "' . $form_mysql_password . '");'. "\n");
fputs($settings_php, "\n");
fputs($settings_php, 'define("ADMIN_EMAIL",    "' . $form_admin_email . '");' . "\n");
fputs($settings_php, "\n");
fputs($settings_php, 'define("CURRENT_THEME",  "' . $form_theme . '");' . "\n");
fputs($settings_php, "\n");
fputs($settings_php, '?>'. "\n");
fclose($settings_php);

# Set visibility of the config/database.php file so only the file owner can read it.
# Typically this should not be needed but a lot of poeple have weird UMASKs with
# free-for-all public write and stuff like that and this certainly doesn't hurt anyone.
chmod(SETTINGS_FILE, 0600);

# Make sure the rest of the script is not unecessaryily visible to the outside world.
chmod('prefs', 0700);
chmod('utils', 0700);
chmod('pages', 0700);
chmod('theme', 0755);  # 755 makes it possible to surf to the dir



header("Location: ?");


?>
