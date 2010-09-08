<?php

global $edit_question_error_message;

$question      = get_param('form_question');
$comment       = get_param('form_comment');
$question_type = get_param('form_question_type');
$btnSubmit     = get_param('btnSubmit');
$question_id   = get_param('question_id');

#
# VALIDATE FORM INPUT DATA
#
if (trim($question) == '') {
  $edit_question_error_message = 'The field "Question" can not be empty';
  serve_main_page('edit/question_edit_page.php');
}
if ($question_type !== 'qt_written_answer' && $question_type !== 'qt_multiple_choice') {
  $edit_question_error_message = 'Each question must have a question type.';
  serve_main_page('edit/question_edit_page.php');
}

#
# WAS THERE AN IMAGE INCLUDED WITH THIS QUESTION? --> FETCH AND STORE IMAGE DATA
#
$image_specs_array = fetch_uploaded_image('form_question_image');
if ($image_specs_array != NULL) {
  $return_value = check_mysql_packet_size($image_specs_array['image_size']);
  switch ($return_value) {
    case -2: case -1:
      serve_error('Database query failed or was inconsistent.', 'QuizMaster was unable to access the ' .
                  'database to determine the maximum packet size the MySQL server can handle. It might still ' .
                  'be possible to create a new question if you omit the image or choose a smaller image file.');
      break;
    case 0:  // OK. check_mysql_packet_size() returned OK, the packet size is large enough. No error, just continue.
      break;
    default: // If check_mysql_packet_size() returns a positive integer, the returned value is the maximum packet size.
      serve_error('Image file is to large for database to handle.', 'QuizMaster detected that the MySQL server ' .
                  'cannot handle packets large enough hold the image you provided. For this reason, QuizMaster ' .
                  'tried to change the maximum allowed packet size, however unfortunately this packet size limit could ' .
                  'not be modified. You may still be able to attach an image to this question if you choose a smaller ' .
                  'image or, if all else fails, omit the image. The maximum packet size varies heavily between servers, ' .
                  'but judging from the server parameters this particular server can probably handle packets up to ' .
                  'approximately <span class="qm_good">' . floor($return_value/1024) . ' kilobytes</span> maximum so ' .
                  'the image file must be at most this size. Press BACK to try again with a smaller image file.');
      break;
  }
  #
  # The image attached to this question exists in qmtbl_images?
  # NO  --> Insert it, and remember its image_image
  # YES --> Remember its image_id
  #
  $image_md5 = md5($image_specs_array['image_data']);
  $result = exec_query("SELECT * FROM qmtbl_images WHERE image_md5='$image_md5'");
  $row = mysql_fetch_assoc($result);
  # Result
  if (mysql_num_rows($result) == 1) {
    $image_id = $row['image_id'];
  } else if (mysql_num_rows($result) == 0) {
    $result = exec_query("INSERT INTO qmtbl_images(image_md5, image_mime, image_data) VALUES (" .
             "'$image_md5', " .
             "'" . mysql_real_escape_string($image_specs_array['image_mime']) . "', " .
             "'" . mysql_real_escape_string($image_specs_array['image_data']) . "')");
    $image_id = mysql_insert_id();
  } else {
    serve_inconsistancy(); // Database corrupted
  }
}


#
# CHECK IF THIS IS AN EXISTING QUESTION OR NOT
#

# Prepare
$question_id = mysql_real_escape_string($question_id);
$result = exec_query("SELECT * FROM qmtbl_questions WHERE question_id='$question_id'");
$row = mysql_fetch_assoc($result);
# Result
if (mysql_num_rows($result) == 0) {
  $question_exists = false;
  $quiz_id = get_param('quiz_id');
} else if (mysql_num_rows($result) == 1) {
  $question_exists = true;
  $quiz_id = get_question_field($question_id, 'quiz_id');
} else {
  serve_inconsistancy();
}

#
# SO DID THIS QUESTION EXISTED IN DATABASE ALREADY?
# YES --> UPDATE IT
# NO  --> CREATE A NEW DATABASE ROW FOR IT
#

# Prepare          Note: We already did mysql_real_escape($question_id)
$quiz_id = mysql_real_escape_string($quiz_id);
$question = mysql_real_escape_string($question);
$comment = mysql_real_escape_string($comment);
if (!$question_exists) {
  exec_query("INSERT INTO qmtbl_questions(quiz_id, question, comment) VALUES ($quiz_id, '$question', '$comment')"); 
  $question_id = mysql_insert_id();
} else
  exec_query("UPDATE qmtbl_questions SET quiz_id=$quiz_id, question='$question', comment='$comment' WHERE question_id=$question_id");  

if (isset($image_id)) {
  # Also update the image_id reference
  $image_id = mysql_real_escape_string($image_id);
  exec_query("UPDATE qmtbl_questions SET image_id=$image_id WHERE question_id=$question_id");  
}

# NOW LETS LOOK AT THE QUESTION TYPE. WE ONLY ALLOW THE USER TO CHANGE THIS TYPE IF
# THERE ARE NO ANSWERS CREATED, OTHERWISE THESE MUST BE DELETED FIRST. WE DO THIS CHECK 
# DOWN HERE ON PURPOSE BECAUSE IF THE USER CHANGES TITLE, COMMENT ETC WE DONT WANT 
# HIM TO LOOSE THAT DATA (HENCE ITS STORED ABOVE). NOTE THAT QUIZMASTER DOESNT SUPPORT 
# QUESTIONS WHICH ACCEPTS BOTH WRITTEN ANSWERS AND SELECTED (MULTIPLE CHOICE) ONES.
$old_question_type = get_question_field($question_id, 'question_type');
if ($old_question_type == NULL) { $old_question_type = $question_type; } // if this is a new question (not in db), we dont argue
if ($old_question_type != $question_type && possible_answer_count($question_id) > 0) {
  $edit_wa_question_error_message = 'You must delete all existing answers before changing question type';
  serve_main_page('page_edit_question.php');
}
# OK QUESTION TYPE CHANGE IS OK. SAVE NEW TYPE IN DATABASE.
// Note: we already did mysql_real_escape_string($question_id)
exec_query("UPDATE qmtbl_questions SET question_type='$question_type' WHERE question_id=$question_id");


#
# OK NOW THE BASIC QUESTION DATA (question, image, comment) IS SAVED.
# SO WHICH BUTTON DID THE USER PRESS?
# EDIT BUTTON BESIDE A QUESTION --> OPEN EDIT FORM FOR THAT ANSWER
# ADD ANSWER BUTTON             --> OPEN EDIT FORM FOR A NEW ANSWER
# STORE QUESTION                --> RETURN TO 'EDIT QUIZ'
# 

if ($btnSubmit == 'ADD ANSWER') {  
  if ($question_type == 'qt_written_answer')
    header("Location: ?action=edit/answer_wa_edit&question_id=$question_id");
  else
    header("Location: ?action=edit/answer_mc_edit&question_id=$question_id");
} else if ($btnSubmit == 'CLOSE') {
  header("Location: ?action=edit/quiz_edit&quiz_id=$quiz_id");
}


?>
