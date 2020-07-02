<?php



// data de alteração 23/04/2020
//data de upload na nuvem 24/04/2020

if (isset($_GET['dadosEmpresa'])) {

	include 'conecta.php';
	
	$id = base64_decode($_GET['us_id']);
	$empresa = base64_decode($_GET['e']);

	$empresaAcesso = base64_decode($_GET['eA']);

	if ($empresaAcesso == 0) {
		$lista = '{"result":[' . json_encode(dadosEmpresaAutorizado($conexao, $id)) . ']}';
		echo $lista;
	} else {
		$lista = '{"result":[' . json_encode(dadosEmpresa($conexao, $id)) . ']}';
		echo $lista;
	}

}

function dadosEmpresaAutorizado($conexao, $id) {
	$retorno = array();

	$sql = "select * from empresas where em_cod_matriz = (select us_empresa from usuarios where us_id = $id);";

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
			'em_cnpj' => utf8_encode($row['em_cnpj']),
			'em_insc' => utf8_encode($row['em_insc']),
			'em_fone' => utf8_encode($row['em_fone']),
			'em_email' => utf8_encode($row['em_email']),
			'em_responsavel' => utf8_encode($row['em_responsavel']),
			'em_cont_nome' => utf8_encode($row['em_cont_nome']),
			'em_cont_fone' => utf8_encode($row['em_cont_fone']),
			'em_logo' => utf8_encode($row['em_logo']),

		));
	}

	return $retorno;
}

function dadosEmpresa($conexao, $id) {
	$retorno = array();

	$sql = "select * from empresas where em_cod = (select us_empresa_acesso from usuarios where us_id = $id);";

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
			'em_cnpj' => utf8_encode($row['em_cnpj']),
			'em_insc' => utf8_encode($row['em_insc']),
			'em_fone' => utf8_encode($row['em_fone']),
			'em_email' => utf8_encode($row['em_email']),
			'em_responsavel' => utf8_encode($row['em_responsavel']),
			'em_cont_nome' => utf8_encode($row['em_cont_nome']),
			'em_cont_fone' => utf8_encode($row['em_cont_fone']),
			'em_logo' => utf8_encode($row['em_logo']),

		));
	}

	return $retorno;
}
