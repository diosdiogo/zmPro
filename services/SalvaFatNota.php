<?php
include 'conecta.php';
include 'log.php';
date_default_timezone_set('America/Bahia');

// data de alteração 28/04/2020 - Kleython
//data de upload na nuvem

$empresa = base64_decode($_GET['e']);
$editarCadastrar = $_GET['editarCadastrar'];
$us_id = base64_decode($_GET['us_id']);
$us = base64_decode($_GET['us']);
$ip = get_client_ip();

$data = date('Y-m-d'); 
$hora = date('H:i:s');

$empresaAcesso = base64_decode($_GET['eA']);
if ($empresaAcesso == 0) {
	$empresaAcesso = $empresa;
}

$array = json_decode(file_get_contents("php://input"), true);

$contas = $array['contas'][0];

$ct_empresa = $contas['ct_empresa'];
$ct_matriz = $contas['ct_matriz'];
$ct_docto = utf8_decode($contas['ct_docto'])));
$ct_cliente_forn = utf8_decode($contas['ct_cliente_forn']);
$ct_emissao = utf8_decode($contas['ct_emissao']);
$ct_vencto = utf8_decode($contas['ct_vencto']);
$ct_valor = $contas['ct_valor'];
$ct_parc = utf8_decode($contas['ct_parc']);
$ct_nome = ucwords(strtolower(utf8_decode($contas['ct_nome'])));
$ct_tipdoc = $contas['ct_tipdoc'];
$ct_historico = ucwords(strtolower(utf8_decode($contas['ct_historico'])));
$ct_desc_hist = ucwords(strtolower(utf8_decode($contas['ct_desc_hist'])));


/*echo "Empresa: " . $empresa . '<br>';
echo "Empresa Acesso: " . $empresaAcesso . '<br>';
echo "Editar - Cadastrar: " . $editarCadastrar . '<br>';
echo "Usuario ID: " . $us_id . '<br>';
echo "Código ID: " . $pe_cod . '<br>';
echo "Nome: " . $pe_nome . '<br>';
echo "Apelido / Nome Fantasia: " . $pe_fanta . '<br>';
echo "CPF / CNPJ: " . $pe_cpfcnpj . '<br>';
echo "RG / IE: " . $pe_rgie . '<br>';
echo 'Nascimento: ' . $pe_nascto . '<br>';
echo "CEP: " . $pe_cep . '<br>';
echo 'Endereço: ' . $pe_endereco_completo . '<br>';
echo 'Bairro: ' . $pe_bairro . '<br>';
echo 'Cidade: ' . $pe_cidade . '<br>';
echo 'Estado: ' . $pe_uf . '<br>';
echo 'Fone: ' . $pe_fone . '<br>';
echo 'Email: ' . $pe_email . '<br>';
echo "OBS: " . $pe_obs . '<br>';*/

//print_r($cliente);

if ($editarCadastrar == 'C') {

	cadastrarContaPagar($conexao, $ct_empresa, $ct_matriz, $ct_docto, $ct_cliente_forn, $ct_emissao, $ct_vencto, $ct_valor, $ct_parc, $ct_nome, $ct_tipdoc, $ct_historico, $ct_desc_hist);

}

if ($editarCadastrar == 'E') {
	editarContaPagar($conexao, $ct_empresa, $ct_matriz, $ct_docto, $ct_cliente_forn, $ct_emissao, $ct_vencto, $ct_valor, $ct_parc, $ct_nome, $ct_tipdoc, $ct_historico, $ct_desc_hist);

}

function cadastrarContaPagar($conexao, $ct_empresa, $ct_matriz, $ct_docto, $ct_cliente_forn, $ct_emissao, $ct_vencto, $ct_valor, $ct_parc, $ct_nome, $ct_tipdoc, $ct_historico, $ct_desc_hist) {

	$sql = "insert into contas(ct_empresa, ct_matriz, ct_docto, ct_cliente_forn, ct_emissao, ct_vencto, ct_valor, ct_parc, ct_nome, ct_tipdoc, ct_receber_pagar, ct_quitado, ct_historico, ct_desc_hist) values
	 $ct_empresa, $ct_matriz, $ct_docto, $ct_cliente_forn, $ct_emissao, $ct_vencto, $ct_valor, $ct_parc, $ct_nome, $ct_tipdoc, 'P', 'N', $ct_historico, $ct_desc_hist;";

	$inserir = mysqli_query($conexao, $sql);

	if (mysqli_insert_id($conexao) <= 0) {
		echo 0;
	} else {
		echo 1;
		logSistema($conexao, $data, $hora, $ip, $us, 'Conta Cadastrada ' . $pe_nome . '');
	}
	echo $sql;
	return $inserir;
}

function editarContaPagar($conexao, $ct_id, $ct_empresa, $ct_matriz, $ct_docto, $ct_cliente_forn, $ct_emissao, $ct_vencto, $ct_valor, $ct_parc, $ct_nome, $ct_tipdoc, $ct_historico, $ct_desc_hist) {

	$sql = "update contas set ct_empresa = '$ct_empresa', ct_matriz = '$ct_matriz', ct_docto = '$ct_docto', ct_cliente_forn = '$ct_cliente_forn', ct_emissao = '$ct_emissao', ct_vencto = '$ct_vencto', ct_valor = '$ct_valor', ct_parc = '$ct_parc', ct_nome = $ct_nome'$ct_nome', ct_tipdoc = '$ct_tipdoc', ct_receber_pagar = 'P', ct_quitado = 'N', ct_historico = '$ct_historico', ct_desc_hist = '$ct_desc_hist' where ct_id = '$ct_id';";

	$query = mysqli_query($conexao, $sql);

	if (mysqli_affected_rows($conexao) <= 0) {
		echo 0;
	} else {
		echo 1;
		logSistema($conexao, $data, $hora, $ip, $us, 'Conta Alterada ' . $pe_nome . '');

	}
	echo $sql;
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