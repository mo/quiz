<?php 

  $quiz_id = get_param('quiz_id');
  $quiz_title = get_quiz_field($quiz_id, 'title');
  $quiz_availability = get_quiz_field($quiz_id, 'public_quiz');
  
  # If this new a new quiz with no database record
  # we use PRIVATE as the default alternative.
  if ($quiz_availability == null)
    $quiz_availability = "1";
  
  global $edit_quiz_error_message;

  if ($edit_quiz_error_message != '')
    echo "<p class=\"qm_bad\">$edit_quiz_error_message</p>";
?>

<form method="post" action="?action=edit/quiz_edit_exec" name="mainform">

  <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>" />

  <div class="qmFormRow">
    <label for="form_quiz_title">Quiz Title</label>
    <input type="text" name="form_quiz_title" value="<?php echo $quiz_title; ?>" class="qmFormText" />
  </div>

  <div class="qmFormRow">  
    <label for="form_quiz_availability">Quiz Availability</label>
    <div class="qmFormFieldBox">
      <div> 
        <input type="radio" name="form_quiz_availability" value="private"<?php echo checked($quiz_availability==1) ?> class="qmFormRadio" />
        <span class="qmFormAltText">Private, for personal use only</span>
      </div>
      <div>
        <input type="radio" name="form_quiz_availability" value="public"<?php echo checked($quiz_availability==0) ?> class="qmFormRadio" />
        <span class="qmFormAltText">Public, available for others</span>
      </div>
    </div>  
  </div>

<hr/>

  <div class="qmFormRow">
    <label>Quiz Questions <span class="qmText">(click title to edit)</span></label>
    <input type="submit" name="btnSubmit" value="ADD NEW QUESTION" class="qmFormSubmit" />
  </div>

  <div class="qmFormRow">
    <ul>
<?php
  # PREPARE QUERY  
  if ($quiz_id == NULL) $quiz_id = '-1'; // -1 is not a valid quiz index so no questions will be found.
  $quiz_id = mysql_real_escape_string($quiz_id);  
  $result = exec_query("SELECT * FROM qmtbl_questions WHERE quiz_id=$quiz_id");
  # DISPLAY RESULTS  
  if (mysql_num_rows($result) == 0) {
    echo '  <li><span class="qm_form_field"><span class="qm_shadedText">(this quiz contains no questions yet)</span></span></li>';
  } else {
    $k = 0;
    while ($row = mysql_fetch_assoc($result)) {
      $k++;
      $vars = Array();
      setVariable($vars, 'VAR_EVEN_OR_ODD'   , ($k%2==0)?'qm_odd':'qm_even'  );
      setVariable($vars, 'VAR_QUIZ_ID'       , $row['quiz_id']               );
      setVariable($vars, 'VAR_QUESTION_ID'   , $row['question_id']           );
      setVariable($vars, 'VAR_QUESTION'      , $row['question']              );
      filterWrite($vars, '      <li class="{VAR_EVEN_OR_ODD}">');
      filterWrite($vars, '        <a class="qmListMainLink" href="?action=edit/question_edit&quiz_id={VAR_QUIZ_ID}&question_id={VAR_QUESTION_ID}">{VAR_QUESTION}</a>');
      filterWrite($vars, '        <a class="qmListXtraLink" href="?action=edit/question_delete&question_id={VAR_QUESTION_ID}">DEL</a>');
      filterWrite($vars, '      </li>');
    }
  }
?>
    </ul>
  </div>

  <hr/>

  <div class="qmFormRow">
    <input type="submit" name="btnSubmit" value="SAVE AND CLOSE QUIZ" class="qmFormSubmit" />
  </div>
  
</form>

<script language="JavaScript">
<!--
document.mainform.form_quiz_title.focus();
//-->
</script>