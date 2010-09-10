<?php

  $quiz_id         = get_param("quiz_id");
  $question_number = get_param("question");
  $answer          = get_param("form_answer");
  $quiz_order      = get_param("quiz_order");

  # GET QUESTION DETAILS
  $quiz_id         = mysql_real_escape_string($quiz_id);
  $question_offset = mysql_real_escape_string($question_number - 1);
  $quiz_order      = mysql_real_escape_string($quiz_order);
  $result          = exec_query("SELECT * FROM qmtbl_questions WHERE quiz_id=$quiz_id ORDER BY RAND($quiz_order) LIMIT 1 OFFSET $question_offset");
  $row             = mysql_fetch_assoc($result);
  $question_id     = $row['question_id'];
  $question        = $row['question'];
  $question_type   = $row['question_type'];
  $comment         = $row['comment'];

  if ($question_type === "qt_written_answer")
  {
    # DOES PROVIDED "WRITTEN ANSWER" MATCH ANY OF THE LISTED "CORRECT ANSWERS" ?
    $result = exec_query("SELECT * FROM qmtbl_wa_answers WHERE question_id=$question_id");
    $isCorrectAnswer = false;
    while ($row = mysql_fetch_assoc($result))
    {
      $postTransformUserAnswer = execPreMatchingTransform($row['preMatchingTransform'], $answer);
      $postTransformCorrectAnswer = execPreMatchingTransform($row['preMatchingTransform'], $row['answer']);
      if ($postTransformUserAnswer === $postTransformCorrectAnswer)
      {
        $isCorrectAnswer = true;
        break;
      }
    }
  } else {
    # DOES SELECTED "ANSWER ALTERNATIVE" MATCH ANY OF THE LISTED "CORRECT ALTERNATIVES" ?
    $answer = mysql_real_escape_string($answer);
    $result = exec_query("SELECT * FROM qmtbl_mc_answers WHERE question_id=$question_id AND answer='$answer' AND is_correct=1");
    $isCorrectAnswer = mysql_fetch_assoc($result) !== FALSE;
  }
?>

  <label>Question</label>
  <div class="qmFormFieldBox"><span class="qmText"><?php echo $question ?></span></div>

  <label>Your Answer</label>
  <div class="qmFormFieldBox"><span class="qmText"><?php echo $answer ?></span></div>

  <label>Result</label>
  <div class="qmFormFieldBox"><span class="qmText"><?php echo $isCorrectAnswer?'CORRECT!':'Incorrect' ?></span></div>

  <?php if ($isCorrectAnswer && $comment != NULL) { ?>
  <label>Comment</label>
  <div class="qmFormFieldBox"><span class="qmText"><?php echo $comment ?></span></div>
  <?php } ?>

  <?php if (!$isCorrectAnswer) { ?>
  <form name="retryform" action="?action=take/question_query" method="post">

    <input type="hidden" name="quiz_id" value="<?php echo $quiz_id ?>" />
    <input type="hidden" name="question" value="<?php echo $question_number ?>" />
    <input type="hidden" name="quiz_order" value="<?php echo $quiz_order ?>" />

    <div class="qm_wideButton_list">
      <input class="qmWideButton" type="submit" name="btnRetry"  value="TRY AGAIN">
    </div>

  </form>
  <?php } ?>

  <form name="mainform" action="?action=take/question_query" method="post">

    <input type="hidden" name="quiz_id" value="<?php echo $quiz_id ?>" />
    <input type="hidden" name="question" value="<?php echo $question_number+1 ?>" />
    <input type="hidden" name="quiz_order" value="<?php echo $quiz_order ?>" />

    <div class="qm_wideButton_list">
      <input class="qmWideButton" type="submit" name="btnSubmit"  value="NEXT QUESTION">
    </div>

  </form>

</div>

<script language="JavaScript">
<!--
<?php
  if (!$isCorrectAnswer)
    echo("document.retryform.btnRetry.focus();");
  else
    echo("document.mainform.btnSubmit.focus();");
?>
//-->
</script>
