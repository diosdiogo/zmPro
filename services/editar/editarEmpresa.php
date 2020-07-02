<?php

$lista = '{"result":[' . json_encode(dadosEmpresa($conexao, $id)) . ']}';
echo $lista;


function dadosEmpresa($conexao, $id) {
	$retorno = array();

	$sql = "select * from empresas where em_cod = (select us_empresa from usuarios where us_id = {$id});";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'em_cod' => $row['em_cod'],
			'em_razao' => utf8_encode($row['em_razao']),
			'em_fanta' => utf8_encode($row['em_fanta']),
			'em_end' => utf8_encode($row['em_end']),
			'em_end_num' => $row['em_end_num'],
			'em_bairro' => utf8_encode($row['em_bairro']),
			'em_cid' => utf8_encode($row['em_cid']),
			'em_uf' => utf8_encode($row['em_uf']),
			'em_cep' => utf8_encode($row['em_cep']),
			'em_cnpj' => $row['em_cnpj'],
			'em_insc' => $row['em_insc'],
			'em_fone' => $row['em_fone'],
			'em_email' => utf8_encode($row['em_email']),
			'em_responsavel' => utf8_encode($row['em_responsavel']),
			'em_cont_nome' => utf8_encode($row['em_cont_nome']),
			'em_cont_fone' => utf8_encode($row['em_cont_fone']),

		));
	}

	return $retorno;
}

?>