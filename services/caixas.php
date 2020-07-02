<?php
//criado 15/05/2020 - Diogo Cesar
//modificado
date_default_timezone_set('America/Bahia');
require_once 'conecta.php';
include 'log.php';
include 'getIp.php';

$ip = get_client_ip();
$data = date('Y-m-d');
$hora = date('H:i:s');

if (isset($_GET['e'])) {
	$empresaMatriz = base64_decode($_GET['e']);
	//echo $empresaMatriz;
}
if (isset($_GET['eA'])) {
	$empresaAcesso = base64_decode($_GET['eA']);
}
if (isset($_GET['us_name'])) {
	$us_name = base64_decode($_GET['us_name']);
}

if (isset($_GET['bc_cod_func'])) {

	$bc_cod_func = base64_decode($_GET['bc_cod_func']);

	if ($bc_cod_func == '' or $bc_cod_func == null) {
		$cod_func = '';
	}
	else if($empresaAcesso == 0){
		$cod_func = " and bc_cod_func = " . $bc_cod_func;
	} else {
		$cod_func = " and bc_cod_func = " . $bc_cod_func . ' and bc_empresa = ' . $empresaAcesso;
	}
} else {
	$cod_func = '';
}

//echo $bc_cod_func;

if (isset($_GET['empresa'])) {
	$empresa = $_GET['empresa'];

	if ($empresa == null or $empresa == '' or $empresa == 0) {
		$pd_empresa = '';

	} else {
		$pd_empresa = ' and bc_empresa= ' . $empresa;
	}

} else {
	$pd_empresa = '';
}


if (isset($_GET['caixa'])) {

	if (isset($_GET['contrCaixa'])) {

		if(isset($_GET['bc_id'])){
			$bcaixa_id = base64_decode($_GET['bc_id']);

			if($bcaixa_id == null or $bcaixa_id == '' or $bcaixa_id == 0){
				$bc_id='';
			}else{
				$bc_id = ' and bc_id= ' .$bcaixa_id;
			}
		}else{
			$bc_id='';
		}
		

		$lista = '{"result":[' . json_encode(caixas($conexao, $empresaMatriz, $pd_empresa, $cod_func, $bc_id)) . ']}';
		echo $lista;
	}

	if (isset($_GET['caixasVerifica'])) {
		$exCaixa = caixasVerifica($conexao, $empresaMatriz, $pd_empresa, $cod_func);
		echo $exCaixa;
	}
	if (isset($_GET['criarCaixa'])) {
		criarCaixa($conexao, $empresaMatriz, $empresaAcesso, $bc_cod_func, $us_name, $data, $hora, $ip);
	}

	if (isset($_GET['abrirCaixa'])) {
		$array = json_decode(file_get_contents("php://input"), true);
		$caixa = $array['caixa'][0];
		$bc_id = base64_decode($caixa['bc_id']);
		$bc_data = $caixa['bc_data'];
		$bc_val_f = $caixa['bc_val'];
		$bc_val = str_replace('R', '', $bc_val_f);
		$bc_val = str_replace('$', '', $bc_val);
		$bc_val = str_replace(',', '.', $bc_val);
/*
echo 'ID: ' . $bc_id . '<br>';
echo 'Data ' . $bc_data . '<br>';
echo 'Valor ' . $bc_val . '<br>';
 */
		abrirCaixa($conexao, $empresaAcesso, $empresaMatriz, $bc_id, $bc_val, $bc_data, $us_name, $data, $hora, $ip);
	}
}

if(isset($_GET['movimentacao'])){

	if(isset($_GET['movAb'])){
		$token = $_GET['token'];
		$bc_id =  base64_decode($_GET['bc_id']);
		$dc = $_GET['dc'];
		$modBanco = $_GET['mod'];
		$lista = '{"result":[' . json_encode(movimentacaoCaixaAb($conexao, $modBanco, $token, $bc_id, $dc)) . ']}';
		echo $lista;
	}
}

if(isset($_GET['excluirMovi'])){
	if(isset($_GET['cx_id'])){
		$cx_id = $_GET['cx_id'];
	}
	$token = $_GET['token'];
	$us_id = base64_decode($_GET['us_id']);

	excluirMovi($conexao, $token, $cx_id, $data, $hora, $ip, $us_id, $empresaMatriz);
	//echo $lista;
}


function caixas($conexao, $empresaMatriz, $pd_empresa, $cod_func, $bc_id) {

	$retorno = array();

		$sql = "SELECT bancos.bc_id, bancos.bc_codigo, bancos.bc_empresa, empresas.em_fanta, bancos.bc_matriz, bancos.bc_cod_func, bancos.bc_descricao,
	bancos.bc_saldo, bancos.bc_saldoanterior, bancos.bc_situacao, bancos.bc_data, bancos.bc_caixa, bancos.bc_cc, bancos.bc_saldocontabil,
	bancos.bc_saldoreal, bancos.bc_caixa
	FROM zmpro.bancos
	inner join empresas on (bc_empresa = em_cod)
	where bc_matriz=$empresaMatriz and bc_caixa = 'S' $pd_empresa $cod_func $bc_id;";

	$query = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($retorno, array(

			'bc_id' => $row['bc_id'],
			'bc_codigo' => $row['bc_codigo'],
			'bc_empresa' => $row['bc_empresa'],
			'em_fanta' => utf8_encode($row['em_fanta']),
			'bc_matriz' => $row['bc_matriz'],
			'bc_cod_func' => $row['bc_cod_func'],
			'bc_descricao' => utf8_encode($row['bc_descricao']),
			'bc_saldo' => $row['bc_saldo'],
			'bc_saldoanterior' => $row['bc_saldoanterior'],
			'bc_situacao' => utf8_encode($row['bc_situacao']),
			'bc_data' => utf8_encode($row['bc_data']),
			'bc_cc' => utf8_encode($row['bc_cc']),
			'bc_saldocontabil' => $row['bc_saldocontabil'],
			'bc_saldoreal' => $row['bc_saldoreal'],
			'bc_caixa' => utf8_encode($row['bc_caixa']),

			/*'bc_codbanco' => $row['bc_codbanco'],
				'bc_cobrregistrada' => $row['bc_cobrregistrada'],
				'bc_digconta' => $row['bc_digconta'],
				'bc_agencia' => $row['bc_agencia'],
				'bc_digagencia' => $row['bc_digagencia'],
				'bc_carteira' => $row['bc_carteira'],
				'bc_nomebanco' => $row['bc_nomebanco'],
				'bc_codcedente' => $row['bc_codcedente'],
				'bc_modalidade' => $row['bc_modalidade'],
				'bc_path_impr' => $row['bc_path_impr'],
				'bc_val_bl' => $row['bc_val_bl'],
				'bc_hist_bl' => $row['bc_hist_bl'],
				'bc_ultcheque' => $row['bc_ultcheque'],
				'bc_tipo_cobr_bl' => $row['bc_tipo_cobr_bl'],
				'bc_ult_fech' => $row['bc_ult_fech'],
			*/

		));
	}

	//echo $sql;
	return $retorno;
}

function caixasVerifica($conexao, $empresaMatriz, $pd_empresa, $cod_func) {

		$sql = "SELECT bancos.bc_id, bancos.bc_codigo, bancos.bc_empresa, empresas.em_fanta, bancos.bc_matriz, bancos.bc_cod_func, bancos.bc_descricao,
	bancos.bc_saldo, bancos.bc_saldoanterior, bancos.bc_situacao, bancos.bc_data, bancos.bc_caixa, bancos.bc_cc, bancos.bc_saldocontabil,
	bancos.bc_saldoreal, bancos.bc_caixa
	FROM zmpro.bancos
	inner join empresas on (bc_empresa = em_cod)
	where bc_matriz=$empresaMatriz and bc_caixa = 'S' $pd_empresa $cod_func;";

	$query = mysqli_query($conexao, $sql);

	//echo $sql;
	return mysqli_num_rows($query);
}

function criarCaixa($conexao, $empresaMatriz, $empresa, $cod_func, $us_name, $data, $hora, $ip) {

	$sql = "INSERT INTO bancos (bc_codigo, bc_empresa, bc_matriz, bc_cod_func, bc_descricao, bc_situacao, bc_data, bc_caixa)
 SELECT (max(bc_codigo) +1), $empresa, $empresaMatriz, $cod_func,'Caixa $us_name','Fechado',now(),'S' FROM bancos where bc_matriz = $empresaMatriz;";

	$query = mysqli_query($conexao, $sql);

	$retorno = mysqli_affected_rows($conexao);

	if ($retorno == 1) {
		logSistema($conexao, $data, $hora, $ip, $us_name, 'Incluido Caixa - ' . $us_name . '', $empresa, $empresaMatriz);
	}
	echo $retorno;

}

function abrirCaixa($conexao, $empresa, $empresaMatriz, $bc_id, $bc_saldo, $bc_data, $us_name, $data, $hora, $ip) {
	$sql = "UPDATE bancos SET bc_saldo = '$bc_saldo', bc_situacao = 'Aberto', bc_data = '$bc_data' WHERE bc_id = $bc_id and bc_matriz=$empresaMatriz;";

	$query = mysqli_query($conexao, $sql);

	$retorno = mysqli_affected_rows($conexao);

	if ($retorno == 1) {
		logSistema($conexao, $data, $hora, $ip, $us_name, 'Caixa Aberto - ' . $us_name . '', $empresa, $empresaMatriz);
	}
	echo $retorno;
	//echo $sql;
}

function movimentacaoCaixaAb($conexao, $modBanco, $token, $bc_id, $dc){

	$retorno = array();

	
	$sql ="SELECT * FROM zmpro.$modBanco where cx_matriz =  (SELECT em_cod FROM empresas where em_token = '$token')  and cx_empresa = (select bc_empresa FROM bancos where bc_id = $bc_id) 
   and cx_banco = (select bc_codigo FROM bancos where bc_id = $bc_id) and cx_deletado <> 'S'";

	$query = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($retorno, array(
			'cx_id' => $row['cx_id'],
			'cx_idlocal' => $row['cx_idlocal'],
			'cx_empresa' => $row['cx_empresa'],
			'cx_matriz' => $row['cx_matriz'],
			'cx_docto' => $row['cx_docto'],
			'cx_emissao' => utf8_encode($row['cx_emissao']),
			'cx_historico' => $row['cx_historico'],
			'cx_tpdocto' => $row['cx_tpdocto'],
			'cx_dc' => utf8_encode($row['cx_dc']),
			'cx_nome' => utf8_encode($row['cx_nome']),
			'cx_obs' => $row['cx_obs'],
			'cx_valor' => $row['cx_valor'],
			'cx_banco' => $row['cx_banco'],
			'cx_canc' => utf8_encode($row['cx_canc']),
			'cx_docanual' => utf8_encode($row['cx_canc']),
			'cx_manual' => utf8_encode($row['cx_manual']),
			'cx_ocorrencia' => $row['cx_ocorrencia'],
			'cx_vendedor' => utf8_encode($row['cx_vendedor']),
			'cx_empr' => $row['cx_empr'],
			
		));
	};

	//echo $sql;
	return $retorno;
}

function excluirMovi($conexao, $token, $cx_id, $data, $hora, $ip, $us_id, $empresaMatriz){
	$retorno = array();
	
	$sql ="update caixa_aberto set cx_deletado='S' where  cx_matriz = (SELECT em_cod FROM empresas where em_token = '$token') 
	and cx_id = $cx_id;
	";
	$query = mysqli_query($conexao, $sql);

	if (mysqli_affected_rows($conexao) <= 0) {
		array_push($retorno, array(
			'status'=> $row = 'ERROR',
			
		));
		echo '{"result":[' . json_encode($retorno). ']}';
	}
	else{
		array_push($retorno, array(
			'status'=> $row = 'SUCCESS',
			''
		));
		
		$msg = 'Movimentação Do Caixa Deletado Ocorrência N ';
		logSistema_Movimentacao_forOcorrencia($conexao, $data, $hora, $ip, $us_id, $msg, $cx_id, $empresaMatriz);

		echo '{"result":[' . json_encode($retorno). ']}';
	}

}

?>