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

if (isset($_GET['produtos'])) {

	$produtos = $_GET['produtos'];

	if ($produtos == null) {
		$pd_id = '';
	} else {
		$pd_id = '  and pd_id= ' . $produtos;
	}
} else {
	$pd_id = '';
}

if (isset($_GET['funcionario'])) {
	$funcionario = $_GET['funcionario'];

	if ($funcionario == null) {
		$vd_func = '';
	} else {
		$vd_func = ' and vd_func=' . $funcionario;
	}
} else {
	$vd_func = '';
}

//$EditarCadastrarExcluir = $_GET['EditarCadastrarExcluir'];


//$array = json_decode(file_get_contents("php://input"), true);

//$id = $_GET['id'];

if (isset($_GET['dadosProdutosSimplificado'])) {

	$lista = '{"result":[' . json_encode(dadosProdutosSimplificado($conexao, $empresaMatriz, $pd_empresa)) . ']}';
	echo $lista;

} elseif (isset($_GET['perfilCompletoEmpresa'])) {

	$lista = '{"result":[' . json_encode(perfilCompletoEmpresa($conexao, $empresa)) . ']}';
	echo $lista;

}

function dadosProdutosSimplificado($conexao, $empresaMatriz, $pd_empresa) {

	$retorno = array();

	$sql = "select 	pd_id,
					pd_cod,
					(select em_fanta from empresas where em_cod = pd_empresa),
					pd_desc,
					pd_vista,
					pd_prazo,
					pd_codinterno,
					pd_subgrupo
 			from produtos where pd_ativo = 'S' and pd_matriz = $empresaMatriz $pd_empresa;";

	$query = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($retorno, array(
				'pd_id' => $row['pd_id'],
				'pd_cod' => $row['pd_cod'],
				'pd_empresa' => $row['pd_empresa'],
				'pd_desc' => ucwords(strtolower(utf8_encode($row['pd_desc']))),
				'pd_vista' => utf8_encode($row['pd_vista']),
				'pd_prazo' => utf8_encode($row['pd_prazo']),
				'pd_codinterno' => $row['pd_codinterno'],
				'pd_subgrupo' => $row['pd_subgrupo'],
		));
	}

	//echo $sql;

	return $retorno;
}

?>
