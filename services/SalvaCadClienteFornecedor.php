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
$cliente_fornecedor = $_GET['cliente_fornecedor'];

$data = date('Y-m-d'); 
$hora = date('H:i:s');

$empresaAcesso = base64_decode($_GET['eA']);
if ($empresaAcesso == 0) {
	$empresaAcesso = $empresa;
}

$array = json_decode(file_get_contents("php://input"), true);

$cliente = $array['cliente'][0];

$pe_id = $cliente['pe_id'];
$pe_nome = ucwords(strtolower(utf8_decode($cliente['pe_nome'])));
$pe_fanta = ucwords(strtolower(utf8_decode($cliente['pe_fanta'])));
$pe_cpfcnpj = utf8_decode($cliente['pe_cpfcnpj']);
$pe_rgie = utf8_decode($cliente['pe_rgie']);
$pe_nascto = $cliente['pe_nascto'];
$pe_cep = utf8_decode($cliente['pe_cep']);
$pe_endereco = ucwords(strtolower(utf8_decode($cliente['pe_endereco'])));
$pe_end_num = $cliente['pe_end_num'];
$pe_end_comp = ucwords(strtolower(utf8_decode($cliente['pe_end_comp'])));
$pe_bairro = ucwords(strtolower(utf8_decode($cliente['pe_bairro'])));
$pe_cidade = ucwords(strtolower(utf8_decode($cliente['pe_cidade'])));
$pe_uf = $cliente['pe_uf'];
$pe_fone = $cliente['pe_fone'];
$pe_email = $cliente['pe_email'];
$pe_limite = null;
$pe_obs = utf8_decode($cliente['pe_obs']);
$pe_vendedor = utf8_decode($cliente['pe_vendedor']);

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

	cadastrarClienteFornecedor($conexao, $empresa, $empresaAcesso, $pe_nome, $pe_fanta, $pe_cpfcnpj, $pe_rgie, $pe_nascto, $pe_cep, $pe_endereco, $pe_end_num, $pe_end_comp, $pe_bairro, $pe_cidade, $pe_uf, $pe_fone, $pe_email, $pe_limite, $pe_obs, $pe_vendedor, $data, $hora, $ip, $us, $cliente_fornecedor);

}

if ($editarCadastrar == 'E') {
	editarClienteFornecedor($conexao, $pe_nome, $pe_fanta, $pe_cpfcnpj, $pe_rgie, $pe_nascto, $pe_cep, $pe_endereco, $pe_end_num, $pe_end_comp, $pe_bairro, $pe_cidade, $pe_uf, $pe_fone, $pe_email, $pe_limite, $pe_obs, $pe_vendedor, $pe_id, $data, $hora, $ip, $us, $cliente_fornecedor);

}

function cadastrarClienteFornecedor($conexao, $empresa, $empresaAcesso, $pe_nome, $pe_fanta, $pe_cpfcnpj, $pe_rgie, $pe_nascto, $pe_cep, $pe_endereco, $pe_end_num, $pe_end_comp, $pe_bairro, $pe_cidade, $pe_uf, $pe_fone, $pe_email, $pe_limite, $pe_obs, $pe_vendedor, $data, $hora, $ip, $us, $cliente_fornecedor) {

	$sql = "insert into pessoas(pe_cod, pe_empresa, pe_matriz, pe_nome, pe_fanta, pe_cpfcnpj, pe_rgie, pe_nascto, pe_cep, pe_endereco, pe_end_num, pe_end_comp, pe_bairro, pe_cidade, pe_uf, pe_fone, pe_email, pe_limite, pe_obs, pe_vendedor, pe_situacao, pe_ativo, $cliente_fornecedor)
	select (MAX(pe_cod)+1), $empresaAcesso, $empresa, '$pe_nome', '$pe_fanta', '$pe_cpfcnpj', '$pe_rgie', '$pe_nascto', '$pe_cep', '$pe_endereco', '$pe_end_num', '$pe_end_comp', '$pe_bairro', '$pe_cidade', '$pe_uf', '$pe_fone', '$pe_email', '$pe_limite', '$pe_obs', '$pe_vendedor', 1, 'S', 'S' from pessoas where pe_empresa = $empresaAcesso and pe_matriz = $empresa;";

	$inserir = mysqli_query($conexao, $sql);

	if (mysqli_insert_id($conexao) <= 0) {
		echo 0;
	} else {
		echo 1;
		logSistema($conexao, $data, $hora, $ip, $us, 'Cliente Cadastrado ' . $pe_nome ,$empresa, $empresaAcesso);
	}
	echo $sql;
	return $inserir;
}

function editarClienteFornecedor($conexao, $pe_nome, $pe_fanta, $pe_cpfcnpj, $pe_rgie, $pe_nascto, $pe_cep, $pe_endereco, $pe_end_num, $pe_end_comp, $pe_bairro, $pe_cidade, $pe_uf, $pe_fone, $pe_email, $pe_limite, $pe_obs, $pe_vendedor, $pe_id, $data, $hora, $ip, $us, $cliente_fornecedor) {

	$sql = "update pessoas set pe_nome = '$pe_nome', pe_fanta='$pe_fanta', pe_cpfcnpj = '$pe_cpfcnpj', pe_rgie='$pe_rgie', pe_nascto = '$pe_nascto', pe_cep = '$pe_cep', pe_endereco= '$pe_endereco', pe_end_num='$pe_end_num', pe_end_comp='$pe_end_comp', pe_bairro='$pe_bairro', pe_cidade = '$pe_cidade', pe_uf = '$pe_uf', pe_fone='$pe_fone', pe_email='$pe_email', pe_limite='$pe_limite', pe_obs='$pe_obs', pe_vendedor='$pe_vendedor', $cliente_fornecedor='S' where pe_id= '$pe_id';";

	$query = mysqli_query($conexao, $sql);

	if (mysqli_affected_rows($conexao) <= 0) {
		echo 0;
	} else {
		echo 1;
		logSistema($conexao, $data, $hora, $ip, $us, 'Cliente Alterado ' . $pe_nome . '');

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