<?php
  
  $quiz_id = get_param('quiz_id');
  $question_id = get_param('question_id');
  
  $quiz_title = get_quiz_title($quiz_id);
  if ($quiz_title === false) {
    serve_error('Invalid quiz id, no such quiz.', 'You where redirected to, or deliberately ' .
                'browsed to, the edit script using an invalid quiz id.');
  }                        

?>

<p class="qmText">You are adding a new 'single written answer'-question to the quiz <?php echo "'<span class=\"qm_shadedText\">$quiz_title</span>'."; ?></p>

<form action="?action=doa_store_wa_question" method="post">
<input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
<input type="hidden" name="question_id" value="<?php echo $question_id; ?>">

<p class="qmText">Further for each answer, you will be able to provide a
prematching transform which allows you to regulate if the comparision with the
correct answer should be strict about accentuations and/or case-sensitive aso.
Press the ADD button below to specify a new correct answer for this question!</p>

<table class="qm_form_table">
  <tr>
    <td><div class="qm_label">Answer</div></td>
    <td><input name="form_answer" class="qm_edit" type="text" value=""></td>
  </tr>           
  <tr>
    <td><div class="qm_label">Prematching Transformations</div></td>
    <td>
    <input type="checkbox" name="form_transf_lowercase" value="1" checked><span class="qmText">Turn both into lowercase</span><br>
    <input type="checkbox" name="form_transf_nowhitespace" value="1" checked><span class="qmText">Remove all whitespace characters<br>
    <input type="checkbox" name="form_transf_noaccentuations" value="1"><span class="qmText">Remove accentuations</span><br>
    <input type="checkbox" name="form_transf_onlyalphanumeric" value="1"><span class="qmText">Strip all but alphanumerics</span><br>
    <p class="qmText">These prematching transformations are applied both to the correct and the given answer before they are compared. The purpose is to let the quiz creator regulate the comparison strictness.</p>
    </td>
  </tr>           
  <tr>
    <td><div class="qm_label">&nbsp;</div></td>
    <td><input name="btnSubmit" class="qm_button" type="submit" value="ADD ANSWER">
  </tr>           
</table>

</form>