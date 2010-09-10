<?php

  $question_id           = get_param('question_id');
  $answer_id             = get_param('answer_id');
  $answer                = get_param('form_answer');
  $is_correct            = get_param('form_is_correct');
  $btnSubmit             = get_param('btnSubmit');

  if ($btnSubmit == 'OK') {
    $question_id = mysql_real_escape_string($question_id);
	$answer = mysql_real_escape_string($answer);
	$answer_id = mysql_real_escape_string($answer_id);
	if ($is_correct === "on")
	  $is_correct = "1";
	else
	  $is_correct = "0";
	if ($answer_id == -1)
	  exec_query("INSERT INTO qmtbl_mc_answers(question_id, answer, is_correct) VALUES ($question_id, '$answer', $is_correct)");
	else
	  exec_query("UPDATE qmtbl_mc_answers SET question_id=$question_id, answer='$answer', is_correct=$is_correct WHERE answer_id=$answer_id");
  }

  header("Location: ?action=edit/question_edit&question_id=$question_id&blah=$is_correct");

?>
