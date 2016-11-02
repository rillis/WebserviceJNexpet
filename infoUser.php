<?php

  require_once 'include/DB_function.php';
  $db = new DB_function();
  $response = array("error" => FALSE);
  $response["vazio"] = TRUE;

  if(isset($_POST['uid'])){
    $uid = $_POST['uid'];
    $user = $db->getUserByUID($uid);
    //DEU
      $response["error"] = FALSE;
      $response["vazio"] = FALSE;
      $response["nome"] = $user["nome"];
      $response["email"] = $user["email"];
      $response["sexo"] = $user["sexo"];
      $response["telefone"] = $user["telefone"];
      $response["celular"] = $user["celular"];
      $response["endereco"] = $user["endereco"];
      $response["complemento"] = $user["complemento"];
      $response["bairro"] = $user["bairro"];
      echo json_encode($response);
  }else{
    $response["error"] = TRUE;
    $response["error_msg"] = "UId faltando!";
    $response["vazio"] = TRUE;
    echo json_encode($response);
  }
?>
