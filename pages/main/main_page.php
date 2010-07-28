<p class="qmText">
Welcome <?php
  echo ($_COOKIE[QM_COOKIE_NAME__GUEST] == '1')?'guest':$_COOKIE[QM_COOKIE_NAME__USERNAME]
?>
</p>
