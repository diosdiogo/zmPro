<?php
require_once 'conecta.php';

$empresaMatriz = base64_decode($_GET['e']);

$empresaAcesso = base64_decode($_GET['eA']);
$dataI = $_GET['dataI'];
$dataF = $_GET['dataF'];

if (isset($_GET['empresa'])) {
	$empresa = $_GET['empresa'];

	if ($empresa == null) {
		$vdi_empr = '';

	} else {
		$vdi_empr = ' and vdi_empr=' . $empresa;
	}

} else {
	$vdi_empr = '';
}

if (isset($_GET['subgrupo'])) {
	$subgrupo = $_GET['subgrupo'];

	if ($subgrupo == null) {
		$pd_subgrupo = '';
	} else {
		$pd_subgrupo = ' AND vdi_subgrupo = ' . $subgrupo;
	}
} else {
	$pd_subgrupo = '';
}

if (isset($_GET['dadosRelatorio'])) {

	if (isset($_GET['pagination'])) {

		$lista = '{"result":[' . json_encode(relatorioPaginate($conexao, $empresaMatriz, $dataI, $dataF, $vdi_empr, $pd_subgrupo)) . ']}';
		echo $lista;

	}

	if (isset($_GET['relatorio'])) {
		
		if (isset($_GET['js'])) {
			$lista = json_encode(relatorio($conexao, $empresaMatriz, $dataI, $dataF, $vdi_empr, $pd_subgrupo));
		}else{
			$lista = '{"result":[' . json_encode(relatorio($conexao, $empresaMatriz, $dataI, $dataF, $vdi_empr, $pd_subgrupo)) . ']}';
		}
		echo $lista;

	}

	if (isset($_GET['relatorioTotalRegistro'])) {
		$lista = '{"result":[' . json_encode(relatorioTotalRegistro($conexao, $empresaMatriz, $dataI, $dataF, $vdi_empr, $pd_subgrupo)) . ']}';
		echo $lista;
	}
	if (isset($_GET['relatorioTotalQtsValor'])) {
		$lista = '{"result":[' . json_encode(relatorioTotalQtsValor($conexao, $empresaMatriz, $dataI, $dataF, $vdi_empr, $pd_subgrupo)) . ']}';
		echo $lista;
	}
}

function relatorio($conexao, $empresaMatriz, $dataI, $dataF, $vdi_empr, $pd_subgrupo) {

	$resultado = array();

	$sql = "SELECT venda_item.vdi_id, venda_item.vdi_prod, venda_item.vdi_descricao, venda_item.vdi_empr, empresas.em_fanta,
	venda_item.vdi_matriz, vdi_subgrupo,
	SUM(vdi_quant) AS qnt, SUM(vdi_total) AS total
	from venda_item 
	inner join empresas on (venda_item.vdi_empr = empresas.em_cod)
	where vdi_emis between '$dataI' and '$dataF'
	AND venda_item.vdi_canc<>'S'
	AND venda_item.vdi_pgr<>'D'
	AND venda_item.vdi_matriz = $empresaMatriz
	$vdi_empr
	$pd_subgrupo
	group by vdi_prod order by qnt desc;";

	$query = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($resultado, array(
			'vdi_prod' => $row['vdi_prod'],
			'vdi_descricao' => utf8_encode($row['vdi_descricao']),
			'vdi_empr' => $row['vdi_empr'],
			'em_fanta' => utf8_encode($row['em_fanta']),
			'vdi_matriz' => $row['vdi_matriz'],
			'vdi_subgrupo' => $row['vdi_subgrupo'],
			//'sbp_descricao' => utf8_encode($row['sbp_descricao']),
			'qnt' => (float) $row['qnt'],
			'total' => $row['total'],
		));
	}

	//echo $sql;
	return $resultado;
}

function relatorioTotalRegistro($conexao, $empresaMatriz, $dataI, $dataF, $vdi_empr, $pd_subgrupo) {

	$resultado = array();

	/*$sql = "SELECT venda_item.vdi_id, venda_item.vdi_prod, venda_item.vdi_descricao, venda_item.vdi_empr, venda_item.vdi_matriz, produtos.pd_subgrupo, subgrupo_prod.sbp_descricao, SUM(vdi_quant) AS qnt, SUM(vdi_total) AS total from venda_item
		inner join produtos on (venda_item.vdi_prod=produtos.pd_cod and venda_item.vdi_matriz=produtos.pd_matriz)
		inner join subgrupo_prod on (produtos.pd_subgrupo=subgrupo_prod.sbp_codigo and venda_item.vdi_matriz=subgrupo_prod.sbp_matriz)
		WHERE vdi_emis>=CAST('$dataI'  AS DATE) AND vdi_emis<=CAST('$dataF' AS DATE)
		and venda_item.vdi_canc<>'S'
		AND venda_item.vdi_pgr<>'D'
		AND vdi_matriz = $empresaMatriz
		$vdi_empr
		$pd_subgrupo
		group by venda_item.vdi_prod, venda_item.vdi_descricao, produtos.pd_subgrupo, subgrupo_prod.sbp_descricao
	*/

	$sql = "SELECT venda_item.vdi_id, venda_item.vdi_prod, venda_item.vdi_descricao, venda_item.vdi_empr, empresas.em_fanta,
	venda_item.vdi_matriz, vdi_subgrupo,
	SUM(vdi_quant) AS qnt, SUM(vdi_total) AS total
	from venda_item 
	inner join empresas on (venda_item.vdi_empr = empresas.em_cod)
	where vdi_emis between '$dataI' and '$dataF'
	AND venda_item.vdi_canc<>'S'
	AND venda_item.vdi_pgr<>'D'
	AND venda_item.vdi_matriz = $empresaMatriz
	$vdi_empr
	$pd_subgrupo
	group by vdi_prod order by qnt desc;";

	$query = mysqli_query($conexao, $sql);

	array_push($resultado, array(
		'total' => mysqli_num_rows($query),

	));
	//echo $sql;
	return $resultado;
}

function relatorioTotalQtsValor($conexao, $empresaMatriz, $dataI, $dataF, $vdi_empr, $pd_subgrupo) {

	$resultado = array();

	/*$sql = "SELECT SUM(vdi_quant) AS qnt, SUM(vdi_total) AS total from venda_item
		inner join produtos on (venda_item.vdi_prod=produtos.pd_cod and venda_item.vdi_matriz=produtos.pd_matriz)
		inner join subgrupo_prod on (produtos.pd_subgrupo=subgrupo_prod.sbp_codigo and venda_item.vdi_matriz=subgrupo_prod.sbp_matriz)
		WHERE vdi_emis>=CAST('$dataI'  AS DATE) AND vdi_emis<=CAST('$dataF' AS DATE)
		and venda_item.vdi_canc<>'S'
		AND venda_item.vdi_pgr<>'D'
		AND vdi_matriz = $empresaMatriz
		$vdi_empr
	*/

	$sql = "SELECT SUM(vdi_quant) AS qnt, SUM(vdi_total) AS total
	from venda_item
	inner join empresas on (venda_item.vdi_empr = empresas.em_cod)
	where vdi_emis between '$dataI' and '$dataF'
	AND venda_item.vdi_canc<>'S'
	AND venda_item.vdi_pgr<>'D'
	AND venda_item.vdi_matriz = $empresaMatriz
	$vdi_empr
	$pd_subgrupo;";

	$query = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($resultado, array(
			'qnt' => (float) $row['qnt'],
			'total' => $row['total'],
		));

		//echo $sql;
		return $resultado;
	}
}

?>