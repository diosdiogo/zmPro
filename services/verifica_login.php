<?php
include 'conecta.php';
date_default_timezone_set('America/Bahia');

$array = json_decode(file_get_contents("php://input"), true);

//print_r($array);

$us_id = base64_decode($array['us_id']);
$pass = base64_decode($array['pass']);
$matriz = base64_decode($array['e']);
//echo $us_id . '<br>';
//echo $pass;
//echo $matriz;

$login = verificaLogin($conexao, $us_id, $pass, $matriz);

echo $login;
function verificaLogin($conexao, $us_id, $pass, $matriz) {

	$sql = "SELECT * from usuarios where us_empresa = $matriz and us_id = $us_id and us_senha='$pass';";

	$query = mysqli_query($conexao, $sql);

	$retorno = mysqli_num_rows($query);
	return mysqli_num_rows($query);
}
?>