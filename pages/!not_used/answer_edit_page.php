<?php
  
  $quiz_id = get_param('quiz_id');
  $question_id = get_param('question_id');
  
  $question = get_question_field($question_id, 'question');
  $quiz_title = get_quiz_field($quiz_id, 'title');
  $question_type = get_question_field($question_id, 'question_type');
  if ($quiz_title === false) {
    serve_error('Invalid quiz id, no such quiz.', 'You where redirected to, or deliberately ' .
                'browsed to, the edit page for an invalid quiz id. The most likely reason for' .
                'this error is that the quiz has been deleted.');
  }                        

//  if ($question_type == 'qt_written_answer')
  




?>

aaa


