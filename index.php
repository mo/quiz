<?php

  define("VERBOSE_ERROR_MESSAGES", true);

  include('utils/constants.php');
  include('utils/quizmaster.php');
  include('utils/database.php');
  include('utils/echo_filter.php');
  include('utils/user.php');
  include('utils/quiz.php');
  include('utils/util.php');

  # MAKE SURE NO MALICIOUS VARIABLES MAKE IT INTO THE CODE
  emulate_register_globals_off();
  
  # Undo "PHP magic_quotes_gpc" carnage if enabled (slow but can and should be fixed by turning of magic_quotes_gpc in PHP ini)
  undo_magic_quotes_gpc_if_enabled();

  $action = keepOnlyTheseChars(get_param('action'), "_/" . ALPHANUMERICALS);  
  while (substr($action, 0, 1) == '/') $action = substr($action, 1, strlen($action) - 1);

  # IS THIS DATA FROM FORM THAT DON'T REQUIRE AUTHORIZATION? --> EXECUTE HANDLER SCRIPT
  if ($action == 'setup_exec' || $action == 'login_exec' || $action == 'user_create_exec' ||
      $action == 'logout_exec' || $action == 'serve_user_image')
    run_script($action . ".php");

  # FIRST TIME SCRIPTS RUNS? --> QUIZMASTER WEB SETUP
  if (MYSQL_DATABASE == '' || $action == 'setup')
    serve_page('setup_page.php');
  
  connectToDatabase();
  createTablesIfNotExists();

  # 
  if ($action == 'user_create' || $action == 'status')
    serve_page($action . '_page.php');
  
  # ASKING FOR LOGIN PAGE? OR, NON-GUEST WITH NO PASSWORD? --> LOGIN FORM
  if ($action == 'login' || (!is_guest() && !password_given())) {
    serve_page('login_page.php');
  }
    
  # NO VALID LOGIN CREDENTIALS? --> TRY LOGIN AGAIN
  if (!is_guest() && !has_valid_userpass()) {
    $login_retry = true;
    serve_page('login_page.php');
  }

  # ARE WE RECEIVING AUTHORIZED-USERS-ONLY FORM DATA? --> CALL THE PROPER HANDLER SCRIPT
  if (substr($action, strlen($action)-5, 5) == '_exec')
    run_script("main/" . $action . ".php");

  if ($action != '')
    serve_main_page($action . "_page.php");
  else
    serve_main_page("main_page.php");

?>
