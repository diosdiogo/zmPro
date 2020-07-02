
<?php

include 'conecta.php';
include 'log.php';
date_default_timezone_set('America/Bahia');

// data de alteração 23/04/2020 - Kleython
//data de upload na nuvem 24/04/2020 - Kleython

$empresa = base64_decode($_GET['e']);
$editarCadastrar = $_GET['editarCadastrar'];
$cnpn_matriz = base64_decode($_GET['cnpn_matriz']);
$ramo = base64_decode($_GET['ramo']);
$us = base64_decode($_GET['us']);
$ip = get_client_ip();

$data = date('Y-m-d');
$hora = date('H:i:s');
$array = json_decode(file_get_contents("php://input"), true);

//print_r($array) . '<p>';

$empresas = $array['empresa'][0];
//print_r($empresas) . '<p>';
$id = $empresas['em_cod'];
$em_cnpj = $empresas['em_cnpj'];
$em_razao = ucwords(strtolower(utf8_decode($empresas['em_razao'])));
$em_fanta = ucwords(strtolower(utf8_decode($empresas['em_fanta'])));
$em_end = ucwords(strtolower(utf8_decode($empresas['em_end'])));
$em_end_num = $empresas['em_end_num'];
$em_bairro = ucwords(strtolower(utf8_decode($empresas['em_bairro'])));
$em_cid = ucwords(strtolower(utf8_decode($empresas['em_cid'])));
$em_uf = utf8_decode($empresas['em_uf']);
$em_cep = $empresas['em_cep'];
$em_insc = $empresas['em_insc'];
$em_fone = $empresas['em_fone'];
$em_email = utf8_decode($empresas['em_email']);
$em_responsavel = ucwords(strtolower(utf8_decode($empresas['em_responsavel'])));
$em_cont_nome = ucwords(strtolower(utf8_decode($empresas['em_cont_nome'])));
$em_cont_fone = utf8_decode($empresas['em_cont_fone']);
$em_cont_email = utf8_decode($empresas['em_cont_email']);
$em_logo = substr(strstr($empresas['em_logo'], ',', false), 1);

//$em_logo = base64_decode($em_logob64);
//echo 'Imagem: ' . $em_logo;

if ($editarCadastrar == 'C') {
	insereEmpresa($conexao, $em_razao, $em_fanta, $em_end, $em_end_num, $em_bairro, $em_cid, $em_uf, $em_cep, $em_cnpj, $em_insc, $em_fone, $em_email, $em_responsavel, $em_cont_nome, $em_cont_fone, $em_cont_email, $empresa, $cnpn_matriz, $ramo, $data, $hora, $ip, $us, $em_logo);
	//echo "C";

}
if ($editarCadastrar == 'E') {
	editarEmpresa($conexao, $em_razao, $em_fanta, $em_end, $em_end_num, $em_bairro, $em_cid, $em_uf, $em_cep, $em_cnpj, $em_insc, $em_fone, $em_email, $em_responsavel, $em_cont_nome, $em_cont_fone, $em_cont_email, $id, $data, $hora, $ip, $us, $em_logo);
	//echo "E";

}

function insereEmpresa($conexao, $em_razao, $em_fanta, $em_end, $em_end_num, $em_bairro, $em_cid, $em_uf, $em_cep, $em_cnpj, $em_insc, $em_fone, $em_email, $em_responsavel, $em_cont_nome, $em_cont_fone, $em_cont_email, $empresa, $cnpn_matriz, $ramo, $data, $hora, $ip, $us, $em_logo) {

	$query = "insert into empresas (
em_razao,
em_fanta,
em_end,
em_end_num,
em_bairro,
em_cid,
em_uf,
em_cep,
em_cnpj,
em_insc,
em_fone,
em_email,
em_responsavel,
em_cont_nome,
em_cont_fone,
em_cont_email,
em_ativo,
em_cod_matriz,
em_cnpj_matriz,
em_ramo,
em_logo
)

values (
'$em_razao',
'{$em_fanta}',
'{$em_end}',
'{$em_end_num}',
'{$em_bairro}',
'{$em_cid}',
'{$em_uf}',
'{$em_cep}',
'{$em_cnpj}',
'{$em_insc}',
'{$em_fone}',
'{$em_email}',
'{$em_responsavel}',
'{$em_cont_nome}',
'{$em_cont_fone}',
'{$em_cont_email}',
'S',
$empresa,
'$cnpn_matriz',
$ramo,
'$em_logo'

)";

	$inserir = mysqli_query($conexao, $query);

	if (mysqli_insert_id($conexao) <= 0) {
		echo 0;
	} else {
		echo 1;
		logSistema($conexao, $data, $hora, $ip, $us, 'Empresa cadastrada ' . $em_razao . '');
	}
	//echo $query;
	return $inserir;
}

function editarEmpresa($conexao, $em_razao, $em_fanta, $em_end, $em_end_num, $em_bairro, $em_cid, $em_uf, $em_cep, $em_cnpj, $em_insc, $em_fone, $em_email, $em_responsavel, $em_cont_nome, $em_cont_fone, $em_cont_email, $id, $data, $hora, $ip, $us, $em_logo) {

	$sql = "update empresas set em_razao='$em_razao', em_fanta='$em_fanta', em_end = '$em_end', em_end_num='$em_end_num',em_bairro='$em_bairro',em_cid='$em_cid', em_uf='$em_uf', em_cep='$em_cep', em_cnpj='$em_cnpj', em_insc='$em_insc', em_fone='$em_fone', em_email='$em_email', em_responsavel='$em_responsavel', em_cont_nome='$em_cont_nome', em_cont_fone='$em_cont_fone', em_cont_email='$em_cont_email', em_logo='$em_logo' where em_cod= $id";

	$query = mysqli_query($conexao, $sql);

	if (mysqli_affected_rows($conexao) <= 0) {
		echo 0;
	} else {
		echo 1;
		logSistema($conexao, $data, $hora, $ip, $us, 'Empresa editada ' . $em_razao . '');
	}
	//echo $sql;
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