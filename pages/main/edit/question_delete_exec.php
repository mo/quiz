<?php

$question_id = get_param('question_id');
$btnSubmit = get_param('btnSubmit');

$quiz_id = get_question_field($question_id, 'quiz_id');

if ($btnSubmit == 'OK') {

  $question_id = mysql_real_escape_string($question_id);

  exec_query("DELETE FROM qmtbl_wa_answers WHERE question_id=$question_id");
  exec_query("DELETE FROM qmtbl_questions WHERE question_id=$question_id");

}

header("Location: ?action=edit/quiz_edit&quiz_id=$quiz_id");

?>
