<?php
require_once 'conecta.php';

$empresaMatriz = base64_decode($_GET['e']);

$empresaAcesso = base64_decode($_GET['eA']);

$lista = '{"result":[' . json_encode(formaPagamento($conexao, $empresaMatriz)) . ']}';
echo $lista;

function formaPagamento($conexao, $empresaMatriz) {

	$retorno = array();

	$sql = "SELECT * FROM tipo_docto where dc_matriz = $empresaMatriz;";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(

			'dc_id' => $row['dc_id'],
			'dc_empr' => $row['dc_empr'],
			'dc_matriz' => $row['dc_matriz'],
			'dc_codigo' => $row['dc_codigo'],
			'dc_descricao' => utf8_encode($row['dc_descricao']),
			'dc_sigla' => utf8_encode($row['dc_sigla']),
			'dc_banco' => $row['dc_banco'],
			'dc_tipo_valor' => utf8_encode($row['dc_tipo_valor']),
			'dc_tipo_condicao' => $row['dc_tipo_condicao'],
			'dc_comportamento' => $row['dc_comportamento'],
			'dc_descto' => $row['dc_descto'],
			'dc_balcao' => $row['dc_balcao'],
			'dc_primeiraqnt' => $row['dc_primeiraqnt'],
			'dc_juro' => $row['dc_juro'],
			'dc_parcelas' => $row['dc_parcelas'],

		));

	}
	//echo $sql;
	return $retorno;
}
?>