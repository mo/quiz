<?php

function get_quiz_field($quiz_id, $field) {
  if (!isset($quiz_id)) return NULL;
  $quiz_id = mysql_real_escape_string($quiz_id);
  $field = mysql_real_escape_string($field);
  $result = exec_query("SELECT " . $field . " FROM qmtbl_quizes WHERE quiz_id=$quiz_id");
  if (mysql_num_rows($result) != 1)
    return NULL;
  $row = mysql_fetch_assoc($result);
  return $row[$field];
}

function get_question_field($question_id, $field) {
  if (!isset($question_id)) return NULL;
  $question_id = mysql_real_escape_string($question_id);
  $field = mysql_real_escape_string($field);
  $result = exec_query("SELECT " . $field . " FROM qmtbl_questions WHERE question_id=$question_id");
  if (mysql_num_rows($result) != 1)
    return NULL;
  $row = mysql_fetch_assoc($result);
  return $row[$field];
}

function get_wa_answer_field($answer_id, $field) {
  $answer_id = mysql_real_escape_string($answer_id);
  $field = mysql_real_escape_string($field);
  $result = exec_query("SELECT " . $field . " FROM qmtbl_wa_answers WHERE answer_id=$answer_id");
  if (mysql_num_rows($result) != 1)
    return NULL;
  $row = mysql_fetch_assoc($result);
  return $row[$field];
}

function get_mc_answer_field($answer_id, $field) {
  $answer_id = mysql_real_escape_string($answer_id);
  $field = mysql_real_escape_string($field);
  $result = exec_query("SELECT " . $field . " FROM qmtbl_mc_answers WHERE answer_id=$answer_id");
  if (mysql_num_rows($result) != 1)
    return NULL;
  $row = mysql_fetch_assoc($result);
  return $row[$field];
}

function possible_answer_count($question_id) {
  $answer_count = 0;

  # Check the qmtbl_wa_answers table
  $question_id = mysql_real_escape_string($question_id);
  $result = exec_query("SELECT * FROM qmtbl_wa_answers WHERE question_id=$question_id");
  $answer_count += mysql_num_rows($result);

  return $answer_count;
}

function get_question_count($quiz_id) {
  $question_count = 0;
  $quiz_id = mysql_real_escape_string($quiz_id);
  $result = exec_query("SELECT * FROM qmtbl_questions WHERE quiz_id=$quiz_id");
  $question_count += mysql_num_rows($result);
  return $question_count;
}

function execPreMatchingTransform($transforms, $text) {
  if (strpos($transforms, 'lowercase') !== FALSE) $text = strtolower($text);
  if (strpos($transforms, 'nowhitespace') !== FALSE) $text = stripAllWhitspace($text);
  if (strpos($transforms, 'noaccentuations') !== FALSE) $text = unAccentuatate($text);
  if (strpos($transforms, 'onlyalphanumeric') !== FALSE) $text = keepOnlyTheseChars($text, ALPHANUMERICALS);
  return $text;
}

?>
