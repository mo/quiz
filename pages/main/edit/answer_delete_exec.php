<?php

$btnSubmit    = get_param('btnSubmit');
$answer_id    = get_param('answer_id');
$question_id  = get_param('question_id');

$question_type    = get_question_field($question_id, 'question_type');

if ($btnSubmit == 'OK') {

  $answer_id = mysql_real_escape_string($answer_id);
  if ($question_type == 'qt_written_answer')
    exec_query("DELETE FROM qmtbl_wa_answers WHERE answer_id=$answer_id");
  else if ($question_type == 'qt_multiple_choice')
    exec_query("DELETE FROM qmtbl_mc_answers WHERE answer_id=$answer_id");
  else serve_inconsistancy();

}

header("Location: ?action=edit/question_edit&question_id=$question_id");

?>
