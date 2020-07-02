<?php
//include 'conecta.php';

function logSistema($conexao, $data, $hora, $ip, $us, $msg, $empresa, $matriz) {

	$sql = "insert into log (lg_data, lg_hora, lg_ip, lg_func_nome, lg_hist, lg_empresa,
lg_matriz
) value('$data', '$hora', '$ip', '$us', '$msg', $empresa, $matriz)";

	$inserir = mysqli_query($conexao, $sql);

	//echo $sql;
	return $inserir;
}

function logSistema_forID($conexao, $data, $hora, $ip, $us, $msg, $empresa, $matriz) {
	
	$sql = "insert into log (lg_data, lg_hora, lg_ip, lg_func_nome, lg_hist, lg_empresa,
lg_matriz
) value('$data', '$hora', '$ip', (SELECT us_nome FROM usuarios where us_id=$us), '$msg', $empresa, $matriz)";

	$inserir = mysqli_query($conexao, $sql);

	//echo $sql;
	return $inserir;
}

function logSistema_Baixar_Conta_Pagar_forOcorrencia($conexao, $data, $hora, $ip, $us, $msg, $empresa, $matriz) {
	
	$sql = "insert into log (lg_data, lg_hora, lg_ip, lg_func_nome, lg_hist, lg_empresa,
lg_matriz
) value('$data', '$hora', '$ip', (SELECT us_nome FROM usuarios where us_id=$us), concat('$msg',(SELECT max(dc_ocorrencia) FROM doctos where dc_matriz = $matriz)), $empresa, $matriz)";

	$inserir = mysqli_query($conexao, $sql);

	//echo $sql;
	return $inserir;
}


function logSistema_Movimentacao_forOcorrencia($conexao, $data, $hora, $ip, $us, $msg, $cx_id, $matriz) {
	
	$sql = "insert into log (lg_data, lg_hora, lg_ip, lg_func_nome, lg_hist, lg_empresa,
lg_matriz
) value('$data', '$hora', '$ip', (SELECT us_nome FROM usuarios where us_id=$us), concat('$msg',(SELECT cx_ocorrencia FROM caixa_aberto where cx_id =  $cx_id)), $matriz, $matriz)";

	$inserir = mysqli_query($conexao, $sql);

	//echo $sql;
	//return $inserir;
}

?>