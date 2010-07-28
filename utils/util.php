<?php

# The PrintMarkupWithVariables() function prints a certain snippet
# of HTML markup while simultaneously replacing certain parts of the
# snippet with dynamically generated content (typically from a database).
# The idea behind this function is to avoid long concatenations of HTML
# and PHP variables because it's kind of hard to read stuff like:
#
#    // PHP and HTML mixed into an unreadable slurry
#    echo '<a href="?quiz_id=' . $row['quiz_id'] . '">' . $row['title'] . "</a>\n";
#
# This function doesn't solve the problem perfectly but atleast it makes
# the code a little bit easier to read imo. Typical use for this function is:
#
#  while ($row = mysql_fetch_assoc($result)) {
#     PrintMarkupWithVariables(
#         '<tr>'                                       . ENDL .
#         '  <td>BLAH</td>'                            . ENDL .
#         '  <td class="VAR_NAME_CLASS">VAR_NAME</td>' . ENDL .
#         '  <td>VAR_PHONE</td>'                       . ENDL .
#         '</tr>'                                      . ENDL
#       , array('VAR_NAME'        => $row['name'],
#               'VAR_NAME_CLASS'  => $someCssClassName;,
#               'VAR_PHONE'       => $row['phone'])
#       );
#  }
#
define("ENDL", "\n");
function PrintMarkupWithVariables($markup, $variables) {
  foreach ($variables as $variable => $value)
    $markup = str_replace($variable, $value, $markup);
  echo $markup;	
}

function checked($isChecked) {
  if ($isChecked)
    //return ' checked="checked"';
    return ' CHECKED';
  else
    return ''; 
}

function fetch_uploaded_image($image_name) {

  # WAS THERE A VALID IMAGE UPLOADED? --> FETCH FILE DATA
  if (isset($_FILES[$image_name]) &&
      $_FILES[$image_name]['size'] > 0 &&
      strpos($_FILES[$image_name]['type'], "image/") !== false &&
      is_uploaded_file($_FILES[$image_name]['tmp_name']) &&
      $_FILES[$image_name]['error'] == UPLOAD_ERR_OK) {
  
    #IS THE FILE LARGER THAN 16MB? --> NOT ALLOWED, SERVE ERROR
    if ($_FILES[$image_name]['size'] >= 16000000) {
      return NULL;
    }

    $userimage_file = fopen($_FILES[$image_name]['tmp_name'], "r");
    $userimage_data = fread($userimage_file, filesize($_FILES[$image_name]['tmp_name']));
    return array('image_mime' => $_FILES[$image_name]['type'], 
                 'image_data' => $userimage_data,
                 'image_size' => $_FILES[$image_name]['size']);
  
  } else {
    return NULL;
  }
}


#
# CHECKS IF MYSQL SERVER CAN HANDLE A PACKAGE OF SIZE $size
# AND TRIES TO ADJUST THIS SETTING IF POSSIBLE (typically requires root)
#
# RETURN VALUES:
# -2               means DB makes no sense and is acting all weird
# -1               means DB failure
# 0                means OK;     that it could handle packets of size $size
# N where N>=1     means ERRROR; that MySQL server could NOT handle packets of size $size,
#                                infact N was the largest packet the MySQL server would allow
#
function check_mysql_packet_size($size) {
  # SHOULD BE INTEGER, BUT WE'RE NOT TAKING ANY CHANCES
  $size = mysql_real_escape_string($size);
  # CHECK PACKET SIZE LIMITATION (max_allowed_packet)
  $result = exec_query("SELECT @@local.max_allowed_packet AS max_allowed_packet;");
  $row = mysql_fetch_assoc($result);
  if ($row['max_allowed_packet'] == 0) //If this actually happens, it would be just weird (TODO: explain in comment why this check is here)
    return -2;
  # WAS THE PACKET SIZE LIMITATION TO LOW FOR OUR NEEDS --> TRY TO SET A NEW LIMIT
  if ($row['max_allowed_packet'] <= $size) {
    $result = mysql_query("SET @@local.max_allowed_packet=$size;");
    if (!$result) {
      return $row['max_allowed_packet'];
    }
  }
  return 0; //All is OK, given packet size can be handled
}



?>