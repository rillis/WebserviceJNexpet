<?php

require_once 'include/DB_function.php';
$db = new DB_function();

// json response array
$response = array("error" => FALSE);

if (!empty($_POST['email']) && !empty($_POST['password'])) {

    // receiving the post params
    $email = $_POST['email'];
    $password = $_POST['password'];

    // get the user by email and password
    $user = $db->getPetshopByEmailAndPassword($email, $password);

    if ($user != false) {
        // use is found
        $response["error"] = FALSE;
        $response["uid"] = $user["unique_index"];
        $response["user"]["nome"] = $user["nome"];
        $response["user"]["email"] = $user["email"];
        $response["user"]["senha_encriptada"] = $user["senha_encriptada"];
        $response["user"]["salt"] = $user["salt"];
        $response["user"]["endereco"] = $user["endereco"];
        $response["user"]["telefone"] = $user["telefone"];
        $response["user"]["nomeResponsavel"] = $user["nomeResponsavel"];
        $response["user"]["horaAbertura"] = $user["horaAbertura"];
        $response["user"]["horaFechamento"] = $user["horaFechamento"];
        $response["user"]["descricao"] = $user["descricao"];
        echo json_encode($response);
    } else {
        // user is not found with the credentials
        $response["error"] = TRUE;
        $response["error_msg"] = "Os dados estão incorretos. Por favor verifique!";
        echo json_encode($response);
    }
} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Email ou senha faltando!";
    echo json_encode($response);
}
?>
