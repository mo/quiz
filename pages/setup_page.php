<h1 class="qm_headline">QuizMaster - Setup</h1>

<p class="qmText">QuizMaster needs access to a MySQL database to store quiz data, scores etc, 
   but the MySQL database host/login has not been configured yet. You don't have to create any
   tables in the database, just tell QuizMaster where it is; once given access to the database 
   QuizMaster will install itself properly. The database login credentials will be stored 
   inside the file quizmaster/config/config.php so that QuizMaster can login to the database
   later on without the need to repeat this setup stage.</p>
<p class="qmText">QuizMaster has a lot of other things you can doodle with in order to make your 
   installation more personal, for instance themes and so on, however only the first four fields
   are mandatory.</p>
<?php
  if (isset($GLOBALS['error_message'])) {
    echo "<p class=\"qm_bad\">" . $GLOBALS['error_message'] . "</p>";
  }
  

  # If the form values are already set, reuse existing values. Otherwise, use the 
  # values specified in SETTINGS_FILE (or default values if there is no such file)
  # The form values will already be set, for instance if this a theme preview form
  # or if the form was submitted with invalid data (and therefore now is being
  # redisplayed with a little red text on top saying which data was invalid).
  if (!isset($form_mysql_hostname)) $form_mysql_hostname = MYSQL_HOSTNAME;
  if (!isset($form_mysql_database)) $form_mysql_database = MYSQL_DATABASE;
  if (!isset($form_mysql_username)) $form_mysql_username = MYSQL_USERNAME;
  if (!isset($form_mysql_password)) $form_mysql_password = MYSQL_PASSWORD;
  if (!isset($form_admin_email)) $form_admin_email = ADMIN_EMAIL;
    
?>

<form method="post" action="?action=setup_exec">

  <div class="qmFormRow">
    <label for="form_mysql_hostname">MySQL Hostname</label>
    <input name="form_mysql_hostname" type="text" value="<?php echo $form_mysql_hostname ?>" class="qmFormText"/>
  </div>
  
  <div class="qmFormRow">
    <label for="form_mysql_database">MySQL Database</label>
    <input name="form_mysql_database" type="text" value="<?php echo $form_mysql_database ?>" class="qmFormText" />
  </div>

  <div class="qmFormRow">
    <label for="form_mysql_username">MySQL Username</label>
    <input name="form_mysql_username" type="text" value="<?php echo $form_mysql_username ?>" class="qmFormText"/>
  </div>
  
  <div class="qmFormRow">
    <label for="form_mysql_password">MySQL Password</label>
    <input name="form_mysql_password" type="password" value="<?php echo $form_mysql_password ?>" class="qmFormText" />
  </div>

<hr/>

  <div class="qmFormRow">
    <label for="form_theme">QuizMaster Theme</label>
    <select name="form_theme" class="qmFormSelect">
<?php
  if ($handle = opendir(THEMES_DIRECTORY)) {
    while (false !== ($file = readdir($handle))) {
      if (substr($file, strlen($file) - 4, 4) == ".css") {
        $file = substr($file, 0, strlen($file) - 4);
        echo('      <option' . (($file == get_current_theme())?' selected':'') . '>' .
             $file . '</option>' . "\n");
      }  
    } 
    closedir($handle);
   }              
?>
    </select>
  </div>
  
  <div class="qmFormRow">
    <input type="submit" name="btnSubmit" value="PREVIEW THEME" class="qmFormSubmit" />
  </div>

<hr/>

  <div class="qmFormRow">
    <label for="form_admin_email">Admin E-mail</label>
    <input name="form_admin_email" type="text" value="<?php echo $form_admin_email ?>" class="qmFormText" />
  </div>

<hr/>

  <div class="qmFormRow">
    <input type="submit" name="btnSubmit" value="SAVE SETUP" class="qmFormSubmit" />
  </div>
  
</form>
