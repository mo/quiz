<?php 
  $form_username = get_param('form_username');
  $form_password = get_param('form_password');
  $form_password_again = get_param('form_password_again');
  $form_email = get_param('form_email');
  $form_realname = get_param('form_realname');
?>

<h1 class="qm_headline">QuizMaster - Create User</h1>

<p class="qmText">QuizMaster registration is free of charge. You may use the quizes for anything you 
like, general trivia or homework training. The quizes you create can be made public or stay private.
However, keep in mind that the quiz site administrator will ultimately decide what type of material
is suitable for the site, spam etc will be deleted with no prior notice.</p>

<?php
  global $user_create_error_message;
  if ($user_create_error_message != '')
    echo('<p class="qm_bad">' . $user_create_error_message . '</p>');
?>

<form method="post" action="?action=user_create_exec" enctype="multipart/form-data">
  
  <div class="qmFormRow">
    <label for="form_username">Username</label>
    <input name="form_username" type="text" value="<?php echo($form_username); ?>" class="qmFormText" />
  </div>
  
  <div class="qmFormRow">
    <label for="form_password">Password</label>
    <input name="form_password" type="password" value="<?php echo($form_password); ?>" class="qmFormPassword" />
  </div>

  <div class="qmFormRow">
    <label for="form_password">Password <span class="qmText">(again)</span></label>
    <input name="form_password_again" type="password" value="<?php echo($form_password_again); ?>" class="qmFormPassword" />
  </div>
  
  <hr/>

  <p class="qmText">Note that providing an e-mail is optional and if you choose you provide
  an e-mail it will not be shared with any third party and it will certainly not be published
  anywhere on the site. The advantage of providing an e-mail is that if you forget your password
  you will be able to have it e-mailed you your account and you can also choose to receive certain
  e-mail alerts, for instance when new quizes are created etc.</p>

  <div class="qmFormRow">
    <label for="form_email">E-mail <span class="qmText">(optional)</span></label>
    <input name="form_email" type="text" value="<?php echo($form_email); ?>" class="qmFormText" />
  </div>

  <div class="qmFormRow">
    <label for="form_realname">Real name <span class="qmText">(optional)</span></label>
    <input name="form_realname" type="text" value="<?php echo($form_realname); ?>" class="qmFormText" />
  </div>

  <div class="qmFormRow">
    <label for="form_user_image">Image <span class="qmText">(optional)</span></label>
    <input name="form_user_image" type="file" value="BROWSE" class="qmFormFile" />
  </div>

  <hr/>

  <div class="qmFormRow">
    <input type="submit" name="btnSubmit" value="CREATE NEW USER" class="qmFormSubmit"/>
  </div>

</form>


<!-- The reason why these two forms are split into two separate is because when the
     file uploaded is really large is tends to make the other post parameters disappear.
     Thus if you open the create user form, select a 20mb image file (ie larger
     than upload_max_filesize) and then press CANCEL you will not be returned to
     the login prompt if these forms are the same one. -->
<form method="post" action="?action=user_create_exec" enctype="multipart/form-data">
  <div class="qmFormRow">
    <input type="submit" name="btnSubmit" value="CANCEL" class="qmFormSubmit"/>
  </div>

</form>
