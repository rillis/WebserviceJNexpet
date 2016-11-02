<?php

  require_once 'include/DB_function.php';
  $db = new DB_function();

  if(isset($_POST['nome'])){
    $r = $db->numeroAgendados($_POST['nome']);
    echo $r;
  }
?>
