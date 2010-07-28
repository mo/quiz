<?php

  # This page is opened with EITHER answer_id OR question_id provided as a parameter.
  # answer_id       (specified when editing existing answers)
  # question_id     (specified when creating a new answer, question_id specifies
  #                  to which question the new answer should be attached)
  $answer_id = get_param('answer_id');
  if ($answer_id != null) {
  	$answer_id    = get_param('answer_id');
  	$question_id  = get_wa_answer_field($answer_id, 'question_id');
  } else {
  	$answer_id    = -1;  	  	
  	$question_id  = get_param('question_id');
  }

  $lowercase = true;
  $nowhitespace = false;
  $noaccentuations = false;
  $onlyalphanumeric = false;
  if ($answer_id !== -1) {
    $answer = get_wa_answer_field($answer_id, 'answer');
    $transform = get_wa_answer_field($answer_id, 'preMatchingTransform');
    if (strpos($transform, 'lowercase') !== FALSE) $lowercase = true;
    if (strpos($transform, 'nowhitespace') !== FALSE) $nowhitespace = true;
    if (strpos($transform, 'noaccentuations') !== FALSE) $noaccentuations =  true;
    if (strpos($transform, 'onlyalphanumeric') !== FALSE) $onlyalphanumeric = true;
  } else {
    $answer = "";
  }
  $question       = get_question_field($question_id, 'question');
  $quiz_id        = get_question_field($question_id, 'quiz_id');
  $quiz_title     = get_quiz_field($quiz_id, 'title');

?>

<p class="qmText">This answer is attached to the question
<span class="qm_shadedText"><?php echo "'$question'"; ?></span>
which belongs to the quiz <span class="qm_shadedText"><?php echo "'$quiz_title'"; ?></span>.</p>

<form method="post" action="?action=edit/answer_wa_edit_exec" name="mainform">

  <input type="hidden" name="question_id" value="<?php echo $question_id; ?>">
  <input type="hidden" name="answer_id" value="<?php echo $answer_id; ?>">

  <div class="qmFormRow">
    <label for="form_answer">Answer</label>
    <input name="form_answer" type="text" value="<?php echo $answer ?>" class="qmFormText" autocomplete="off" />
  </div>
  
  <div class="qmFormRow">
    <label>Answer Matching <span class="qmText">(specifies now strict the specified answer has to be in order for it to be considered correct)</span></label>
	<div class="qmFormFieldBox">
	  
	  <div>
	    <input type="checkbox" name="form_lowercase"<?php echo checked($lowercase) ?> />
	    <label class="qmFormAltText" for="form_lowercase">Disregard UPPER/lower case</label>
	  </div>
	  
	  <div>
	    <input type="checkbox" name="form_nowhitespace"<?php echo checked($nowhitespace) ?> />
	    <label class="qmFormAltText" for="form_nowhitespace">Disregard all whitespace</label>
	  </div>
	    
	  <div>
        <input type="checkbox" name="form_noaccentuations"<?php echo checked($noaccentuations) ?> />
	    <label class="qmFormAltText" for="form_noaccentuations">Disregard accentuations (á &hArr; a)</label>
	  </div>
	    
	  <div>
	    <input type="checkbox" name="form_onlyalphanumeric"<?php echo checked($onlyalphanumeric) ?> />
        <label class="qmFormAltText" for="form_onlyalphanumeric">Disregard non-alphanumerics</label>
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

