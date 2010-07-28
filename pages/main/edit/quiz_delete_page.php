<?php 

  $quiz_id = get_param('quiz_id');
  $quiz_title = get_quiz_field($quiz_id, 'title');

?>

<form method="post" action="?action=edit/quiz_delete_exec">
  
  <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">

  <p class="qm_bad">
  Are you sure you want to permanently delete this quiz,
  including all questions, answers and images stored in it?
  </p>

  <div class="qmFormRow">
    <label>Quiz Title</label>
    <div class="qmFormFieldBox">
      <span class="qmText"><?php echo $quiz_title ?></span>
    </div>
  </div>

<hr/>

  <div class="qmFormRow">
    <input type="submit" class="qmFormSubmit" name="btnSubmit" value="OK" />
  </div>
  
  <div class="qmFormRow">
    <input type="submit" class="qmFormSubmit" name="btnSubmit" value="CANCEL" />
  </div>

</form>

