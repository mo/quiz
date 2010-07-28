<?php

  $user_id = get_param("user_id");
  if ($user_id == null)
    $user_id = get_user_id();

?>

<h2>Select quiz to take</h2>

<ul>  
<?php  
  $user_id = mysql_real_escape_string($user_id);
  if ($user_id == get_user_id()) { # users can see their own quizes (even private ones)
    $query = "SELECT * FROM qmtbl_quizes WHERE owner_user_id=$user_id";
  } else # A user can only see other another user's public quizes
    $query = "SELECT * FROM qmtbl_quizes WHERE owner_user_id=$user_id AND public_quiz=TRUE";
  $result = exec_query($query);
  if (mysql_num_rows($result) == 0)
    echo '<li><span class="qm_shadedText">(you have not created any quizes yet, click the "Edit Quizes" link)</span></li>';
  else {
    $k = 0;
    while ($row = mysql_fetch_assoc($result)) {
      $k++;
      $vars = Array();
      setVariable($vars, 'VAR_ODD_OR_EVEN', ($k%2==0)?'qm_odd':'qm_even');
      setVariable($vars, 'VAR_QUIZ_TITLE', $row['title']);
      setVariable($vars, 'VAR_QUIZ_ID', $row['quiz_id']);
      filterWrite($vars, '  <li class="{VAR_ODD_OR_EVEN}">');
      filterWrite($vars, '    <a class="qmListLongLink" href="?action=take/quiz_start&quiz_id={VAR_QUIZ_ID}">{VAR_QUIZ_TITLE}</a>');
      filterWrite($vars, '  </li><br />');      
    }
  }

?>
</ul>
  
</div>


