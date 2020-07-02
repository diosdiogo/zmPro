<?php

require_once 'conecta.php';
include 'log.php';
date_default_timezone_set('America/Bahia');

if(isset($_GET['empresa_matriz'])){
	$empresaMatriz = base64_decode($_GET['empresa_matriz']);
}
if (isset($_GET['empresa_filial'])) {
	$empresaAcesso = base64_decode($_GET['empresa_filial']);	
}

if (isset($_GET['token'])) {
	$token =  $_GET['token'];
}
if(isset($_GET['us_id'])){
	$us_id = base64_decode($_GET['us_id']);
}
if(isset($_GET['nomeEmp'])){
	$em_razao = $_GET['nomeEmp'];
}

if(isset($_GET['us'])){
	$us = base64_decode($_GET['us']);
}
if(isset($_GET['status'])){
	$status = $_GET['status'];
}



$ip = get_client_ip();

$data = date('Y-m-d');
$hora = date('H:i:s');

/*
echo "Empresa Matriz: " . $empresaMatriz . "<br>";
echo "Empresa empresaAcesso: " . $empresaAcesso . "<br>";
echo "Usuário: " . $us_id . "<br>";
echo "Status: " . $status . "<br>";
 */
if (isset($_GET['verificaStatus'])) {

	$verificaStatus = '{"result":[' . json_encode(verificaStatus($conexao, $token)) . ']}';
	/*
		if ($verificaStatus['em_aberto'] == 'N') {

			$status = array('status' => 'Fechado',
							'dataAbertura'
							'statusCheck' => false);

		} else if ($verificaStatus['em_aberto'] == 'S') {
			$status = array('status' => 'Aberto',
				'statusCheck' => true);

		}

		$statusReturn = '{"result":[' . json_encode($status) . ']}';
	*/

	echo $verificaStatus;
}

if (isset($_GET['mudarStatusEmp'])) {
	if ($status == 'Aberto') {
		$status = 'N';
	} else if ($status == 'Fechado') {
		$status = 'S';
	}
	$verificaPedidosAbertos = verificaPedidosAbertos($conexao, $empresaMatriz, $empresaAcesso, $status, $data, $hora, $ip, $us, $em_razao);
	//$mudarStatus = mudarStatus($conexao, $empresaMatriz, $empresaAcesso, $status, $data, $hora, $ip, $us, $em_razao);
}

if (isset($_GET['gerarTokenEmp'])) {
	
	/*$better_token = md5(uniqid(rand(), true));
	echo $better_token;
*/	
	$emp = $_GET['empresa'];
	$guidCriete = str_replace('-','',com_create_guid());
	$guid = str_replace('{','',$guidCriete);
	$guid = str_replace('}','',$guid);
	echo $guid;
	
	$sql = "update empresas set em_token='$guid' where em_cod = $emp;";
	$query = mysqli_query($conexao, $sql);
	
}

function verificaStatus($conexao, $token) {

	$resultado = array();
	$sql = "SELECT em_aberto, em_data_abertura, em_hora_abertura FROM zmpro.empresas where em_token= '$token';";
	$query = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($resultado, array(
			'em_aberto' => utf8_encode(statusAbertoFechado($row['em_aberto'])),
			'em_data_abertura' => utf8_encode($row['em_data_abertura']),
			'em_hora_abertura' => utf8_encode($row['em_hora_abertura']),
			'statusCheck' => statusCheck(utf8_encode($row['em_aberto'])),

		));
	}
	//echo $sql;
	return $resultado;

}

function statusAbertoFechado($status) {

	if ($status == 'N') {

		$status = 'Fechado';

	} else if ($status == 'S') {
		$status = 'Aberto';

	}

	//echo $status;
	return $status;
}
function statusCheck($status) {

	if ($status == 'N') {

		$status = false;

	} else if ($status == 'S') {
		$status = true;

	}

	//echo $status;
	return $status;
}

function verificaPedidosAbertos($conexao, $empresaMatriz, $empresaAcesso, $status, $data, $hora, $ip, $us, $em_razao){
	$sql ="SELECT * FROM zmpro.pedido_food where ped_matriz = $empresaMatriz and ped_empresa = $empresaAcesso and ped_status= 'Novo';";

	$query = mysqli_query($conexao, $sql);

	$row = mysqli_num_rows($query);

	if($row === 0){
		mudarStatus($conexao, $empresaMatriz, $empresaAcesso, $status, $data, $hora, $ip, $us, $em_razao);
	}
	else if($row != 0){
		echo 2;
	}

	//return $row;
}

function mudarStatus($conexao, $empresaMatriz, $empresaAcesso, $status, $data, $hora, $ip, $us, $em_razao) {

	$sql = "update empresas set em_aberto='$status', em_data_abertura='$data', em_hora_abertura='$hora' where em_cod_matriz = $empresaMatriz and em_cod = $empresaAcesso;";

	$query = mysqli_query($conexao, $sql);

	//echo $sql;
	if (mysqli_affected_rows($conexao) <= 0) {
		echo 0;
	} else {
		echo 1;
		if ($status == 'S') {
			$statusFull = 'Aberta';
		} else if ($status == 'N') {
			$statusFull = 'Fechada';
		}
		logSistema($conexao, $data, $hora, $ip, $us, 'Empresa ' . $statusFull . ' - ' . $em_razao . '', $empresaAcesso, $empresaMatriz);
	}
}

function get_client_ip() {
	$ipaddress = '';
	if (isset($_SERVER['HTTP_CLIENT_IP'])) {
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	} else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	} else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	} else if (isset($_SERVER['HTTP_FORWARDED'])) {
		$ipaddress = $_SERVER['HTTP_FORWARDED'];
	} else if (isset($_SERVER['REMOTE_ADDR'])) {
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	} else {
		$ipaddress = 'UNKNOWN';
	}

	return $ipaddress;
}
?>