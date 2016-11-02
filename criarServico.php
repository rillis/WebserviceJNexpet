<?php

  require_once 'include/DB_function.php';
  $db = new DB_function();

  if(isset($_POST['nome'])){
    $r = $db->criarServico($_POST['nome'], $_POST['precoP'], $_POST['precoM'],$_POST['precoG'],$_POST['precoGG'],$_POST['precoGato'],$_POST['duracaoCao'],$_POST['duracaoGato'],$_POST['descricao'],$_POST['UID']);
    echo $r;
  }
?>
