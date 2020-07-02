<?php
include 'conecta.php';
include 'log.php';
include 'ocorrencia.php';
include 'lancarCaixa.php';
include 'getIp.php';

date_default_timezone_set('America/Bahia');

$id = base64_decode($_GET['us_id']);
$empresa = base64_decode($_GET['e']);

$empresaAcesso = base64_decode($_GET['eA']);

// data de alteração 28/04/2020 - Kleython
//data de upload na nuvem

$data = date('Y-m-d'); 
$hora = date('H:i:s');
$ip = get_client_ip();

if(isset($_GET['SalvarDespesa'])){



	$array = json_decode(file_get_contents("php://input"), true);

	$ct_empresa = $empresaAcesso;
	$ct_matriz = $empresa;
	/*
	$ct_docto = $array['despesa']['ct_docto'];
	$ct_cliente_forn = $array['despesa']['ct_cliente_forn'];
	$ct_emissao = $array['despesa']['ct_emissao'];
	$ct_nome = $array['despesa']['ct_nome'];
	$ct_tipdoc = $array['despesa']['ct_tipdoc'];
	$ct_pagto = $array['despesa']['vencimento'];
	$ct_valorpago = $array['despesa']['inputValor'];*/

	$selecionarCaixa = $_GET['cx'];
	$bc_codigo = $_GET['bc_codigo'];


	$despesa = $array['despesa'];

	$parcelas = $array['parcelas'];
	//print_r($despesa). '<p>';
	//print_r($parcelas);

	if (isset($_GET['ct_emissao'])){
		$ct_emissao = $_GET['ct_emissao'];
	}

	if (isset($_GET['bc_codigo'])){
		$bc_codigo = base64_decode($_GET['bc_codigo']);
		if ($bc_codigo == 'undefined'){
			$bc_codigo = '""';
		}
	} else {
		$bc_codigo = '';
	}

	$ct_quitado = '';

	//$selecionarCaixa = $array['contas'][0]['selecionarCaixa'];

	//$bc_id = $array['contas'][0]['bc_id'];



	/*print_r($array['parcelas']);*/
	//$ocorrencia = ocorrencia ($conexao, $ct_matriz);

	$i=1;

	foreach ($parcelas as $parcelas) {
		
		$ct_pagto = $parcelas['vencimento'];
		$ct_valorpago = $parcelas['parcela'];
		$ct_quitado = 'N';
		$ct_pagto = null;
		$ct_valorpago = null;

		adicionarCompra ($conexao, $ct_empresa, $ct_matriz, $despesa['ct_docto'], $despesa['ct_cliente_forn'], $despesa['ct_obs'], $ct_emissao, $parcelas['vencimento'], $parcelas['parcela'], $parcelas['vezes'], $ct_quitado, $despesa['ct_historico'], $ct_pagto, $ct_valorpago, $selecionarCaixa, $bc_codigo, $i);
		$i++;
	};
}

if (isset($_GET['editarDispesa'])) {
	$array = json_decode(file_get_contents("php://input"), true);

	$editarConta = $array['editarConta'][0];
	//print_r($editarConta);

	editarDispesa($conexao, $editarConta['ct_id'], $editarConta['parc'], $editarConta['mudarVencimento'], $editarConta['mudarValor'], $editarConta['mudarTipoDocto'], $editarConta['ct_docto'], $editarConta['ct_obs'], $editarConta['ct_cliente_forn'], $id, $empresa, $data, $hora, $ip);
}
function adicionarCompra($conexao, $ct_empresa, $ct_matriz, $ct_docto, $ct_cliente_forn, $ct_obs, $ct_emissao, $vencimento, $parcela, $vezes, $ct_quitado, $ct_historico, $ct_pagto, $ct_valorpago, $selecionarCaixa, $bc_codigo, $i) {

	$retorno = array();

	$sql = "insert into contas(ct_empresa, ct_matriz, ct_docto, ct_cliente_forn, ct_nome, ct_obs, ct_emissao, ct_vencto, ct_valor, ct_parc, ct_canc, ct_tipdoc, ct_receber_pagar, ct_quitado, ct_historico, ct_desc_hist) values (
	$ct_matriz, $ct_matriz, $ct_docto, (select pe_cod from pessoas where pe_id = $ct_cliente_forn), (select pe_nome from pessoas where pe_id = $ct_cliente_forn), UPPER ('$ct_obs'), '$ct_emissao', '$vencimento', '$parcela', '$vezes', 'N', 3, 'P', '$ct_quitado', (select ht_cod from historico where ht_id = $ct_historico), (select ht_descricao from historico where ht_id = $ct_historico));";

	$inserir = mysqli_query($conexao, $sql);
	
	$ct_id = mysqli_insert_id($conexao);

	if (mysqli_affected_rows($conexao) <= 0) {
	
		array_push($retorno, array(
                'status'=> $row = 'ERROR',
            ));
	
	} else {

		if ($selecionarCaixa == 'true') {
			array_push($retorno, array(
                'status'=> $row = 'SUCCESS',
            ));

            echo '{"result":[' . json_encode($retorno). ']}';

		} else {

			if ($i == 1) {
				array_push($retorno, array(
	                'status'=> $row = 'SUCCESS',
	               
	            ));

	            echo '{"result":[' . json_encode($retorno). ']}';
			}
		}

		return $retorno;

	}

	//echo $sql;
	
}

function editarDispesa($conexao, $ct_id, $parc, $mudarVencimento, $mudarValor, $mudarTipoDoctolor, $ct_docto, $ct_obs, $ct_cliente_forn, $id, $empresa, $data, $hora, $ip){
	$retorno = array();

	$valor = str_replace('R','',$mudarValor);
	$valor = str_replace('$','',$valor); 
	$valor = str_replace(',','.',$valor); 

	$sql = "UPDATE contas set ct_vencto = '$mudarVencimento', ct_valor = $valor, ct_tipdoc = $mudarTipoDoctolor, ct_obs = '$ct_obs', 
	ct_cliente_forn = (SELECT pe_cod from pessoas where pe_id = $ct_cliente_forn), 
	ct_nome = (SELECT pe_nome from pessoas where pe_id = $ct_cliente_forn) where ct_id = $ct_id;";

	$query = mysqli_query ($conexao, $sql);

	if (mysqli_affected_rows($conexao) <= 0) {
		array_push($retorno, array(
			'status'=> $row = 'ERROR',
			
		));

	} else {
	   
		array_push($retorno, array(
			'status'=> $row = 'SUCCESS',
		));
		
		$msg = "Dispesa editada N Docto. ". $ct_docto;

		logSistema_forID($conexao, $data, $hora, $ip, $id, $msg, $empresa, $empresa);
	

	}

	//echo $sql;
	echo '{"result":[' . json_encode($retorno) . ']}';
}

?>