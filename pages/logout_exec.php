<?php

  setcookie(QM_COOKIE_NAME__PASSWORD, '', time() + 15);
  setcookie(QM_COOKIE_NAME__GUEST,    '0');

  header('Location: ?');

?>
