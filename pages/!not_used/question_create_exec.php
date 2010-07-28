<?php

$btnSubmit = get_param('btnSubmit');
$quiz_id = get_param('quiz_id');

switch ($btnSubmit) {
  case 'ADD WRITTEN ANSWER QUESTION':
    $question_type = 'written answer';
    serve_main_page('page_edit_question.php');
    break;
  case 'ADD WRITTEN ENUMERATION QUESTION':
    break;
}


?>
