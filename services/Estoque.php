<?php

require_once 'conecta.php';

$empresaMatriz = base64_decode($_GET['e']);

$empresaAcesso = base64_decode($_GET['eA']);

if (isset($_GET['empresa'])) {
	$empresa = $_GET['empresa'];

	if ($empresa == null) {
		$tr_empresa = '';

	} else {
		$tr_empresa = ' and tr_empresa= ' . $empresa;
	}

} else {
	$tr_empresa = '';
}

$dataI = $_GET['dataI'];
$dataF = $_GET['dataF'];

if (isset($_GET['tr_id'])) {
	$tr_id = $_GET['tr_id'];
} else {
	$tr_id = '';
}

if (isset($_GET['funcionario'])) {
	$funcionario = $_GET['funcionario'];

	if ($funcionario == null) {
		$vd_func = '';
	} 
} else {
	$vd_func = '';
}

//$EditarCadastrarExcluir = $_GET['EditarCadastrarExcluir'];


//$array = json_decode(file_get_contents("php://input"), true);

//$id = $_GET['id'];

if (isset($_GET['dadosTransferencias'])) {

	$lista = '{"result":[' . json_encode(dadosTransferencias($conexao, $empresaMatriz, $tr_empresa, $dataI, $dataF)) . ']}';
	echo $lista;

} 

if (isset($_GET['itensTransf'])) {

	$lista = '{"result":[' . json_encode(itensTransf($conexao, $empresaMatriz, $dataI, $dataF, $tr_id)) . ']}';
	echo $lista;

}

function dadosTransferencias($conexao, $empresaMatriz, $tr_empresa, $dataI, $dataF) {

	$retorno = array();

	$sql = "select 	transferencia.tr_id,
					transferencia.tr_cod,
					transferencia.tr_empresa,
					transferencia.tr_data,
					(select empresas.em_fanta from empresas where empresas.em_cod_local = transferencia.tr_saida and transferencia.tr_matriz = empresas.em_cod_matriz) as emp_saida,
					(select empresas.em_fanta from empresas where empresas.em_cod_local = transferencia.tr_entrada and transferencia.tr_matriz = empresas.em_cod_matriz) as emp_entrada,
					transferencia.tr_pedido,
					transferencia.tr_autorizado,
					transferencia.tr_sel,
					transferencia.tr_lancado,
					transferencia.tr_enviado,
					transferencia.tr_recebido
 			from transferencia inner join empresas on transferencia.tr_empresa = empresas.em_cod and transferencia.tr_matriz = empresas.em_cod_matriz
 			where tr_matriz = $empresaMatriz $tr_empresa
			and tr_data between '$dataI' and '$dataF';";

	$query = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($retorno, array(
				'tr_id' => $row['tr_id'],
				'tr_cod' => $row['tr_cod'],
				'tr_empresa' => $row['tr_empresa'],
				'tr_data' => utf8_encode($row['tr_data']),
				'emp_saida' => ucwords(strtolower(utf8_encode($row['emp_saida']))),
				'emp_entrada' => ucwords(strtolower(utf8_encode($row['emp_entrada']))),
				'tr_pedido' => $row['tr_pedido'],
				'tr_autorizado' => $row['tr_autorizado'],
				'tr_sel' => $row['tr_sel'],
				'tr_lancado' => $row['tr_lancado'],
				'tr_enviado' => $row['tr_enviado'],
				'tr_recebido' => $row['tr_recebido'],
		));
	}

	//echo $sql;

	return $retorno;
}


function itensTransf($conexao, $empresaMatriz, $dataI, $dataF, $tr_id) {

	$resultado = array();

	//$sql = "select count(distinct(vd_cli)) totalCliente from vendas where vd_emis >= '$dataI' and vd_emis <= '$dataF' and vd_canc<>'S' and vd_pgr<>'D' and vd_matriz = $empresaMatriz $vd_empr $vd_cli $vd_func";

	$sql = "SELECT 	tri_id,
					tri_empresa,
					tri_matriz,
					tri_cod,
					tri_data,
					tri_saida,
					tri_entrada,
					tri_prod,
					tri_desc,
					tri_quant
			from transf_item
			where tri_idprim = $tr_id
			order by tri_desc;";

	$query = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($query)) {
		
		array_push($resultado, array(

				'tri_id' => $row['tri_id'],
				'tri_empresa' => $row['tri_empresa'],
				'tri_matriz' => $row['tri_matriz'],
				'tri_cod'  => $row['tri_cod'],
				'tri_data' => utf8_encode($row['tri_data']),
				'tri_saida' => $row['tri_saida'],
				'tri_entrada' => $row['tri_entrada'],
				'tri_prod' => $row['tri_prod'],
				'tri_desc' => ucwords(strtolower(utf8_encode($row['tri_desc']))),
				'tri_quant' => utf8_encode($row['tri_quant']),

		));

	}

	//echo $sql;
	return $resultado;
}

?>
