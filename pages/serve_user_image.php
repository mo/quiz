<?php

  # This script outputs an image so we can't easily print an error message.
  # Maybe it would be a good idea to generate an error image instead though.

  connectToDatabase();
  createTablesIfNotExists();

  $username = mysql_real_escape_string(canonical_username(get_param('username')));
  $result = exec_query("SELECT image_data, image_mime FROM qmtbl_users WHERE username='$username'");
  if (mysql_num_rows($result) == 0)
    serve_error("QuizMaster could not find the requested image.",
                "There was no user found in the database with the specified username.");
  $row = mysql_fetch_assoc($result);

  header("Content-Type: " . $row['image_mime']);
  echo $row['image_data'];

?>
