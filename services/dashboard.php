<?php

function dashboardTotalCont($conexao, $matriz){
    $dados = array();
	$resultado = mysqli_query($conexao,"SELECT count(vd_id)as total FROM zmpro.condicional where vd_matriz= $matriz;");
	$dados = mysqli_fetch_assoc($resultado);
	return $dados;
}
function dashboardTotalVendas($conexao, $matriz, $ano, $mes){
    $dados = array();
	$resultado = mysqli_query($conexao,"SELECT count(vd_id) as total FROM zmpro.vendas where vd_matriz=$matriz and year(vd_emis) = $ano and month(vd_emis) = $mes;");
	$dados = mysqli_fetch_assoc($resultado);
	return $dados;
}

function dashboardTotalVendasValor($conexao, $matriz, $ano, $mes){
    $dados = array();
	$resultado = mysqli_query($conexao,"SELECT SUM(vd_total) as total FROM zmpro.vendas where vd_matriz=$matriz and year(vd_emis) = $ano and month(vd_emis) = $mes;");
	$dados = mysqli_fetch_assoc($resultado);
	return $dados;
}

function dashboardTotalVendasValorHoje($conexao, $matriz, $mes){
    $dados = array();
	$resultado = mysqli_query($conexao,"SELECT SUM(vd_total) as total FROM zmpro.vendas where vd_matriz=$matriz and vd_emis =  '$mes';");
	$dados = mysqli_fetch_assoc($resultado);
	return $dados;
}