<?php global $admin_email; ?>

<h1 class="qm_headline">QuizMaster - Error</h1>

<p class="qm_bad"><span class="qmText">Error message:&nbsp;&nbsp;</span>
<?php echo $error_message ?></p>

<p class="qmText"><?php echo $error_explanation ?></p>

<p class="qmText">If necessary, you can contact the QuizMaster administrator at
<a href="mailto:<?php echo $admin_email ?>"><?php echo $admin_email ?></a>.</p>


