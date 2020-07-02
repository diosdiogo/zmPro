<?php

$cep = limpaCep($_GET['cep']);
$ch = curl_init("https://viacep.com.br/ws/" . $cep . "/json/unicode/");

curl_setopt($ch, CURLOPT_HEADER, 0);

$response = curl_exec($ch);

function limpaCep($valor) {
	$valor = trim($valor);
	$valor = str_replace(".", "", $valor);
	$valor = str_replace(",", "", $valor);
	$valor = str_replace("-", "", $valor);
	$valor = str_replace("/", "", $valor);
	return $valor;
}

?>