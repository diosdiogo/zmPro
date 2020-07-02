<?php

$limpacpfcnpj = limpaCPF_CNPJ($_GET['cpfcnpj']);
$cpfcnpj = formataCPF_CNPJ($limpacpfcnpj);

echo $cpfcnpj;

function formataCPF_CNPJ($valor) {
  $valor = preg_replace("/\D/", '', $valor);
  if (strlen($valor) === 11) {
    return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $valor);
  } 
  
  return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $valor);
}

function limpaCPF_CNPJ($valor) {
	$valor = trim($valor);
	$valor = str_replace(".", "", $valor);
	$valor = str_replace(",", "", $valor);
	$valor = str_replace("-", "", $valor);
	$valor = str_replace("/", "", $valor);
	return $valor;
}

?>