<?php

$cnpj = limpaCPF_CNPJ($_GET['cnpj']);
$ch = curl_init("http://receitaws.com.br/v1/cnpj/" . $cnpj . "");

curl_setopt($ch, CURLOPT_HEADER, 0);

$response = curl_exec($ch);

function limpaCPF_CNPJ($valor) {
	$valor = trim($valor);
	$valor = str_replace(".", "", $valor);
	$valor = str_replace(",", "", $valor);
	$valor = str_replace("-", "", $valor);
	$valor = str_replace("/", "", $valor);
	return $valor;
}

?>