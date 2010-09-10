<?php

  # This page is opened with EITHER answer_id OR question_id provided as a parameter.
  # answer_id       (specified when editing existing answers)
  # question_id     (specified when creating a new answer, question_id specifies
  #                  to which question the new answer should be attached)
  if (get_param('answer_id') != null) {
    $answer_id    = get_param('answer_id');
    $question_id  = get_mc_answer_field($answer_id, 'question_id');
    $answer       = get_mc_answer_field($answer_id, 'answer');
    $is_correct   = get_mc_answer_field($answer_id, 'is_correct');
  } else {
    $answer_id    = -1;
    $question_id  = get_param('question_id');
    $answer       = "";
    $is_correct   = "0";
  }

  $question       = get_question_field($question_id, 'question');
  $quiz_id        = get_question_field($question_id, 'quiz_id');
  $quiz_title     = get_quiz_field($quiz_id, 'title');

?>

<p class="qmText">This answer is attached to the question
<span class="qm_shadedText"><?php echo "'$question'"; ?></span>
which belongs to the quiz <span class="qm_shadedText"><?php echo "'$quiz_title'"; ?></span>.</p>

<form method="post" action="?action=edit/answer_mc_edit_exec" name="mainform">

  <input type="hidden" name="question_id" value="<?php echo $question_id; ?>">
  <input type="hidden" name="answer_id" value="<?php echo $answer_id; ?>">

  <div class="qmFormRow">
    <label for="form_answer">Answer</label>
    <input name="form_answer" type="text" value="<?php echo $answer ?>" class="qmFormText" autocomplete="off" />
  </div>

  <div class="qmFormRow">
    <div class="qmFormFieldBox">
      <div>
        <input type="checkbox" name="form_is_correct"<?php echo checked($is_correct) ?> />
	<label class="qmFormAltText" for="form_is_correct">Is (one of the) correct answers</label>
      </div>
    </div>
  </div>
<hr/>

  <input name="btnSubmit" type="submit" value="OK" class="qmFormSubmit"/>
  <br />

  <input name="btnSubmit" type="submit" value="CANCEL" class="qmFormSubmit" />
  <br />

</form>

<script language="JavaScript">
<!--
document.mainform.form_answer.focus();
//-->
</script>
