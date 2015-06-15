<?php

$form_username = canonical_username(get_param('form_username'));
$form_password = get_param('form_password');
$form_password_again = get_param('form_password_again');
$form_email = get_param('form_email');
$form_realname = get_param('form_realname');
$btnSubmit = get_param('btnSubmit');

# This IF-statement (see below) is the result of a rather peculiar thing that PHP
# does. Imagine a method="POST" form that contains a textbox and a file upload
# widget. Now suppose that a user selects to upload a ridiculously large
# file, a file that is so big that it's size exceeds not only upload_max_filesize
# both also post_max_size. Now, the weird thing is the way PHP reacts to this,
# it simply chooses to wipe all data in $_POST and $_FILES ... meaning that
# whatever data was sent in the textbox field will be lost as well. This is
# not only counter-intuitive but it also limits the ability to write proper
# error handling. Typically, in a form you have some kind of "validation" for
# each field. For instance, username field may not be an existing username,
# a password field may not be empty. The user will sometimes submit the same
# form several times and comply with any violations of these rules. However,
# if the file uploaded is too large PHP makes it impossible to write code that
# re-displays the form (with all values) and prints "file is too big" next to
# the file upload widget. In effect, uploading a really large file to any PHP
# script will force you to start over (in some cases you can be saved by pressing
# the BACK button though) because the receiving end of the form will just receive
# both $_POST and $_FILES as completely empty variables.
#
# This annoyingly obscure behaviour is even documented, albeit in a subtle place.
# See the last parapraph in the documentation of "post_max_size", available here:
# http://se2.php.net/ini.core#ini.post-max-size
#
# It says:
#
#    If the size of post data is greater than post_max_size, the $_POST and
#    $_FILES superglobals are empty. This can be tracked in various ways, e.g.
#    by passing the $_GET variable to the script processing the data, i.e.
#    <form action="edit.php?processed=1">, and then checking if $_GET['processed'] is set.
#
# So, to get to the point... the IF-statement below will execute if and only if
# a really large file (making the HTTP request in total larger than post_max_size)
# has been uploaded as user image in the "create user" form.
if (empty($_POST)) {
  global $user_create_error_message;
  $user_create_error_message = "The image you specified is too large (it made the entire HTTP request size exceed the post_max_size limit which is currently set to " . ini_get("post_max_size") . ").";
  serve_page('user_create_page.php');
}

# IF AN IMAGE WAS SUPPLIED, IS IT VALID?
if ($_FILES['form_user_image']['error'] != UPLOAD_ERR_NO_FILE and # file upload box was left empty
    $_FILES['form_user_image']['error'] != UPLOAD_ERR_OK) {       # file upload box specifies a valid file
  global $user_create_error_message;
  switch ($_FILES['form_user_image']['error']) {
      case UPLOAD_ERR_INI_SIZE:
        $user_create_error_message = "The image you specified is too large (size exceeded upload_max_filesize which is currently set to " . ini_get("upload_max_filesize") . ").";
        break;
      case UPLOAD_ERR_PARTIAL:
        $user_create_error_message = "The image you specified was, for some reason, only partially uploaded (PHP error code UPLOAD_ERR_PARTIAL). Try to upload it again, and if that does not work, try another image instead.";
        break;
      case 6: # 6 == UPLOAD_ERR_NO_TMP_DIR
        $user_create_error_message = "The image could not be uploaded, because the server that runs this PHP script either (1) didn't configure a temporary folder, or (2) the configured temporary folder did not exist.";
        break;
      case 7: # 7 == UPLOAD_ERR_CANT_WRITE
        $user_create_error_message = "The image could not be uploaded, because the server could not write the uploaded file to disk. This issue is due to server problem and can only be resolved by the server administrator. Typically this problem is caused by (1) the server ran out of disk space, or (2) access rights for the directory where uploaded files are saved is not properly setup.";
        break;
      default:
        $user_create_error_message = "The image you specified could not be uploaded, because an unknown error occurred. The file upload error code was " . $_FILES['form_user_image']['error'] . ".";
        break;
    }
    serve_page('user_create_page.php');
}

# DID THE USER PRESS THE CANCEL BUTTON? --> RETURN TO LOGIN FORM
if ($btnSubmit == 'CANCEL') {
  header("Location: ?");
  die;
}

# DID THE USER OMIT A MANDATORY FIELD? --> RETRY
if ($form_username == '' or $form_password == '') {
  global $user_create_error_message;
  $user_create_error_message = "You must provide a username and a password.";
  serve_page('user_create_page.php');
}

# DOES THE SUPPLIED PASSWORDS MATCH?
if ($form_password != $form_password_again) {
  global $user_create_error_message;
  $user_create_error_message = "The passwords does not match, please type the same password twice.";
  serve_page('user_create_page.php');
}

connectToDatabase();
createTablesIfNotExists();

$form_username = mysql_real_escape_string($form_username);
$result = exec_query("SELECT * FROM qmtbl_users WHERE username='$form_username'");

if (mysql_num_rows($result) > 0) {
  global $user_create_error_message;
  $user_create_error_message = "The username you choose is already in use, please choose another.";
  serve_page('user_create_page.php');
}

# DID THE USER UPLOAD A PERSONAL IMAGE? --> FETCH FILE DATA
$image_specs_array = fetch_uploaded_image("form_user_image");
if ($image_specs_array !== NULL) {
  $return_value = check_mysql_packet_size($image_specs_array['image_size']);
  switch ($return_value) {
  case -2: case -1:
    serve_error('Database query failed or was inconsistent.', 'QuizMaster was unable to ' .
                'access the database to determine the maximum packet size the MySQL server can ' .
                'handle. It might still be possible to create an account if you omit the image.');
    break;
  case 0:  // OK. check_mysql_packet_size() returned OK, the packet size is large enough. No error, just continue.
    break;
  default: // If check_mysql_packet_size() returns a positive integer, the returned value is the maximum packet size.
      serve_error('Image file is to large for database to handle.', 'QuizMaster detected that the MySQL server ' .
                  'cannot handle packets large enough hold the image you provided. For this reason, QuizMaster ' .
                  'tried to change the maximum allowed packet size, however unfortunately this packet size limit ' .
                  'could not be modified. You may still be able to create an account if you choose a smaller image ' .
                  'or, if all else fails, omit the image. The maximum packet size varies heavily between servers, ' .
                  'but judging from the server parameters this particular server can probably handle packets up to ' .
                  'approximately <span class="qm_good">' . floor($return_value/1024) .
                  ' kilobytes</span> maximum. Press BACK to try again with a smaller image file.');
  }
}

$query = "INSERT INTO qmtbl_users(" .
         "username, password, registration_date, last_seen, image_data, image_mime, email, realname) VALUES (" .
         "'" . mysql_real_escape_string(strtolower($form_username)) . "', " .
         "'" . mysql_real_escape_string($form_password) . "', " .
         "now(), " .
         "now(), " .
         "'" . mysql_real_escape_string($image_specs_array['image_data']) . "', " .
         "'" . mysql_real_escape_string($image_specs_array['image_mime']) . "', " .
         "'" . mysql_real_escape_string($form_email) . "', " .
         "'" . mysql_real_escape_string($form_realname) . "')";

$result = mysql_query($query);
if (!$result) {
  switch (mysql_errno()) {
  case 1153:
    serve_error('Image file is to large for database to handle.', 'QuizMaster detected that the MySQL server ' .
                'cannot handle packets large enough hold the image you provided. For this reason, QuizMaster ' .
                'tried to change the maximum allowed packet size, however unfortunately this packet size limit ' .
                'could not be modified. You may still be able to create an account if you choose a smaller image ' .
                'or, if all else fails, omit the image. The maximum packet size varies heavily between servers, ' .
                'but judging from the server parameters this particular server can probably handle packets up to ' .
                'approximately <span class="qm_good">' . floor($max_allowed_packet_limitation/1000) .
                ' kilobytes</span> maximum. Press BACK to try again.');
  default:
    serve_db_error($query);
  }
}

include(PAGES_DIRECTORY . 'login_exec.php');

?>
