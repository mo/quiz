<?php

  $quiz_id = get_param("quiz_id");
  $question_number = get_param("question");
  $quiz_order = get_param("quiz_order");

  # LOOKUP QUESTION COUNT
  $quiz_id = mysql_real_escape_string($quiz_id);
  $question_offset = mysql_real_escape_string($question_number - 1);
  $result = exec_query("SELECT COUNT(*) AS question_count FROM qmtbl_questions WHERE quiz_id=$quiz_id");
  $row = mysql_fetch_assoc($result);
  if (!$row) //question count for non-existing quiz
    serve_error("Invalid question reference", "The QuizMaster script was asked to start a quiz with question $question_number in the quiz with id $quiz_id but there was no such question. This could be due to a bug in QuizMaster, an old bookmark/link or an intentionally constructed invalid URL.");
  $question_count = $row['question_count'];
  if ($question_number < 1 || $question_number > $question_count) {
  	echo "finshed!";
  	die("");
  }

  # FETCH QUESTION DETAILS
  $quiz_order = mysql_real_escape_string($quiz_order);
  $result = exec_query("SELECT * FROM qmtbl_questions WHERE quiz_id=$quiz_id ORDER BY RAND($quiz_order) LIMIT 1 OFFSET $question_offset");
  $row = mysql_fetch_assoc($result);
  $question = $row['question'];
  $question_id = $row['question_id'];
  $question_type = $row['question_type'];

?>

<form name="mainform" action="?action=take/question_answered" method="post">

  <input type="hidden" name="question" value="<?php echo $question_number ?>" />
  <input type="hidden" name="quiz_id" value="<?php echo $quiz_id ?>" />
  <input type="hidden" name="quiz_order" value="<?php echo $quiz_order ?>" />

  <?php  if (get_question_field($question_id, 'image_id') != NULL) { ?>
  <div class="qmPreviewImageBox">
    <label>Image</label>
    <div class="qmFormFieldBox">
      <a href="?action=serve_question_image_exec&question_id=<?php echo $question_id?>">
        <img src="?action=serve_question_image_exec&question_id=<?php echo $question_id?>">
      </a>
    </div>
  </div>
  <?php } ?>

  <label>Question</label>
  <div class="qmFormFieldBox">
    <span class="qmText"><?php echo $question ?></span>
  </div>

  <?php if ($question_type === 'qt_written_answer') { ?>
    <label for="form_answer">Your Answer</label>
    <input name="form_answer" class="qmFormText" type="text" value="" autocomplete="off" />
  <?php } else if ($question_type === 'qt_multiple_choice') {
    echo '<div class="qmFormRow">';
    echo '<label>Select Your Answer</label>';
    # Order alternatives randomly but use a seed to make sure the random order is the same when re-trying
    # each question repeatedly (a new random ordering will be used the next time the quiz is restarted).
    $result = exec_query("SELECT * FROM qmtbl_mc_answers WHERE question_id=$question_id ORDER BY RAND(" . $quiz_order . ")");
    while ($row = mysql_fetch_assoc($result))
    {
      echo '<div class="qmFormFieldBox">';
      echo '  <input type="radio" name="form_answer" value="' . $row['answer'] . '" />';
      echo '  <label class="qmFormAltText">' . $row['answer'] . '</label>';
      echo '</div>';
    }
    echo '</div>';
  } else {
    serve_error("Invalid Question Type", "The database contains an invalid question type.");
  } ?>

  <div class="qmFormRow">
  <hr />
  </div>

  <div class="qm_wideButton_list">
    <input class="qmWideButton" type="submit" name="btnSubmit"  value="OK">
  </div>

</form>

<script language="JavaScript">
<!--
document.mainform.form_answer.focus();
//-->
</script>
