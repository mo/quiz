<?php

  $question_id           = get_param('question_id');
  $answer_id             = get_param('answer_id');
  $answer                = get_param('form_answer');
  $btnSubmit             = get_param('btnSubmit');
  $form_lowercase        = get_param('form_lowercase');
  $form_nowhitespace     = get_param('form_nowhitespace');
  $form_noaccentuations  = get_param('form_noaccentuations');
  $form_onlyalphanumeric = get_param('form_onlyalphanumeric');

  if ($btnSubmit == 'OK') {
    $question_id = mysql_real_escape_string($question_id);
  $answer = mysql_real_escape_string($answer);
  $transform = '';
  if ($form_lowercase) $transform .= "lowercase,";
  if ($form_nowhitespace) $transform .= "nowhitespace,";
  if ($form_noaccentuations) $transform .= "noaccentuations,";
  if ($form_onlyalphanumeric) $transform .= "onlyalphanumeric,";
  if (substr($transform, strlen($transform) - 1, 1) == ',') $transform = substr($transform, 0, strlen($transform) - 1);
  $transform = "'" . $transform . "'";
  $answer_id = mysql_real_escape_string($answer_id);
  if ($answer_id == -1)
    exec_query("INSERT INTO qmtbl_wa_answers(question_id, answer, preMatchingTransform) VALUES ($question_id, '$answer', $transform)");
  else
    exec_query("UPDATE qmtbl_wa_answers SET question_id=$question_id, answer='$answer', preMatchingTransform=$transform WHERE answer_id=$answer_id");
  }

  header("Location: ?action=edit/question_edit&question_id=$question_id");

?>
