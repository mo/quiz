<h1>QuizMaster - Main</h1>

<!-- Here we had to resort to tables for backwards compatibility.
     Unfortunately support for inline-block etc is too poor as of June 2005. -->
<table class="qm_menu_table">
<tr>
  <td><a href="?" class="qm_menu_item">Main</a></td>
<?php
if (!is_guest()) {
  echo '  <td><a href="?action=take/take_quiz" class="qm_menu_item">Take Quiz</a></td>' . "\n";
  echo '  <td><a href="?action=edit/quizes_edit&user_id=' . get_user_id() .
              '" class="qm_menu_item">Edit Quizzes</a></td>' . "\n";
  echo '  <td><a href="?action=profile" class="qm_menu_item">Profile</a></td>' . "\n";
}
?>
  <td><a href="?action=logout_exec" class="qm_menu_item">Logout</a></td>
</tr>
</table>

<div class="qmMainContainer">

