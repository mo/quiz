<?php

  $quiz_id = get_param("quiz_id");

  $quiz_title = get_quiz_field($quiz_id, 'title');
  $question_count = get_question_count($quiz_id);

  $quiz_order = rand();
?>

  <label>Quiz Title</label>
  <div class="qmFormFieldBox"><span class="qmText"><?php echo $quiz_title ?></span></div>
  <br />

  <label>Question Count</label>
  <div class="qmFormFieldBox"><span class="qmText"><?php echo $question_count ?></span></div>
  <br />

  <hr />

  <form name="mainform" action="?action=take/question_query" method="post">

  <input type="hidden" name="quiz_id" value="<?php echo $quiz_id ?>" />
  <input type="hidden" name="question" value="1" />
  <input type="hidden" name="quiz_order" value="<?php echo $quiz_order ?>" />

  <div class="qm_wideButton_list">
    <input class="qmWideButton" type="submit" name="btnSubmit"  value="START THIS QUIZ"></span>
  </div>
  </form>

</div>

<script language="JavaScript">
<!--
document.mainform.btnSubmit.focus();
//-->
</script>
