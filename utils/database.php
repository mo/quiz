<?php

#
# DATABASE TABLE DEFINITIONS
# Note: The data type 'MEDIUMBLOB' can hold approx 16MB data
#
define("QMTBL_USERS", "user_id INT NOT NULL AUTO_INCREMENT, username TEXT, password TEXT, " .
               "registration_date DATETIME, last_seen DATETIME, " .
               "image_mime TEXT, image_data MEDIUMBLOB, realname TEXT, email TEXT, PRIMARY KEY (user_id)");
define("QMTBL_QUIZES", "quiz_id INT NOT NULL AUTO_INCREMENT, owner_user_id INT, public_quiz BOOL, " .
                "title TEXT, creation_date DATETIME, last_finished DATETIME, times_finished INT, " .
                "PRIMARY KEY (quiz_id), FOREIGN KEY (owner_user_id) REFERENCES qmtbl_users (user_id)");
define("QMTBL_IMAGES", "image_id INT NOT NULL AUTO_INCREMENT, image_md5 TEXT, image_mime TEXT, image_data MEDIUMBLOB, " .
                "PRIMARY KEY (image_id)");
define("QMTBL_QUESTIONS", "question_id INT NOT NULL AUTO_INCREMENT, quiz_id INT, question TEXT, " .
                   "comment TEXT, image_id INT, question_type ENUM('qt_written_answer', 'qt_multiple_choice'), " .
                   "PRIMARY KEY (question_id), FOREIGN KEY (quiz_id) REFERENCES qmtbl_quizes (quiz_id), " .
                   "FOREIGN KEY (image_id) REFERENCES qmtbl_images (image_id)");
define("QMTBL_WA_ANSWERS", "answer_id INT NOT NULL AUTO_INCREMENT, question_id INT, " . 
                   "preMatchingTransform SET('lowercase', 'nowhitespace', 'noaccentuations', " .
                   "'onlyalphanumeric'), answer TEXT, " .
                   "PRIMARY KEY (answer_id), " .
                   "FOREIGN KEY (question_id) REFERENCES qmtbl_questions (question_id)");
define("QMTBL_MC_ANSWERS", "answer_id INT NOT NULL AUTO_INCREMENT, question_id INT, " . 
                   "answer TEXT, is_correct BOOL, " .
                   "PRIMARY KEY (answer_id), " .
                   "FOREIGN KEY (question_id) REFERENCES qmtbl_questions (question_id)");

####################################################################
### Utility functions.
####################################################################

function connectToDatabase() {
  if (!function_exists("mysql_connect")) {
    serve_error('MySQL functions not defined in PHP',
                'QuizMaster tried to use the mysql_connect() function but it was not defined. Please speak to your system ' .
                'administrator (or check configuration yourself if you\'re on a personal server). Typically the MySQL bindings ' .
                'have not been installed properly, or not installed at all. On Windows you must make sure that your php directory ' .
                'has a sub-directory called ext which contains the file php_mysql.dll, and then you must add or uncomment the line ' .
                'in php.ini that says "extension=php_mysql.dll". On Linux you can install a special version of the PHP package in ' .
                'order to get the MySQL bindings right, ie on systems with apt-get you should use "apt-get install php4-mysql" ' .
                '(or possibly php5 etc). Note that any of these instructions might have changed, so check the documentation (or ask on IRC).');
  }
  # Note; Prefixing the mysql_connect() function with a @-symbol prevents
  # warnings/errors from beeing printed directly to the screen.
  if (!@mysql_connect(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD)) {
    switch (mysql_errno()) {
      case 0:     # 0    == no error occurred
        break;
      case 2005:  # 2005 == CR_UNKNOWN_HOST      caused by connecting to "www.doesnotexistandwillcausetimeoutpluserrormessage.com"
      case 2003:  # 2003 == CR_CONN_HOST_ERROR   caused by connecting to "10.1.2.3" (ie a local IP range that is not on the same network as this host) or "www.svd.se" (domain exists but no reply from that IP)
        serve_error('Could not connect to database server (MySQL ErrNo==' . mysql_errno() . ')',
                    'QuizMaster failed to connect to the database. This error could be caused by several things, for instance; ' .
                    'the database server has crashed, the database server is down for scheduled maintainance or the network link ' .
                    'between the web server and the database server has been disrupted. Finally it could also be that the QuizMaster ' .
                    'database configuration is not properly tuned. Typically only the quiz site administrator can fix this problem.');
        break;
      case 1045:  # 1045 == ER_ACCESS_DENIED_ERROR
        serve_error('Database login incorrect (wrong user, pass or db)',
                    'QuizMaster could not login to the database server because the username, password and/or database that QuizMaster tried where invalid. ' .
                    'Please check that the database account is properly setup and that QuizMaster has the right database login credentials configured ' . 
                    'in its settings file (typically located at <code>quizmaster/' . SETTINGS_FILE . '</code>).');
        break;
      default:
        serve_db_error("Error while connecting, no query available.");
    }
  }
  exec_query("CREATE DATABASE IF NOT EXISTS " . MYSQL_DATABASE);
  mysql_select_db(MYSQL_DATABASE) or serve_db_error("Error while selecting database, no query available.");
}

function createTablesIfNotExists() {
  exec_query('CREATE TABLE IF NOT EXISTS qmtbl_users(' . QMTBL_USERS . ');');
  exec_query('CREATE TABLE IF NOT EXISTS qmtbl_quizes(' . QMTBL_QUIZES . ');');
  exec_query('CREATE TABLE IF NOT EXISTS qmtbl_images(' . QMTBL_IMAGES . ');');
  exec_query('CREATE TABLE IF NOT EXISTS qmtbl_questions(' . QMTBL_QUESTIONS . ');');
  exec_query('CREATE TABLE IF NOT EXISTS qmtbl_wa_answers(' . QMTBL_WA_ANSWERS . ');');
  exec_query('CREATE TABLE IF NOT EXISTS qmtbl_mc_answers(' . QMTBL_MC_ANSWERS . ');');
}   

function exec_query($query) {
  if (!($result = mysql_query($query))) serve_db_error($query);
  return $result;
}

?>
