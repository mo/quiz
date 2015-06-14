<?php

$form_quiz_title = get_param('form_quiz_title');
$form_quiz_availability = get_param('form_quiz_availability');
$quiz_id = get_param('quiz_id');
$btnSubmit = get_param('btnSubmit');

global $edit_quiz_error_message;

# VALIDATE INPUT
if ($form_quiz_title == '') {
  $edit_quiz_error_message = 'You must specify a title for your quiz.';
  serve_main_page('edit/quiz_edit_page.php');
}

# PREPROCESS INPUT (for use either in updating an existing quiz OR for creating a new quiz)
$user_id = get_user_id();
$quiz_id = mysql_real_escape_string($quiz_id);
$form_quiz_title = mysql_real_escape_string($form_quiz_title);
if ($form_quiz_availability == 'public') {
  $public_quiz = '1';
} else {
  $public_quiz = '0';
}


# IF AN EXISTING QUIZ ID IS PROVIDED --> UPDATE IT.
if ($quiz_id != NULL) {
  ######################################################################
  # BUT FIRST CHECK THAT THE CLIENT OWNS THE QUIZ HE WANTS TO UPDATE.
  ######################################################################

  # PREPROCESS QUERY
  $quiz_id = mysql_real_escape_string($quiz_id);
  $result = exec_query("SELECT owner_user_id FROM qmtbl_quizes WHERE quiz_id=$quiz_id");
  if (mysql_num_rows($result) == 0) {
    serve_error('Invalid quiz identifier, no such quiz.', '');
  }
  $row = mysql_fetch_assoc($result);
  if (get_user_id() !== $row['owner_user_id']) {
    serve_policy_violation('You can only edit quizzes that you have created.');
  }

  ######################################################################
  # UPDATE AUTHORIZED, NOW UPDATE THE SPECIFIED QUIZ
  ######################################################################

  // Note: we already did mysql_real_escape_string($quiz_id);
  //       also $user_id doesn't need escaping because it comes from the DB
  exec_query("UPDATE qmtbl_quizes SET public_quiz=$public_quiz, title='$form_quiz_title' " .
             "WHERE quiz_id=$quiz_id AND owner_user_id=$user_id");

} else {

  ######################################################################
  # NO QUIZ ID WAS PROVIDED --> CREATE A NEW QUIZ OWNED BY CURRENT USER
  ######################################################################

  # PREPROCESS QUERY
  exec_query("INSERT INTO qmtbl_quizes(owner_user_id, public_quiz, title, creation_date, times_finished) " .
             "VALUES ($user_id, $public_quiz, '$form_quiz_title', now(), 0)");
  # FORWARD USER TO THE "EDIT QUIZ CONTENTS" SCREEN
  $quiz_id = mysql_insert_id();
}


######################################################################
# BASIC QUIZ DATA (title, visibility) SAVED.
# WHICH BUTTON DID THE USER PRESS?
######################################################################

if ($btnSubmit == 'ADD NEW QUESTION') {
  header('Location: ?action=edit/question_edit&quiz_id=' . $quiz_id);
} else if ($btnSubmit == 'SAVE AND CLOSE QUIZ') {
  header("Location: ?action=edit/quizes_edit");
} else {
   // TODO: handle edit,up,down,delete buttons for all questions.

}


?>
