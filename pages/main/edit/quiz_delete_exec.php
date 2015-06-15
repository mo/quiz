<?php

$quiz_id = get_param('quiz_id');
$btnSubmit = get_param('btnSubmit');

if ($btnSubmit == 'OK') {

  $quiz_id = mysql_real_escape_string($quiz_id);

  # DELETE ALL ANSWERS TO QUESTIONS IN THIS QUIZ
  exec_query("DELETE FROM qmtbl_wa_answers WHERE question_id IN (SELECT question_id FROM qmtbl_questions WHERE quiz_id=$quiz_id)");

  # DELETE ALL QUESTIONS IN THIS QUIZ
  exec_query("DELETE FROM qmtbl_questions WHERE quiz_id=$quiz_id");

  # DELETE THE QUIZ ITSELF
  exec_query("DELETE FROM qmtbl_quizzes WHERE quiz_id=$quiz_id");

}

header('Location: ?action=edit/quizzes_edit');

?>
