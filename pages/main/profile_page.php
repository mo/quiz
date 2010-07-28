<?php
  if (is_guest()) {
    echo('<p class="qmText">The guest account does not have a profile. If you register a personal account ');
    echo('you will be able to create you own quizes and see your user information plus statistics here!</p>');
    echo('<br><br><br><br><br><br><br><br>');
    die;
  }
  
  # PREPROCESS INPUT
  $username = mysql_real_escape_string(canonical_username(get_client_username()));
  
  # EXECUTE QUERY
  $result = exec_query("SELECT * FROM qmtbl_users WHERE username='$username'");
  $profile = mysql_fetch_assoc($result);

?>

<h2>Profile</h2>

<a href="?action=serve_user_image&username=<?php echo $_COOKIE[QM_COOKIE_NAME__USERNAME] ?>">
<img id="userImage" class="qmProfileImage" src="?action=serve_user_image&username=<?php echo $_COOKIE[QM_COOKIE_NAME__USERNAME] ?>">
</a>

  <label>Username</label>
  <div class="qmFormFieldBox">
    <span class="qm_shadedText"><?php echo stripslashes($profile['username']) ?></span>
  </div>

  <label>Real name</label>
  <div class="qmFormFieldBox">
    <span class="qm_shadedText"><?php echo stripslashes($profile['realname']) ?></span
  </div>
  
  <label>E-mail</label>
  <div class="qmFormFieldBox">
    <span class="qm_shadedText"><?php echo stripslashes($profile['email']) ?></span>
  </div>
  

