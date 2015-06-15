<?php

  $question_id = get_param('question_id');
  $question = get_question_field($question_id, 'question');

?>

<form method="post" action="?action=edit/question_delete_exec">

  <input type="hidden" name="question_id" value="<?php echo $question_id; ?>">

  <p class="qm_bad">
    Are you sure you want to permanently delete this question,
    including all answers and images stored with it?
  </p>

  <div class="qmFormRow">
    <label>Question</label>
    <div class="qmFormFieldBox">
      <span class="qmText"><?php echo $question ?></span>
    </div>
  </div>

<hr/>

  <div class="qmFormRow">
    <input type="submit" class="qmFormSubmit" name="btnSubmit" value="OK" />
  </div>

  <div class="qmFormRow">
    <input type="submit" class="qmFormSubmit" name="btnSubmit" value="CANCEL" />
  </div>

</div>

</form>
