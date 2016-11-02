<?php

  require_once 'include/DB_function.php';
  $db = new DB_function();

  if(isset($_POST['id'])){
    $id = $_POST['id'];
    $db->deleteServico($id);
  }
?>
