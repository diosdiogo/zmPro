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
		$es_est = ' and es_est > 0';
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

	if (isset($_GET['relatorioSubGrupo'])) {

		$lista = '{"result":[' . json_encode(relatorioSubGrupo($conexao, $empresaMatriz, $pd_empresa, $pd_subgrupo, $es_est)) . ']}';
		echo $lista;
	}

	if (isset($_GET['dadosRelatorio'])) {

		$lista = '{"result":[' . json_encode(relatorio($conexao, $empresaMatriz, $pd_empresa, $pd_subgrupo, $es_est)) . ']}';
		echo $lista;

	}

	if (isset($_GET['relatorioTotalRegistro'])) {
		$lista = '{"result":[' . json_encode(relatorioTotalRegistro($conexao, $empresaMatriz, $pd_empresa, $pd_subgrupo, $es_est)) . ']}';
		echo $lista;
	}

	if (isset($_GET['relatorioTotalGrupoBy'])) {

		$lista = '{"result":[' . json_encode(relatorioTotalGrupoBy($conexao, $empresaMatriz, $pd_empresa, $pd_subgrupo, $es_est)) . ']}';
		echo $lista;
	}

	if (isset($_GET['relatorioTotal'])) {

		$lista = '{"result":[' . json_encode(relatorioTotalGeral($conexao, $empresaMatriz, $pd_empresa, $pd_subgrupo, $es_est)) . ']}';
		echo $lista;
	}

}

function relatorioSubGrupo($conexao, $empresaMatriz, $pd_empresa, $pd_subgrupo, $es_est) {
	$resultado = array();

	$sql = "SELECT (SELECT sbp_descricao FROM subgrupo_prod where subgrupo_prod.sbp_codigo = produtos.pd_subgrupo
and subgrupo_prod.sbp_matriz = produtos.pd_matriz) as descricao
 FROM produtos
 left join estoque on(pd_cod=es_prod)
 left join empresas on (pd_empresa = em_cod)
 where pd_matriz = $empresaMatriz
 $pd_empresa
 $pd_subgrupo
 $es_est
 group by descricao, pd_subgrupo, pd_matriz
 order by descricao asc;";

	$query = mysqli_query($conexao, $sql);

	

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($resultado, array(

			'descricao' => utf8_encode($row['descricao']),

		));
	}

	//echo $sql;
	return $resultado;

}

function relatorio($conexao, $empresaMatriz, $pd_empresa, $pd_subgrupo, $es_est) {
	$resultado = array();
	$sql = "SELECT pd_id, pd_cod, pd_empresa, empresas.em_fanta, pd_matriz, pd_desc, es_est, pd_un, pd_marca,
pd_custo, pd_vista, pd_subgrupo,
(SELECT sbp_descricao FROM subgrupo_prod where subgrupo_prod.sbp_codigo = produtos.pd_subgrupo
and subgrupo_prod.sbp_matriz = produtos.pd_matriz) as descricao,
(pd_custo * es_est) as c_total, ( pd_vista * es_est) as v_total
 FROM produtos
 left join estoque on(pd_cod=es_prod)
 left join empresas on (pd_empresa = em_cod)
 where pd_matriz = $empresaMatriz
 $pd_empresa
 $pd_subgrupo
 $es_est
 order by descricao asc;";

	$query = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($resultado, array(

			'pd_id' => $row['pd_id'],
			'pd_cod' => $row['pd_cod'],
			'pd_empresa' => $row['pd_empresa'],
			'em_fanta' => utf8_encode($row['em_fanta']),
			'pd_matriz' => $row['pd_matriz'],
			'pd_desc' => utf8_encode($row['pd_desc']),
			'es_est' => $row['es_est'],
			'pd_un' => utf8_encode($row['pd_un']),
			'pd_marca' => $row['pd_marca'],
			'pd_custo' => $row['pd_custo'],
			'pd_vista' => $row['pd_vista'],
			'pd_subgrupo' => $row['pd_subgrupo'],
			'descricao' => utf8_encode($row['descricao']),
			'c_total' => $row['c_total'],
			'v_total' => $row['v_total'],

		));
	}

	echo $sql;
	return $resultado;

}

function relatorioTotalRegistro($conexao, $empresaMatriz, $pd_empresa, $pd_subgrupo, $es_est) {

	$resultado = array();

	$sql = "SELECT pd_id, pd_cod, pd_empresa, empresas.em_fanta, pd_matriz, pd_desc, es_est, pd_un, pd_marca,
pd_custo, pd_vista, pd_subgrupo,
(SELECT sbp_descricao FROM subgrupo_prod where subgrupo_prod.sbp_codigo = produtos.pd_subgrupo
and subgrupo_prod.sbp_matriz = produtos.pd_matriz) as descricao,
(pd_custo * es_est) as c_total, ( pd_vista * es_est) as v_total
 FROM produtos
 left join estoque on(pd_cod=es_prod)
 left join empresas on (pd_empresa = em_cod)
 where pd_matriz = $empresaMatriz
 $pd_empresa
 $pd_subgrupo
$es_est
 order by descricao asc;";

	$query = mysqli_query($conexao, $sql);

	array_push($resultado, array(
		'total' => mysqli_num_rows($query),
	));

	//echo $sql;

	return $resultado;

}

function relatorioTotalGrupoBy($conexao, $empresaMatriz, $pd_empresa, $pd_subgrupo, $es_est) {

	$resultado = array();

	$sql = "SELECT sum(es_est) as es_est , pd_subgrupo,
(SELECT sbp_descricao FROM subgrupo_prod where subgrupo_prod.sbp_codigo = produtos.pd_subgrupo
and subgrupo_prod.sbp_matriz = produtos.pd_matriz) as descricao,
sum((pd_custo * es_est)) as c_total,
sum(( pd_vista * es_est)) as v_total
 FROM produtos left join estoque on(pd_cod=es_prod)
 left join empresas on (pd_empresa = em_cod)
 where pd_matriz = $empresaMatriz
$es_est
 $pd_empresa
 /*and pd_subgrupo=15;*/
 $pd_subgrupo
 group by pd_subgrupo, pd_matriz
 order by descricao asc;";

	$query = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($resultado, array(

			'es_est' => $row['es_est'],
			'pd_subgrupo' => $row['pd_subgrupo'],
			'descricao' => utf8_encode($row['descricao']),
			'c_total' => $row['c_total'],
			'v_total' => $row['v_total'],

		));
	}

	//echo $sql;
	return $resultado;
}

function relatorioTotalGeral($conexao, $empresaMatriz, $pd_empresa, $pd_subgrupo, $es_est) {

	$resultado = array();

	$sql = "SELECT sum(es_est) as es_est ,
(SELECT sbp_descricao FROM subgrupo_prod where subgrupo_prod.sbp_codigo = produtos.pd_subgrupo
and subgrupo_prod.sbp_matriz = produtos.pd_matriz) as descricao,
sum((pd_custo * es_est)) as c_total,
sum(( pd_vista * es_est)) as v_total
 FROM produtos left join estoque on(pd_cod=es_prod)
 left join empresas on (pd_empresa = em_cod)
 where pd_matriz = $empresaMatriz
 $es_est
 $pd_empresa
 /*and pd_subgrupo=15*/
 $pd_subgrupo;";

	$query = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($resultado, array(

			'es_est' => $row['es_est'],
			'c_total' => $row['c_total'],
			'v_total' => $row['v_total'],

		));
	}

	return $resultado;
}

?>