<?php
require_once 'conecta.php';

$empresaMatriz = base64_decode($_GET['e']);

$empresaAcesso = base64_decode($_GET['eA']);

$lista = '{"result":[' . json_encode(subgrupo($conexao, $empresaMatriz)) . ']}';
echo $lista;

function subgrupo($conexao, $empresaMatriz) {
	$resultado = array();

	$sql = "SELECT * FROM subgrupo_prod where sbp_matriz = $empresaMatriz;";

	$query = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($resultado, array(
			'sbp_id' => $row['sbp_id'],
			'sbp_empresa' => $row['sbp_empresa'],
			'sbp_matriz' => $row['sbp_matriz'],
			'sbp_codigo' => $row['sbp_codigo'],
			'sbp_descricao' => utf8_encode($row['sbp_descricao']),
			'sbp_grupo' => $row['sbp_grupo'],
			'sbp_impressora' => $row['sbp_impressora'],
			'sbp_grade' => $row['sbp_grade'],
			'sbp_tipo' => $row['sbp_tipo'],
			'sbp_desc' => $row['sbp_desc'],
			'sbp_comis' => $row['sbp_comis'],

		));

	}

	//echo $sql;
	return $resultado;
}

?>
