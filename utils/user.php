<?php

function password_given() {
  return isset($_COOKIE[QM_COOKIE_NAME__PASSWORD]) && $_COOKIE[QM_COOKIE_NAME__PASSWORD] != '';
}


function is_guest() {
  return isset($_COOKIE[QM_COOKIE_NAME__GUEST]) && $_COOKIE[QM_COOKIE_NAME__GUEST] == '1';
}


function canonical_username($username) {
  return strtolower(trim($username));
}


# GET USERNAME FROM CLIENT COOKIE
function get_client_username() {
  if (!isset($_COOKIE[QM_COOKIE_NAME__USERNAME]))
    return "";
  
  return $_COOKIE[QM_COOKIE_NAME__USERNAME];
}


# GET PASSWORD FROM CLIENT COOKIE
function get_client_password() {
  if (!isset($_COOKIE[QM_COOKIE_NAME__PASSWORD])) {
    return "";
  }
  
  return $_COOKIE[QM_COOKIE_NAME__USERNAME];
}

function get_user_id() {
  if (is_guest()) { return -1; }
  return get_user_id_for_username(get_client_username());
}

# RETURNS INTERNAL DATABASE TABLE ROW INDEX FOR CURRENT USER
function get_user_id_for_username($username) {
  # PREPROCESS INPUT
  $username = mysql_real_escape_string(canonical_username($username));

  # EXECUTE QUERY
  $result = exec_query("SELECT user_id FROM qmtbl_users WHERE username='$username'");
  if (mysql_num_rows($result) != 1)
    return -1;

  # RETRIEVE & RETURN RESULT 
  $row = mysql_fetch_assoc($result);
  return $row['user_id'];
}


function has_valid_userpass() {

  # GET USERNAME AND PASSWORD FROM CLIENT COOKIES
  if (!isset($_COOKIE[QM_COOKIE_NAME__USERNAME]))
  {
    return false;
  } else{
    $username = $_COOKIE[QM_COOKIE_NAME__USERNAME];
  }
  if (!isset($_COOKIE[QM_COOKIE_NAME__USERNAME]))
  {
    return false;
  } else{
    $password = $_COOKIE[QM_COOKIE_NAME__PASSWORD];
  }

  # PREPROCESS INPUT
  $username = mysql_real_escape_string(canonical_username($username));

  # EXECUTE QUERY
  $result = mysql_query("SELECT * FROM qmtbl_users WHERE username='$username'");
  if (mysql_num_rows($result) != 1)
    return false;
  
  # RETRIEVE & RETURN RESULT
  $row = mysql_fetch_assoc($result);
  return ($password == $row['password']);
}

?>
