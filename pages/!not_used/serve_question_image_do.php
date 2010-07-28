<?php

  # This script outputs an image so we can't easily print an error message.
  # Maybe it would be a good idea to generate an error image instead though.

  
  connectToDatabase();
  createTablesIfNotExists();

  #
  # Get image_index and availability for the image attached to the quiz with id 'quiz_id'
  #

  # PREPROCESS
  $question_id = mysql_real_escape_string(get_param('question_id'));
  # EXECUTE QUERY (the join is just because we need owner_user_id too)
  $result = exec_query("SELECT * FROM qmtbl_questions, qmtbl_quizes " . 
         "WHERE qmtbl_questions.quiz_id=qmtbl_quizes.quiz_id AND question_id='$question_id'");
  if (mysql_num_rows($result) == 0) {
    serve_error("Invalid question id", "");
  }
  $row = mysql_fetch_assoc($result);
  # Viewing images attached to other users' privare quizes is not allowed.
  $user_id = get_user_id();
  if ($user_id !== $row['owner_user_id'] && $row['public_quiz'] !== '1') {
    serve_policy_violation('You can only see images attached to public quizes.');
  }
  $image_index = $row['image_index'];

  #
  # Get image data for image with index image_index
  #

  # EXECUTE QUERY  
  // Note: mysql_real_escape_string($image_index) is not needed since $image_index comes directly from the db
  $result = exec_query("SELECT * FROM qmtbl_images WHERE image_index='$image_index'");
  if (mysql_num_rows($result) == 0) {
    serve_error("QuizMaster could not find the requested image.", 
                "There was no user found in the database with the specified username.");
  } 
  $row = mysql_fetch_assoc($result);

  header("Content-Type: " . $row['image_mime']);
  echo $row['image_data'];

?>
