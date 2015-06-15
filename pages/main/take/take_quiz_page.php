<?php

  $user_id = get_param("user_id");
  if ($user_id == null)
    $user_id = get_user_id();


  echo '<h2>Select quiz to take</h2>';

  if ($user_id == get_user_id())
  {
    # When a user list their own quizzes we show both their public and private quizzes plus all other user's public quizzes.
    echo "<h3>Your quizzes</h3>\n";
    echo "<ul>\n";
    $user_id = mysql_real_escape_string($user_id);
    write_li_items_for_quizes("SELECT * FROM qmtbl_quizes WHERE owner_user_id='$user_id'", '(you have not created any quizzes yet, click the "Edit" menu item)');
    echo "</ul>\n";
    echo "<h3>Public quizzes shared by other users</h3>\n";
    echo "<ul>\n";
    write_li_items_for_quizes("SELECT * FROM qmtbl_quizes WHERE owner_user_id<>'$user_id' AND public_quiz=1", '(no one else are sharing any quizzes right now)');
    echo '</ul>';
  } else {
    # When listing other user's quizzes, list only public ones.
    echo '<h3>Quizzes shared by user "' . $user_id . '"</h3>' . "\n";
    echo "<ul>\n";
    # If someone passes in a username instead of a numerical user ID, just do the right thing:
    if (!is_numeric($user_id))
      $user_id = get_user_id_for_username($user_id);
    $user_id = mysql_real_escape_string($user_id);
    write_li_items_for_quizes("SELECT * FROM qmtbl_quizes WHERE owner_user_id='$user_id' AND public_quiz=1", '(this user is not sharing any quizzes right now)' . "SELECT * FROM qmtbl_quizes WHERE owner_user_id='$user_id' AND public_quiz=1");
    echo "</ul>\n";
  }


?>

</div>
