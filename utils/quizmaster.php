<?php



# FETCHES A SCRIPT PARAMETER SENT FROM THE BROWSER (EITHER AS GET OR POST)
function get_param($variable) {
  if (isset($_REQUEST[$variable])) {
    return $_REQUEST[$variable];
  } else {
    return NULL;
  }
}



# Typically, any sane PHP production environment should run
# with registers global off for security reasons. However, the
# world is not perfect so QuizMaster tries to make sure no weird
# globals are set. All variables that should be registered are
# fetched manually at the beginning of each page script using the
# get_param() function. For more information this security issue
# see the PHP manual: http://se.php.net/manual/en/security.globals.php
function emulate_register_globals_off() {
  if (ini_get('register_globals')) {
    $superglobals = array($_SERVER, $_ENV, $_FILES, $_COOKIE, $_POST, $_GET);
    if (isset($_SESSION)) {
      array_unshift($superglobals, $_SESSION);
    }
    foreach ($superglobals as $superglobal) {
      foreach ($superglobal as $global => $value) {
        if ($global != 'superglobals' && $global != 'superglobal') {
          unset($GLOBALS[$global]);
        }
      }
    }
  }
}



function run_script($filename) {  
  if (file_exists(PAGES_DIRECTORY . $filename))
    include(PAGES_DIRECTORY . $filename);
  die("");
}



function serve_page($filename) {
  if (file_exists(PAGES_DIRECTORY . $filename)) { 
    include(INCLUDES_DIRECTORY . 'inc_header.php');
    include(PAGES_DIRECTORY . $filename); 
    include(INCLUDES_DIRECTORY . 'inc_footer.php');
    die();
  } else {
    serve_error('serve_page() with invalid parameter', '');
  }
}



# This function will:
# 1. Send a HTML header (inc_header.php), contains HTML, HEAD and all that
# 2. Send the quizmaster main page header (inc_main_header.php), contains main menu etc
# 3. Run the given PHP script
# 4. Send the quizmaster main page footer (inc_main_footer.php)
# 5. Send a HTML footer (inc_footer.php)
function serve_main_page($filename) {
  # Otherwise, serve visual page
  if (file_exists(MAINPAGES_DIRECTORY . $filename)) { 
    include(INCLUDES_DIRECTORY . 'inc_header.php');
    include(INCLUDES_DIRECTORY . 'inc_main_header.php');
    include(MAINPAGES_DIRECTORY . $filename); 
    include(INCLUDES_DIRECTORY . 'inc_main_footer.php');
    include(INCLUDES_DIRECTORY . 'inc_footer.php');
    die();
  } else {
    $backtrace = debug_backtrace();
    serve_error('serve_main_page() called with invalid parameter', !VERBOSE_ERROR_MESSAGES?'':'Tried to read file <code>' . MAINPAGES_DIRECTORY . $filename . '</code> which did not exist, this particular function call was made from row <code>' . $backtrace[0]['line'] . '</code> in the file named ' . $backtrace[0]['file']);
  }
}



function serve_error($error_message, $error_explanation) {
  global $current_theme;
  include(INCLUDES_DIRECTORY . 'inc_header.php');
  include(PAGES_DIRECTORY . 'error_page.php');
  include(INCLUDES_DIRECTORY . 'inc_footer.php');
  die;
}



function serve_policy_violation($policy_rule) {
  serve_error('Request blocked due to security policy violation.', $policy_rule);  
}



# Displays an error message when a database query fails.
# When the global boolean (set in index.php) VERBOSE_ERROR_MESSAGES is true
# the error will contain a full mysql error message, but this might be
# a security risk for production servers and thus in the release version
# this option will be turned off and the user will receive a normal error
# message saying that a database operation failed and that only the quiz
# administrator can fix this.
function serve_db_error($query) {
  if (VERBOSE_ERROR_MESSAGES) {
    $backtrace = debug_backtrace();
    $db_dbg = '<p class="qm_bad">MySQL ErrNo: ' . mysql_errno() . " Error: " . mysql_error() . '.</p>' .
              '<p class="qm_bad">Query: <code>' . $query . '</code></p>' .
              '<p class="qm_bad" >The failing database call was located around line ' . $backtrace[1]['line'] . ' in ' . $backtrace[1]['file'] . '</p>';
	serve_error('Database operation failed.', $db_dbg);
  } else {
    serve_error('Database operation failed.', 
                'QuizMaster was unable to access the database. ' .
                'Unfortunately, only the quiz site administrator ' .
                'will be able to fix this problem.');
  }
}



function serve_inconsistancy() {
  serve_error('Assertion failed, possibly due to a QuizMaster script bug.', 
              'The QuizMaster script failed or its database was found to be in an invalid state. ' .
              'This could be either a serious problem or a trivial bug. You are recommended to ' .
              'take a backup copy of your QuizMaster tables and contact the original script author ' .
              'using the e-mail address ' . ORIGINAL_QUIZMASTER_AUTHOR_EMAIL);
}



function get_current_theme() {
  return isset($GLOBALS['theme_override']) ? $GLOBALS['theme_override'] : CURRENT_THEME;
}



# Constans for use with keepOnlyTheseChars()
// Alphabetical letters (lower and upper cases) plus digits
define("ALPHANUMERICALS", 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
// ASCII chars from 32 to 126 excludes weird ASCIIs like the low-ascii terminal control chars
#define("ASCII_CHARS_32_TO_126_AND_TAB", ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~' . "\t");
// These chars are essentially chars 192-255 taken from Latin-1 a.k.a. ISO/IEC 8859-1 (and with the sole exception of char 0xF8 i.e. '˜')
#define("WEIRD_CHARS_FROM_NON_ENGLISH_LANGS", "¿¡¬√ƒ≈∆«»… ÀÃÕŒœ–—“”‘’÷◊ÿŸ⁄€‹›ﬁﬂ‡·‚„‰ÂÊÁËÈÍÎÏÌÓÔÒÚÛÙıˆ¯˘˙˚¸˝˛ˇ");

function keepOnlyTheseChars($string, $chars_to_keep) {
  $result = '';
  for ($k = 0; $k < strlen($string); $k++) {
    $char = substr($string, $k, 1);
    if (strpos($chars_to_keep, $char) !== FALSE)
      $result .= $char;
  }
  return $result;
}

function stripAllWhitspace($string) {
  $result = '';
  for ($k = 0; $k < strlen($string); $k++) {
    $char = substr($string, $k, 1);
    if ($char != " " && $char != "\t")
      $result .= $char;
  }
  return $result;
}

// Weird letters in the name of the capital of Moldovia is not supporter yet as they
// seem to belong to some weird charset not supported in .php source files?
//
// These chars are not supported ﬁ˛
// also – ==> D and  ==> d might be incorrect because – is "th" in islandic?
define("ACCENTUATED_CHARS",     "¿¡¬√ƒ≈«»… ÀÃÕŒœ–—“”‘’÷ÿŸ⁄€‹›ﬂ‡·‚„‰ÂÁËÈÍÎÏÌÓÔÒÚÛÙıˆ¯˘˙˚¸˝ˇ");
define("NON_ACCENTUATED_CHARS", "AAAAAACEEEEIIIIDNOOOOOOUUUUYBaaaaaaceeeeiiiidnoooooouuuuyy");

function unAccentuatate($string) {
  $result = '';
  for ($k = 0; $k < strlen($string); $k++) {
    $char = substr($string, $k, 1);
    $pos = strpos(ACCENTUATED_CHARS, $char);
    if ($pos !== FALSE)
      $result .= substr(NON_ACCENTUATED_CHARS, $pos, 1);
    else
      $result .= $char;
  }
  $result = str_replace("∆", "AE", $result);
  $result = str_replace("Ê", "ae", $result);  
  return $result;
}

?>
