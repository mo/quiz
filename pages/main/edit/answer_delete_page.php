<?php

  $question_id      = get_param('question_id');
  $answer_id        = get_param('answer_id');

  $question_type    = get_question_field($question_id, 'question_type');
  if ($question_type == 'qt_written_answer')
    $answer = get_wa_answer_field($answer_id, 'answer');
  else if ($question_type == 'qt_multiple_choice')
    $answer = get_mc_answer_field($answer_id, 'answer');

?>

<form method="post" action="?action=edit/answer_delete_exec">

  <input type="hidden" name="question_id" value="<?php echo $question_id; ?>">
  <input type="hidden" name="answer_id" value="<?php echo $answer_id; ?>">

  <p class="qm_bad">Are you sure you want to permanently delete this answer?</p>

  <div class="qmFormRow">
    <label>Answer</label>
    <div class="qmFormFieldBox"><span class="qmText"><?php echo $answer ?></span></div>
  </div>

<hr/>

  <div class="qmFormRow">
    <input type="submit" class="qmFormSubmit" name="btnSubmit" value="OK">
  </div>

  <div class="qmFormRow">
    <input type="submit" class="qmFormSubmit" name="btnSubmit" value="CANCEL">
  </div>

</div>


</form>
