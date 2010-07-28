<h1 class="qm_headline">QuizMaster - Status</h1>

<p class="qmText">


upload_max_filesize==<?php echo ini_get("upload_max_filesize") ?>.
<br />

max_allowed_packet==<?php
  $result = exec_query("SELECT @@local.max_allowed_packet AS max_allowed_packet;");
  $row = mysql_fetch_assoc($result);
  echo $row['max_allowed_packet'];
?><br />

<?php
  $result = mysql_query("SET @@local.max_allowed_packet=15000000;");
  if (!$result)
    echo "failed";
?>


max_allowed_packet==<?php
  $result = exec_query("SELECT @@local.max_allowed_packet AS max_allowed_packet;");
  $row = mysql_fetch_assoc($result);
  echo $row['max_allowed_packet'];
?><br />
