<?php

  # This script outputs an image so we can't easily print an error message.
  # Maybe it would be a good idea to generate an error image instead though.

  $question_id = get_param('question_id');

  connectToDatabase();
  createTablesIfNotExists();

  $quiz_id = get_question_field($question_id, 'quiz_id');
  if ($quiz_id == null)
    serve_error("Invalid question id.",
                "The specified question id does not match an entry in the image database.");
  $public_quiz = get_quiz_field($quiz_id, 'public_quiz');
  $owner_user_id = get_quiz_field($quiz_id, 'owner_user_id');
  $is_quiz_ower = $owner_user_id === get_user_id();
  if (!$public_quiz && !$is_quiz_ower)
    serve_policy_violation('You may only view images from quizzes marked as public or those you have created yourself.');

  $image_id = get_question_field($question_id, 'image_id');
  $result = exec_query("SELECT image_data, image_mime FROM qmtbl_images WHERE image_id='$image_id'");
  if (mysql_num_rows($result) == 0)
    serve_error("No question image found.", 
                "There was no image attached to the question with this particular question id.");
  $row = mysql_fetch_assoc($result);

  header("Content-Type: " . $row['image_mime']);
  echo $row['image_data'];
?>
