<h2>Select quiz to edit</h2>

<ul>
<?php  
  $user_id = get_user_id();
  $result = exec_query("SELECT * FROM qmtbl_quizzes WHERE owner_user_id=$user_id");
  if (mysql_num_rows($result) == 0)
    echo '  <li><span class="qm_shadedText">(you have not created any quizzes yet)</span><br />';
  else {
    $k = 0;
    while ($row = mysql_fetch_assoc($result)) {
      $k++;
      $vars = Array();
      setVariable($vars, 'VAR_EVEN_OR_ODD'   , ($k%2==0)?'qm_odd':'qm_even'  );
      setVariable($vars, 'VAR_QUIZ_ID'       , $row['quiz_id']               );
      setVariable($vars, 'VAR_TITLE'         , $row['title']                 );
      filterWrite($vars, '  <li class="{VAR_EVEN_OR_ODD}">');
      filterWrite($vars, '    <a class="qmListMainLink" href="?action=edit/quiz_edit&quiz_id={VAR_QUIZ_ID}">{VAR_TITLE}</a>');
      if ($user_id == get_user_id())
        filterWrite($vars, '    <a class="qmListXtraLink" href="?action=edit/quiz_delete&quiz_id={VAR_QUIZ_ID}">DEL</a>');
      filterWrite($vars, '  </li><br />');
    }
  }
?>
</ul>

<hr/>

<form action="?action=edit/quiz_edit" method="post">

  <div class="qm_wideButton_list">
    <input type="submit" name="btnSubmit"  value="CREATE NEW QUIZ" class="qmWideButton" />
  </div>

</form>

