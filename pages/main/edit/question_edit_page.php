<?php
  
  global $edit_question_error_message;
  
  $question_id = get_param('question_id');
  if ($question_id == NULL)
    $quiz_id = get_param('quiz_id');
  else
    $quiz_id = get_question_field($question_id, 'quiz_id');
  
  $question_type = get_question_field($question_id, 'question_type');
  if ($question_type == NULL)
    $question_type = 'qt_written_answer';
  
  $quiz_title = get_quiz_field($quiz_id, 'title');
  if ($quiz_title === false) {
    serve_error('Invalid quiz id, no such quiz.', 'You where redirected to, or deliberately ' .
                'browsed to, the edit script using an invalid quiz id.');
  }                        

  if ($edit_question_error_message != '') {
    echo('<p class="qm_bad">' . $edit_question_error_message . '</p>');
  }
?>

<p class="qmText">This question belongs to the quiz <?php echo "'<span class=\"qm_shadedText\">$quiz_title</span>'."; ?></p>

<form action="?action=edit/question_edit_exec" method="post" enctype="multipart/form-data" name="mainform">

  <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
  <input type="hidden" name="question_id" value="<?php echo $question_id; ?>">

  <div class="qmFormRow">
    <label for="form_question">Question</label>
    <input name="form_question" type="text" value="<?php echo get_question_field($question_id, 'question'); ?>" class="qmFormText" autocomplete="off" />
  </div>

<hr/>

  <div class="qmFormRow">
    <label for="form_question_type">Question Type <span class="qmText">(changing removes existing answers)</span></label>
    <div class="qmFormFieldBox">
      <div>
        <input type="radio" name="form_question_type" value="qt_written_answer"<?php echo checked($question_type=='qt_written_answer') ?> />
        <label class="qmFormAltText">Written answer question</label>
      </div>
      <div>
        <input type="radio" name="form_question_type" value="qt_multiple_choice"<?php echo checked($question_type=='qt_multiple_choice') ?> />
        <label class="qmFormAltText">Multiple choice question</label>
      </div>  
    </div>
  </div>

  <p class="qmText">Even questions which asks for a written answer can have one or more possible answers, for instance
  if a question asks for the country which capital is London it is perfectly reasonable
  to answer both 'United Kingdom' and 'Great Britain'.</p>

  <div class="qmFormRow">
    <label>Question Answers <span class="qmText">(click title to edit)</span></label>
    
    <ul>
<?php
if ($question_type == 'qt_written_answer') {
  $edit_action = "edit/answer_wa_edit";
  $result = exec_query("SELECT * FROM qmtbl_wa_answers WHERE question_id='$question_id'");
} else if ($question_type = 'qt_multiple_choice') {
  $edit_action = "edit/answer_mc_edit";
  $result = exec_query("SELECT * FROM qmtbl_mc_answers WHERE question_id='$question_id'");
}
if (mysql_num_rows($result) == 0) {
  echo '      <li><span class="qm_shadedText">(no answers added yet)</span></li>';
} else { 
  $k = 0;
  while ($row = mysql_fetch_assoc($result)) {
    $k++;
    $vars = Array();
    setVariable($vars, 'VAR_EVEN_OR_ODD'   , ($k%2==0)?'qm_odd':'qm_even'  );
    setVariable($vars, 'VAR_ANSWER_ID'     , $row['answer_id']             );
    setVariable($vars, 'VAR_QUESTION_ID'   , $row['question_id']           );
    setVariable($vars, 'VAR_ANSWER'        , $row['answer']                );
    setVariable($vars, 'VAR_EDIT_ACTION'   , $edit_action                  );    
    filterWrite($vars, '      <li class="{VAR_EVEN_OR_ODD}">');
    filterWrite($vars, '        <a href="?action={VAR_EDIT_ACTION}&answer_id={VAR_ANSWER_ID}" class="qmListMainLink">{VAR_ANSWER}</a>');
    filterWrite($vars, '        <a href="?action=edit/answer_delete&question_id={VAR_QUESTION_ID}&answer_id={VAR_ANSWER_ID}" class="qmListXtraLink">DEL</a>');
    filterWrite($vars, '      </li>');
  }
}
?>
    </ul>
  </div>    

  <div class="qmFormRow">
    <input name="btnSubmit" type="submit" value="ADD ANSWER" class="qmFormSubmit" />
  </div>
  
<hr/>

  <?php  if (isset($question_id) && get_question_field($question_id, 'image_id') != NULL) { ?>
  <div class="qmFormRow">
    <label>Current Image</label>
    <div class="qmPreviewImageBox">    
      <img src="?action=serve_question_image_exec&question_id=<?php echo $question_id?>">
    </div>
  </div>
  
  <?php } else echo '<span class="qm_shadedText">(no image provided yet)</span>'; ?>
  
  <div class="qmFormRow">
    <label for="form_label">Set Image <span class="qmText">(optional)</span></label>
        <!-- A maximal size of 15MB images are allowed, this soft limit
             can be circumvented but it does not matter since PHP specifies
             another limit in php.ini which is hard plus usually a lot smaller. -->
    <input type="hidden" name="MAX_FILE_SIZE" value="15000000">
    <input type="file" name="form_question_image" class="qmFormFile" value="BROWSE">
  </div>
  
<hr/>

  <div class="qmFormRow">
    <label for="form_comment">Answer Comment <span class="qmText">(optional, displayed next to the correct answer)</span></label>
    <textarea name="form_comment" class="qmFormTextarea"><?php echo get_question_field($question_id, 'comment'); ?></textarea>
  </div>
  
<hr/>

  <div class="qmFormRow">
    <input name="btnSubmit" class="qmFormSubmit" type="submit" value="CLOSE" />
  </div>

</form>

<script language="JavaScript">
<!--
document.mainform.form_question.focus();
//-->
</script>
