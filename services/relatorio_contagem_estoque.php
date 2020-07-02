<?php

require_once 'conecta.php';

$empresaMatriz = base64_decode($_GET['e']);

$empresaAcesso = base64_decode($_GET['eA']);

if (isset($_GET['empresa'])) {
	$empresa = $_GET['empresa'];

	if ($empresa == null) {
		$pd_empresa = '';

	} else {
		$pd_empresa = ' and pd_empresa= ' . $empresa;
	}

} else {
	$pd_empresa = '';
}

if (isset($_GET['subgrupo'])) {
	$subgrupo = $_GET['subgrupo'];

	if ($subgrupo == null) {
		$pd_subgrupo = '';
	} else {
		$pd_subgrupo = ' AND pd_subgrupo = ' . $subgrupo;
	}
} else {
	$pd_subgrupo = '';
}

if (isset($_GET['estoque'])) {
	$estoque = $_GET['estoque'];

	if ($estoque == 0) {
		$es_est = ' and es_est <> 0';
	} else if ($estoque == 1) {
		$es_est = ' and (es_est IS NULL or es_est = 0)';
	} else if ($estoque == 2) {
		$es_est = '';
	} else {
		$es_est = '';
	}
} else {
	$es_est = '';
}

if (isset($_GET['relatorio'])) {

	if (isset($_GET['dadosRelatorio'])) {

		$lista = '{"result":[' . json_encode(relatorio($conexao, $empresaMatriz, $pd_empresa, $pd_subgrupo)) . ']}';
		echo $lista;

	}

	if (isset($_GET['relatorioTotalRegistro'])) {
		$lista = '{"result":[' . json_encode(relatorioTotalRegistro($conexao, $empresaMatriz, $pd_empresa, $pd_subgrupo, $es_est)) . ']}';
		echo $lista;
	}
}

function relatorio($conexao, $empresaMatriz, $pd_empresa, $pd_subgrupo) {
	$resultado = array();
	$sql = "SELECT produtos.pd_id, empresas.em_fanta ,produtos.pd_cod, produtos.pd_empresa, produtos.pd_matriz, produtos.pd_desc, produtos.pd_un, produtos.pd_subgrupo,
subgrupo_prod.sbp_descricao
FROM zmpro.produtos
left join subgrupo_prod on (produtos.pd_subgrupo = subgrupo_prod.sbp_codigo)
left join empresas on(produtos.pd_empresa = empresas.em_cod)
 where pd_matriz = $empresaMatriz
 $pd_empresa
 $pd_subgrupo
 order by pd_desc;";

	$query = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($resultado, array(

			'pd_id' => $row['pd_id'],
			'em_fanta' => utf8_encode($row['em_fanta']),
			'pd_cod' => $row['pd_cod'],
			'pd_empresa' => $row['pd_empresa'],
			'pd_matriz' => $row['pd_matriz'],
			'pd_desc' => utf8_encode($row['pd_desc']),
			'pd_un' => utf8_encode($row['pd_un']),
			'pd_subgrupo' => $row['pd_subgrupo'],
			'sbp_descricao' => utf8_encode($row['sbp_descricao']),

		));
	}

	//echo $sql;
	return $resultado;

}

function relatorioTotalRegistro($conexao, $empresaMatriz, $pd_empresa, $pd_subgrupo, $es_est) {

	$resultado = array();

	$sql = "SELECT produtos.pd_id, empresas.em_fanta ,produtos.pd_cod, produtos.pd_empresa, produtos.pd_matriz, produtos.pd_desc, produtos.pd_un, produtos.pd_subgrupo,
subgrupo_prod.sbp_descricao
FROM zmpro.produtos
left join subgrupo_prod on (produtos.pd_subgrupo = subgrupo_prod.sbp_codigo)
left join empresas on(produtos.pd_empresa = empresas.em_cod)
 where pd_matriz = $empresaMatriz
 $pd_empresa
 $pd_subgrupo
 order by pd_desc;";

	$query = mysqli_query($conexao, $sql);

	array_push($resultado, array(
		'total' => mysqli_num_rows($query),
	));

	//echo $sql;

	return $resultado;

}

?>