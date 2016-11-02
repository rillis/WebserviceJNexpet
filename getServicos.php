<?php

  require_once 'include/DB_function.php';
  $db = new DB_function();
  $response = array("error" => FALSE);
  $response["vazio"] = TRUE;

  if(isset($_POST['id'])){
    $nome = $_POST['id'];
    $user = $db->getServicos($nome);
    //DEU
    if ($user != false) {
      $response["error"] = FALSE;
      $response["vazio"] = FALSE;
      $response["user"] = $user;
      echo json_encode($response);
    } else {
        // user is not found with the credentials
        $response["error"] = TRUE;
        $response["error_msg"] = "PetShop não encontrado!";
        echo json_encode($response);
    }
  }else{
    $response["error"] = TRUE;
    $response["error_msg"] = "Id faltando!";
    $response["vazio"] = TRUE;
    echo json_encode($response);
  }
?>
