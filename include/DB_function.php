<?php
	class DB_function {

		private $conn;

		function __construct() {
			require_once 'DB_Connect.php';
			$db = new Db_Connect();
			$this->conn = $db->connect();
		}
		function __destruct() {

		}
		public function getPetshopByEmailAndPassword($email, $password) {
			$query = "SELECT * FROM tbPetshop WHERE email = '$email'";
			mysqli_query($this->conn, 'SET CHARACTER SET utf8');
			if ($dados = mysqli_query($this->conn, $query)) {
				$row = mysqli_fetch_array($dados);
				$hash = $this->checkhashSSHA($row['salt'], $password);
				if ($row['senha_encriptada'] == $hash) {
						mysqli_close($this->conn);
						return $row;
				}
			} else {
				mysqli_close($this->conn);
				return null;
			}
		}
		public function mudaSenha($userID, $newPassword){
			$db = new Db_Connect();
			$this->conn = $db->connect();
			if(empty($userID)||empty($newPassword)){
				return "Verifique seus dados!!";
			}else{
				$hash = $this->hashSSHA($newPassword);
				$senha_encriptada = $hash["encrypted"];
				$salt = $hash["salt"];
				$query = "update tbPetshop set senha_encriptada =  \"$senha_encriptada\", salt = \"$salt\" where id = \"$userID\"";
				$resul = mysqli_query($this->conn, $query);
				if(mysqli_affected_rows($this->conn) > 0){
					mysqli_query($this->conn, $query);
					return true;
				}else{
					return "0 results";
				}
			}
			mysqli_close($this->conn);
		}
		public function hashSSHA($password) {
			$salt = sha1(rand());
			$salt = substr($salt, 0, 10);
			$encrypted = base64_encode(sha1($password . $salt, true) . $salt);
			$hash = array("salt" => $salt, "encrypted" => $encrypted);
			return $hash;
		}

		public function checkhashSSHA($salt, $password) {
			$hash = base64_encode(sha1($password . $salt, true) . $salt);

			return $hash;
		}

		public function getInfoPetshop($userID, $valor, $table){
			$sql = "SELECT ".$valor." FROM ".$table. " WHERE id = \"$userID\"";
			$response = array();
			$response["rec"] = array();
			$response["rec"] ["cont"]= 0;
			$result = mysqli_query($this->conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_array($result)) {
					$response ["rec"] ["cont"]++;
					$tmp = array();
					$tmp["telefone"] = $row["telefone"];
					$tmp["nomeResponsavel"] = $row["nomeResponsavel"];
					$tmp["endereco"] = $row["endereco"];
					$tmp["nome"] = $row["nome"];
					$tmp["descricao"] = $row["descricao"];
					$tmp["horaAbertura"] = $row["horaAbertura"];
					$tmp["horaFechamento"] = $row["horaFechamento"];

					array_push($response["rec"], $tmp);
				}
			return $response["rec"];
			} else {
				return "0 results";
			}
			mysqli_close($this->conn);
		}

		public function setInfoPetshop($userID, $valorAlteracao, $alteracao){
			$sql = "UPDATE tbPetshop SET ".$valorAlteracao."= '".$alteracao."' WHERE id = \"$userID\"";
			$response ["rec"] ["cont"]++;
			$tmp = array();
			$response = array();
			$response["rec"] = array();
			$response["rec"] ["cont"]= 0;
			$result = mysqli_query($this->conn, $sql);
			$temp = mysqli_affected_rows($this->conn);

			array_push($response["rec"], $tmp);
			return $response["rec"];

			mysql_close($conn);
		}

		public function getNumAgendado($user){
			$sql = "SELECT COUNT(nomePetshop) AS total FROM tbAgendado WHERE nomePetshop = \"$user\"";
			$response = array();
			$response["rec"] = array();
			$response["rec"] ["cont"]= 0;
			$result = mysqli_query($this->conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_array($result)) {
					$response ["rec"] ["cont"]++;
					$tmp = array();
					$tmp["total"] = $row["total"];

					array_push($response["rec"], $tmp);
				}
				return $response["rec"];
			} else {
				return "0 results";
			}
			mysql_close($conn);
		}

		public function getPrecoDuracao($userID, $valor, $servico){
			$sql = "SELECT ".$valor." FROM tbServico WHERE petshopUID = ".$userID." AND nome = \"$servico\"";
			$response = array();
			$response["rec"] = array();
			$response["rec"] ["cont"]= 0;
			$result = mysqli_query($this->conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_array($result)) {
					$response ["rec"] ["cont"]++;
					$tmp = array();
					$tmp[$valor] = $row[$valor];

					array_push($response["rec"], $tmp);
				}
				return $response["rec"];
			} else {
				return "0 results";
			}
			mysql_close($conn);
		}

		public function setPreco($userID, $servico, $preco){
			$sql = "UPDATE tbServico SET preco = '".$preco."' WHERE nome = '".$servico."' AND petshopUID = \"$userID\"";
			$response ["rec"] ["cont"]++;
			$tmp = array();
			$response = array();
			$response["rec"] = array();
			$response["rec"] ["cont"]= 0;
			$result = mysqli_query($this->conn, $sql);
			$temp = mysqli_affected_rows($this->conn);

			array_push($response["rec"], $tmp);
			return $response["rec"];

			mysql_close($conn);
		}
		public function setDuracao($userID, $servico, $duracao){
			$sql = "UPDATE tbServico SET duracao = '".$duracao."' WHERE nome = '".$servico."' AND petshopUID = \"$userID\"";
			$response ["rec"] ["cont"]++;
			$tmp = array();
			$response = array();
			$response["rec"] = array();
			$response["rec"] ["cont"]= 0;
			$result = mysqli_query($this->conn, $sql);
			$temp = mysqli_affected_rows($conn);

			array_push($response["rec"], $tmp);
			return $response["rec"];

			mysql_close($conn);
		}
		public function naoServico($user, $servico){
			$sql = "UPDATE tbServico SET preco = NULL, duracao = NULL WHERE nome = '".$servico."' AND petshopUID = 1";
			$result = mysqli_query($this->conn, $sql);
			return mysqli_affected_rows($this->conn);

			mysql_close($this->conn);
		}

		public function agendado($nome){
			$query = "SELECT id, unique_index, dataAgendada, nomePetshop, nomeAnimal, servico, precoFinal, usuarioUID, confirmado, pagou, dataArmazenamento,case when servicoAdicional IS NULL or servicoAdicional = '' then 'empty' else servicoAdicional end as servicoAdicional,case when formaPagamento IS NULL or formaPagamento = '' then 'empty' else formaPagamento end as formaPagamento from tbAgendado WHERE nomePetshop = '".$nome."' ORDER BY dataAgendada DESC";
			mysqli_query($this->conn,'SET CHARACTER SET utf8');
			$result = mysqli_query($this->conn, $query);
			$tmp = array();
	    //create an array
	    $emparray = 0;
	    while($row=mysqli_fetch_assoc($result))
	    {
					$tmp["id"][$emparray]=$row["id"];
					$tmp["uid"][$emparray]=$row["unique_index"];
					$tmp["dataAgendada"][$emparray]=$row["dataAgendada"];
					$tmp["nomePetshop"][$emparray]=$row["nomePetshop"];
					$tmp["precoFinal"][$emparray]=$row["precoFinal"];
					$tmp["nomeAnimal"][$emparray]=$row["nomeAnimal"];
					$tmp["servicoAdicional"][$emparray]=$row["servicoAdicional"];
					$tmp["formaPagamento"][$emparray]=$row["formaPagamento"];
					$tmp["usuarioUID"][$emparray]=$row["usuarioUID"];
					$tmp["confirmado"][$emparray]=$row["confirmado"];
					$tmp["pagou"][$emparray]=$row["pagou"];
					$tmp["dataArmazenamento"][$emparray]=$row["dataArmazenamento"];
					$tmp["servico"][$emparray]=$row["servico"];
	        $emparray++;
	    }
			$tmp["n"]=$emparray;
			mysqli_close($this->conn);
	    return $tmp;
			}
			public function getServicos($unique){
				$query = "SELECT * FROM tbServico WHERE petshopUID='".$unique."'";
				mysqli_query($this->conn,'SET CHARACTER SET utf8');
				$result = mysqli_query($this->conn, $query);
				$tmp = array();
		    //create an array
		    $emparray = 0;
		    while($row=mysqli_fetch_assoc($result))
		    {
						$tmp["id"][$emparray]=$row["id"];
						$tmp["nome"][$emparray]=$row["nome"];
						$tmp["precoP"][$emparray]=$row["precoP"];
						$tmp["precoM"][$emparray]=$row["precoM"];
						$tmp["precoG"][$emparray]=$row["precoG"];
						$tmp["precoGG"][$emparray]=$row["precoGG"];
						$tmp["precoGato"][$emparray]=$row["precoGato"];
						$tmp["duracaoCao"][$emparray]=$row["duracaoCao"];
						$tmp["duracaoGato"][$emparray]=$row["duracaoGato"];
						$tmp["descricao"][$emparray]=$row["descricao"];
		        $emparray++;
		    }
				$tmp["n"]=$emparray;
				mysqli_close($this->conn);
		    return $tmp;
				}
			public function nome($UID){
				$query = "SELECT * FROM tbUsuario WHERE unique_index = '$UID'";
				mysqli_query($this->conn, 'SET CHARACTER SET utf8');
				if ($dados = mysqli_query($this->conn, $query)) {
					$row = mysqli_fetch_array($dados);
							mysqli_close($this->conn);
							return $row['nome'];
				} else {
					mysqli_close($this->conn);
					return null;
				}
			}
			public function deleteServico($id){
				$query = "DELETE FROM tbServico WHERE id=".$id;
				mysqli_query($this->conn, 'SET CHARACTER SET utf8');
				mysqli_query($this->conn, $query);
				mysqli_close($this->conn);
			}
			public function updateServico($id, $coluna, $valor){
				if(strcmp($coluna,"nome")==0 || strcmp($coluna,"descricao")==0){
					$query = "UPDATE tbServico SET ".$coluna."=\"".$valor."\" WHERE id=".$id;
				}else{
					$query = "UPDATE tbServico SET ".$coluna."=".$valor." WHERE id=".$id;
				}
				mysqli_query($this->conn, 'SET CHARACTER SET utf8');
				mysqli_query($this->conn, $query);
				mysqli_close($this->conn);
			}
			public function criarServico($nome, $precoP, $precoM, $precoG, $precoGG, $precoGato, $duracaoCao, $duracaoGato, $descricao, $UID){
				$query = "INSERT INTO tbServico(nome, precoP, precoM, precoG, precoGG, precoGato, duracaoCao, duracaoGato, descricao, petshopUID) VALUES(";
				$query .= "'".$nome."', ";
				$query .= $precoP.", ";
				$query .= $precoM.", ";
				$query .= $precoG.", ";
				$query .= $precoGG.", ";
				$query .= $precoGato.", ";
				$query .= $duracaoCao.", ";
				$query .= $duracaoGato.", ";
				$query .= "'".$descricao."', ";
				$query .= "'".$UID."')";
				mysqli_query($this->conn, 'SET CHARACTER SET utf8');
				if(mysqli_query($this->conn, $query)){
					mysqli_close($this->conn);
					return "Serviço criado com sucesso!";
				}else{
					mysqli_close($this->conn);
					return "FATAL ERROR! ".$query;
				}

			}
			public function numeroAgendados($nome){
					$query = "SELECT * FROM tbAgendado WHERE nomePetshop='".$nome."'";
					mysqli_query($this->conn,'SET CHARACTER SET utf8');
					$result = mysqli_query($this->conn, $query);
					$tmp = array();
			    //create an array
			    $emparray = 0;
			    while($row=mysqli_fetch_assoc($result))
			    {
			        $emparray++;
			    }
					mysqli_close($this->conn);
			    return $emparray;
			}
			public function getUserByUID($unique){
						$query = "SELECT * FROM tbUsuario WHERE unique_index='".$unique."'";
						mysqli_query($this->conn,'SET CHARACTER SET utf8');
						$result = mysqli_query($this->conn, $query);
						$row = mysqli_fetch_array($result);
								mysqli_close($this->conn);
								return $row;
			}
			public function pagar($id){
				$query = "UPDATE tbAgendado SET pagou=1 WHERE id=".$id;
				mysqli_query($this->conn, 'SET CHARACTER SET utf8');
				if(mysqli_query($this->conn, $query)){
					mysqli_close($this->conn);
					return "Alterado com sucesso (Pago)";
				}else{
					mysqli_close($this->conn);
					return "Erro (Pago)";
				}
			}
			public function confirmar($id){
				$query = "UPDATE tbAgendado SET confirmado=1 WHERE id=".$id;
				mysqli_query($this->conn, 'SET CHARACTER SET utf8');
				if(mysqli_query($this->conn, $query)){
					mysqli_close($this->conn);
					return "Confirmado com sucesso (Confirmar)";
				}else{
					mysqli_close($this->conn);
					return "Erro (Confirmar)";
				}
			}
	}
?>
