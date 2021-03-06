<?php

include 'conecta.php';

$id = $_GET['id'];

$lista = '{"result":[' . json_encode(dadosCliente($conexao, $id)) . ']}';
echo $lista;

function dadosCliente($conexao, $id) {
	$retorno = array();

	$sql = "select * from pessoas where pe_id= $id";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'pe_id' => $row['pe_id'],
			'pe_cod' => $row['pe_cod'],
			'pe_empresa' => $row['pe_empresa'],
			'pe_matriz' => $row['pe_matriz'],
			'pe_nome' => ucwords(strtolower(utf8_encode($row['pe_nome']))),
			'pe_situacao' => $row['pe_situacao'],
			//'pe_endereco' => strstr(utf8_encode($row['pe_endereco']), ',', true),
			'pe_endereco' => ucwords(strtolower(utf8_encode($row['pe_endereco']))),
			'pe_end_num' => $row['pe_end_num'],
			'pe_end_comp' => ucwords(strtolower(utf8_encode($row['pe_end_comp']))),
			'pe_cidade' => ucwords(strtolower(utf8_encode($row['pe_cidade']))),
			'pe_uf' => utf8_encode($row['pe_uf']),
			'pe_bairro' => ucwords(strtolower(utf8_encode($row['pe_bairro']))),
			'pe_cep' => utf8_encode($row['pe_cep']),
			'pe_rgie' => utf8_encode($row['pe_rgie']),
			'pe_cpfcnpj' => utf8_encode($row['pe_cpfcnpj']), 
			'pe_fone' => utf8_encode($row['pe_fone']),
			'pe_fanta' => ucwords(strtolower(utf8_encode($row['pe_fanta']))),
			'pe_email' => utf8_encode($row['pe_email']),
			'pe_site' => utf8_encode($row['pe_site']),
			'pe_cont' => ucwords(strtolower(utf8_encode($row['pe_cont']))),
			'pe_fonecont' => utf8_encode($row['pe_fonecont']),
			'pe_obs' => ucwords(strtolower(utf8_encode($row['pe_obs']))),
			'pe_entrega' => utf8_encode($row['pe_entrega']),
			'pe_cidentrega' => ucwords(strtolower(utf8_encode($row['pe_cidentrega']))),
			'pe_bairroentrega' => ucwords(strtolower(utf8_encode($row['pe_bairroentrega']))),
			'pe_ufentrega' => utf8_encode($row['pe_ufentrega']),
			'pe_foneentrega' => utf8_encode($row['pe_foneentrega']),
			'pe_contentrega' => utf8_encode($row['pe_contentrega']),
			'pe_cobranca' => utf8_encode($row['pe_cobranca']),
			'pe_cidcobranca' => ucwords(strtolower(utf8_encode($row['pe_cidcobranca']))),
			'pe_ufcobranca' => utf8_encode($row['pe_ufcobranca']),
			'pe_bairrocobranca' => ucwords(strtolower(utf8_encode($row['pe_bairrocobranca']))),
			'pe_fonecobranca' => utf8_encode($row['pe_fonecobranca']),
			'pe_contcobranca' => utf8_encode($row['pe_contcobranca']),
			'pe_nascto' => utf8_encode($row['pe_nascto']),
			'pe_limite' => $row['pe_limite'],
			'pe_celular' => utf8_encode($row['pe_celular']),
			'pe_trabalho' => utf8_encode($row['pe_trabalho']),
			'pe_funcao' => ucwords(strtolower(utf8_encode($row['pe_funcao']))),
			'pe_endtrab' => ucwords(strtolower(utf8_encode($row['pe_endtrab']))),
			'pe_fonetrab' => utf8_encode($row['pe_fonetrab']),
			'pe_tempotrab' => utf8_encode($row['pe_tempotrab']),
			'pe_salario' => $row['pe_salario'],
			'pe_conttrab' => utf8_encode($row['pe_conttrab']),
			'pe_conj' => ucwords(strtolower(utf8_encode($row['pe_conj']))),
			'pe_conjtrab' => utf8_encode($row['pe_conjtrab']),
			'pe_funcconj' => utf8_encode($row['pe_funcconj']),
			'pe_endtrabconj' => ucwords(strtolower(utf8_encode($row['pe_endtrabconj']))),
			'pe_fonetrabconj' => utf8_encode($row['pe_fonetrabconj']),
			'pe_tempotrabconj' => utf8_encode($row['pe_tempotrabconj']),
			'pe_salarioconj' => utf8_encode($row['pe_salarioconj']),
			'pe_conttrabconj' => utf8_encode($row['pe_conttrabconj']),
			'pe_cpfconj' => utf8_encode($row['pe_cpfconj']),
			'pe_nasctoconj' => utf8_encode($row['pe_nasctoconj']),
			'pe_celconj' => utf8_encode($row['pe_celconj']),
			'pe_banco1' => ucwords(strtolower(utf8_encode($row['pe_banco1']))),
			'pe_ag1' => utf8_encode($row['pe_ag1']),
			'pe_cc1' => utf8_encode($row['pe_cc1']),
			'pe_contbanco1' => utf8_encode($row['pe_contbanco1']),
			'pe_banco2' => utf8_encode($row['pe_banco2']),
			'pe_ag2' => utf8_encode($row['pe_ag2']),
			'pe_cc2' => utf8_encode($row['pe_cc2']),
			'pe_contbanco2' => utf8_encode($row['pe_contbanco2']),
			'pe_primeiracompra' => utf8_encode($row['pe_primeiracompra']),
			'pe_valprimcompra' => $row['pe_valprimcompra'],
			'pe_maiorcompra' => utf8_encode($row['pe_maiorcompra']),
			'pe_valmaiorcompra' => $row['pe_valmaiorcompra'],
			'pe_ultcompra' => utf8_encode($row['pe_ultcompra']),
			'pe_valultcompra' => utf8_encode($row['pe_valultcompra']),
			'pe_cadastro' => utf8_encode($row['pe_cadastro']),
			'pe_regiao' => $row['pe_regiao'],
			'pe_funcionario' => $row['pe_funcionario'],
			'pe_natural' => ucwords(strtolower(utf8_encode($row['pe_natural']))),
			'pe_valresid' => $row['pe_valresid'],
			'pe_credito' => $row['pe_credito'],
			'pe_vencto' => $row['pe_vencto'],
			'pe_descto' => $row['pe_descto'],
			'pe_novo' => utf8_encode($row['pe_novo']),
			'pe_observ' => utf8_encode($row['pe_observ']),
			'pe_cepentrega' => utf8_encode($row['pe_cepentrega']),
			'pe_cepcobranca' => utf8_encode($row['pe_cepcobranca']),
			'pe_cod_cid' => utf8_encode($row['pe_cod_cid']),
			'pe_cli_prim' => $row['pe_cli_prim'],
			'pe_isento_ipi' => utf8_encode($row['pe_isento_ipi']),
			'pe_ativo' => utf8_encode($row['pe_ativo']),
			'pe_email_nfe' => utf8_encode($row['pe_email_nfe']),
			'pe_foto' => utf8_encode($row['pe_foto']),
			'pe_fone1' => utf8_encode($row['pe_fone1']),
			'pe_fone2' => utf8_encode($row['pe_fone2']),
			'pe_fone3' => utf8_encode($row['pe_fone3']),
			'pe_fone4' => utf8_encode($row['pe_fone4']),
			'pe_fone5' => utf8_encode($row['pe_fone5']),
			'pe_nome1' => ucwords(strtolower(utf8_encode($row['pe_nome1']))),
			'pe_nome2' => ucwords(strtolower(utf8_encode($row['pe_nome2']))),
			'pe_nome3' => ucwords(strtolower(utf8_encode($row['pe_nome3']))),
			'pe_nome4' => ucwords(strtolower(utf8_encode($row['pe_nome4']))),
			'pe_nascto1' => utf8_encode($row['pe_nascto1']),
			'pe_nascto2' => utf8_encode($row['pe_nascto2']),
			'pe_nascto3' => utf8_encode($row['pe_nascto3']),
			'pe_nascto4' => utf8_encode($row['pe_nascto4']),
			'pe_nascto5' => utf8_encode($row['pe_nascto5']),
			'pe_fornecedor' => utf8_encode($row['pe_fornecedor']),
			'pe_cliente' => utf8_encode($row['pe_cliente']),
			'pe_colaborador' => utf8_encode($row['pe_colaborador']),
			'pe_vendedor' => utf8_encode($row['pe_vendedor']),

		));
	}

	return $retorno;
}

?>