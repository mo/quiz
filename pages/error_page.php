<?php global $admin_email; ?>

<h1 class="qm_headline">QuizMaster - Error</h1>

<p class="qm_bad"><span class="qmText">Error message:&nbsp;&nbsp;</span>
<?php echo $error_message ?></p>

<p class="qmText"><?php echo $error_explanation ?></p>

<p class="qmText">QuizMaster will automatically notify the quiz site administrator concerning this
particular problem, so hopefully it will be fixed shortly. However, if it does persists,
you can e-mail the administrator using this address 
<a href="mailto:<?php echo $admin_email ?>"><?php echo $admin_email ?></a>.</p>

<?php

  //TODO: mail to admin here
  
?>

                    
