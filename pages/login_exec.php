<?php

$btnSubmit = get_param('btnSubmit');
$form_username = get_param('form_username');
$form_password = get_param('form_password');

if (($btnSubmit !== 'LOGIN AS GUEST') and ($form_username == '' or $form_password == '')) {
  global $login_retry;
  $login_retry = true;
  serve_page('login_page.php');
}

#
# The variable $btnSubmit is equal to LOGIN if a non-guest logs in, and
# it's equal to LOGIN AS GUEST when that button (on the login form) is used.
# Also note that the create new account form also tries to automatically
# login a newly created user by serving this file directly; this is when
# the $btnSubmit variables is set to CREATE NEW USER (see second case below).
#
if ($btnSubmit == 'LOGIN AS GUEST') {
  
  setcookie(QM_COOKIE_NAME__GUEST, '1');

} else if ($btnSubmit == 'LOGIN' || $btnSubmit == 'CREATE NEW USER') {
  #
  # Note: We do NOT check the given login credentials for validity here.
  #       Instead all scripts will check their validity before serving a
  #       request, and when invalid login credentials are found the client
  #       is automatically refered back to the login page.
  #
  # Save login credentials in cookies. Password cookie expires after 1 week.
  # NULL expire means no expiration, and the final arg is in which dir the
  # cookie is set (since QM have script in many dirs they all put their cookies
  # in the domain root directory).
  #
  setcookie(QM_COOKIE_NAME__USERNAME, $form_username, time() + 60*60*24*14);
  setcookie(QM_COOKIE_NAME__PASSWORD, $form_password, time() + 60*60*12);
  setcookie(QM_COOKIE_NAME__GUEST,    '0',            time() + 60*60*24);
}

header('Location: ?');

?>