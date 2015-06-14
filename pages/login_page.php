<h1 class="qm_headline">QuizMaster - Login</h1>

<p class="qmText">Welcome to the quiz site. Here you can take different quizzes created by other members of
the quiz site, but you can also create your own quizzes, privately or publicly for anyone to take. Additionally,
the site assembles some basic statistics like the number of times each quiz has been taken etc. You are free to
use the quiz site for anything you like, general trivia, homework training or even as a survey tool.</p>

<p class="qmText">Enter your username and password in the text boxes below. If you are new to this site and is
not a registered user you may use this form to <a href="?action=user_create">create a new account</a>.

<p class="qmText">If you login as guest, by clicking the button at the bottom of this page, you will not be
able to create your own quizzes, you will only be able to take existing quizzes created by other users.</p>

<?php
  global $login_retry;
  if ($login_retry) {
    echo '<p class="qm_bad">Incorrect username and/or password. Access denied.</p>';
  }
?>

<form method="post" action="?action=login_exec">

  <div class="qmFormRow">
    <label for="form_username">Username</label>
    <input name="form_username" type="text" value="<?php echo(get_client_username()); ?>" class="qmFormText" />
  </div>

  <div class="qmFormRow">
    <label for="form_password">Password</label>
    <input name="form_password" type="password" value="<?php if (!$login_retry) echo(get_client_password()); ?>" class="qmFormPassword" />
  </div>

  <div class="qmFormRow">
    <input type="submit" name="btnSubmit" value="LOGIN" class="qmFormSubmit" />
  </div>

  <div class="qmFormRow">
    <label>...or you could also</label>
    <input type="submit" name="btnSubmit" value="LOGIN AS GUEST" class="qmFormSubmit" />
  </div>

</form>

