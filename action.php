<?php

  require_once 'include/DB_function.php';
  $db = new DB_function();

  if(isset($_POST['action'])){
    if(strcmp($_POST['action'],"p")==0){
      //pagou
      $r = $db->pagar($_POST['id']);
      echo $r;
    }else if(strcmp($_POST['action'],"c")==0){
      //confirmou
      $r = $db->confirmar($_POST['id']);
      echo $r;
    }
  }
?>
