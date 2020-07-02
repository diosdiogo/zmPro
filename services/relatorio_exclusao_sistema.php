<?php

require_once 'conecta.php';

$empresaMatriz = base64_decode($_GET['e']);

$empresaAcesso = base64_decode($_GET['eA']);

$dataI = $_GET['dataI'];
$dataF = $_GET['dataF'];

if (isset($_GET['empresa'])) {
	$empresa = $_GET['empresa'];

	if ($empresa == null) {
		$pd_empresa = '';

	} else {
		$pd_empresa = ' and ex_empresa= ' . $empresa;
	}

} else {
	$pd_empresa = '';
}

if (isset($_GET['funcionario'])) {
	$funcionario = $_GET['funcionario'];

	if ($funcionario == null) {
		$ex_func = '';
	} else {
		$ex_func = ' and ex_func = ' . $funcionario;
	}
} else {
	$ex_func = '';
}

if (isset($_GET['relatorio'])) {

	if (isset($_GET['dadosRelatorio'])) {

		$lista = '{"result":[' . json_encode(relatorio($conexao, $empresaMatriz, $pd_empresa, $dataI, $dataF, $ex_func)) . ']}';
		echo $lista;

	}

	if (isset($_GET['relatorioTotalGrupoBy'])) {
		$lista = '{"result":[' . json_encode(relatorioTotalGrupoBy($conexao, $empresaMatriz, $pd_empresa, $dataI, $dataF, $ex_func)) . ']}';
		echo $lista;

	}

	if (isset($_GET['relatorioTotal'])) {
		$lista = '{"result":[' . json_encode(relatorioTotal($conexao, $empresaMatriz, $pd_empresa, $dataI, $dataF, $ex_func)) . ']}';

		echo $lista;
	}
}

function relatorio($conexao, $empresaMatriz, $pd_empresa, $dataI, $dataF, $ex_func) {
	$resultado = array();
	$sql = "SELECT exclusoes.ex_id, exclusoes.ex_id_local, exclusoes.ex_empresa, empresas.em_fanta,
exclusoes.ex_matriz, exclusoes.ex_func,
(SELECT pe_nome FROM zmpro.pessoas WHERE pe_matriz=$empresaMatriz 
and pe_colaborador = 'S' 
and pe_cod = exclusoes.ex_func
and pe_empresa=ex_empresa) as pe_nome,
exclusoes.ex_descricao, exclusoes.ex_motivo, exclusoes.ex_data,
exclusoes.ex_hora, exclusoes.ex_autorizado
from exclusoes
left join empresas on(exclusoes.ex_empresa = empresas.em_cod)
where ex_matriz = $empresaMatriz
and ex_data between '$dataI' and '$dataF'
$pd_empresa
$ex_func
order by ex_data;";

	$query = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($resultado, array(

			'ex_id' => $row['ex_id'],
			'ex_id_local' => $row['ex_id_local'],
			'ex_empresa' => $row['ex_empresa'],
			'em_fanta' => utf8_encode($row['em_fanta']),
			'ex_matriz' => $row['ex_matriz'],
			'ex_func' => $row['ex_func'],
			'pe_nome' => utf8_encode($row['pe_nome']),
			'ex_descricao' => utf8_encode($row['ex_descricao']),
			'ex_motivo' => utf8_encode($row['ex_motivo']),
			'ex_data' => utf8_encode($row['ex_data']),
			'ex_hora' => utf8_encode($row['ex_hora']),
			'ex_autorizado' => $row['ex_autorizado'],

		));
	}

	//echo $sql;
	return $resultado;

}

function relatorioTotalGrupoBy($conexao, $empresaMatriz, $pd_empresa, $dataI, $dataF, $ex_func) {

	$resultado = array();

	$sql = "SELECT (SELECT pe_nome FROM zmpro.pessoas WHERE pe_matriz=$empresaMatriz 
	and pe_colaborador = 'S' 
	and pe_cod = exclusoes.ex_func
	and pe_empresa=ex_empresa) as pe_nome
from exclusoes
left join empresas on(exclusoes.ex_matriz = empresas.em_cod)
where ex_matriz = $empresaMatriz
and ex_data between '$dataI' and '$dataF'
$pd_empresa
$ex_func
group by  pe_nome
order by ex_data;";

	$query = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($query)) {
		array_push($resultado, array(
			'pe_nome' => utf8_encode($row['pe_nome']),
		));

	}

	return $resultado;

}

function relatorioTotal($conexao, $empresaMatriz, $pd_empresa, $dataI, $dataF, $ex_func) {

	$resultado = array();

	$sql = "SELECT exclusoes.ex_id, exclusoes.ex_id_local, exclusoes.ex_empresa, empresas.em_fanta,
exclusoes.ex_matriz, exclusoes.ex_func,
(SELECT pe_nome FROM zmpro.pessoas WHERE pe_matriz=$empresaMatriz 
and pe_colaborador = 'S' 
and pe_cod = exclusoes.ex_func
and pe_empresa=ex_empresa) as pe_nome,
exclusoes.ex_descricao, exclusoes.ex_motivo, exclusoes.ex_data,
exclusoes.ex_hora, exclusoes.ex_autorizado
from exclusoes
left join empresas on(exclusoes.ex_empresa = empresas.em_cod)
where ex_matriz = $empresaMatriz
and ex_data between '$dataI' and '$dataF'
$pd_empresa
$ex_func
order by ex_data;";

	$query = mysqli_query($conexao, $sql);
	array_push($resultado, array(
		'total' => mysqli_num_rows($query),
	));

	return $resultado;

}

?>