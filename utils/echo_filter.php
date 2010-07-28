<?php


/*

  TODO:
  Calls like this can mess things up:
  
    $filter->setVariable('VAR_ANSWER'        , $row['answer']                );
    $filter->setVariable('VAR_ANSWER_ID'     , $row['answer_id']             );
 
  The class needs to detect these and handle it (by rearranging variable order?).
  I.E. the problem is that VAR_ANSWER is contain inside VAR_ANSWER_ID
 
 */
  
function setVariable(&$vars, $variable, $value) {
  $vars[$variable] = $value;
}
   
function filterWrite($vars, $markup) {
  foreach ($vars as $variable => $value)
    $markup = str_replace('{' . $variable . '}', $value, $markup);
  echo $markup . "\n";	
}


?>