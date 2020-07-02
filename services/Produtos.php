<?php

require_once 'conecta.php';

$empresaMatriz = base64_decode($_GET['e']);

$empresaAcesso = base64_decode($_GET['eA']);

if (isset($_GET['empresa'])) {
	$empresa = $_GET['empresa'];

	if ($empresa == null) {
		$pd_empresa = '';

	} else {
		$pd_empresa = ' and pd_empresa= ' . $empresa;
	}

} else {
	$pd_empresa = '';
}

if (isset($_GET['produtos'])) {

	$produtos = $_GET['produtos'];

	if ($produtos == null) {
		$pd_id = '';
	} else {
		$pd_id = '  and pd_id= ' . $produtos;
	}
} else {
	$pd_id = '';
}

if (isset($_GET['funcionario'])) {
	$funcionario = $_GET['funcionario'];

	if ($funcionario == null) {
		$vd_func = '';
	} else {
		$vd_func = ' and vd_func=' . $funcionario;
	}
} else {
	$vd_func = '';
}

//$EditarCadastrarExcluir = $_GET['EditarCadastrarExcluir'];


//$array = json_decode(file_get_contents("php://input"), true);

//$id = $_GET['id'];

if (isset($_GET['dadosProdutosSimplificado'])) {

	$lista = '{"result":[' . json_encode(dadosProdutosSimplificado($conexao, $empresaMatriz, $pd_empresa)) . ']}';
	echo $lista;

} elseif (isset($_GET['perfilCompletoEmpresa'])) {

	$lista = '{"result":[' . json_encode(perfilCompletoEmpresa($conexao, $empresa)) . ']}';
	echo $lista;

}

if(isset($_GET['visualizarProdutos'])){
	$pd_id = $_GET['pd_id'];
	$lista = '{"result":[' . json_encode(getProduto($conexao, $empresaMatriz, $pd_id)) . ']}';
	echo $lista;
}
function dadosProdutosSimplificado($conexao, $empresaMatriz, $pd_empresa) {

	$retorno = array();

	$sql = "select pd_id, pd_cod, pd_marca, (select em_fanta from empresas where em_cod = pd_empresa) as pd_empresa, 
	pd_desc, pd_vista, pd_prazo, pd_codinterno, pd_subgrupo, 
	(SELECT sbp_descricao from subgrupo_prod where sbp_codigo=pd_subgrupo and sbp_empresa=pd_empresa) as pd_subgrupoDesc
	
 			from produtos where pd_ativo = 'S' and pd_matriz = $empresaMatriz $pd_empresa ORDER BY pd_desc LIMIT 100 OFFSET 0";

	$query = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($retorno, array(
				'pd_id' => $row['pd_id'],
				'pd_cod' => $row['pd_cod'],
				'pd_empresa' => ucwords(strtolower(utf8_encode($row['pd_empresa']))),
				'pd_desc' => ucwords(strtolower(utf8_encode($row['pd_desc']))),
				'pd_marca'=> ucwords(strtolower(utf8_encode($row['pd_marca']))),
				'pd_vista' => utf8_encode($row['pd_vista']),
				'pd_prazo' => utf8_encode($row['pd_prazo']),
				'pd_codinterno' => $row['pd_codinterno'],
				'pd_subgrupo' => $row['pd_subgrupo'],
				'pd_subgrupoDesc'=> ucwords(strtolower(utf8_encode($row['pd_subgrupoDesc']))),
		));
	}

	//echo $sql;

	return $retorno;
}

function getProduto($conexao, $empresaMatriz, $pd_id){
	//função tem que terminar, não funcionando ainda
	$retorno = array();

	$sql = "select * from produtos where pd_id=$pd_id and pd_matriz = $empresaMatriz;";
	
	$query = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($query)) {
		array_push($retorno, array(
			'pd_id' => $row['pd_id'],
			'pd_cod' => $row['pd_cod'],
			'pd_ean'=> $row['pd_ean'],
			'pd_codinterno' => $row['pd_codinterno'],
			'pd_un'=>$row['pd_un'],
			'pd_desc' => $row['pd_desc'],
			'pd_marca'=>$row['pd_marca'],
			'pd_localizacao' => $row['pd_localizacao'],
			'pd_st1' => $row['pd_st1'],
			'pd_st2'=> $row['pd_st2'],
			'pd_ncm' => $row['pd_ncm'],
			'pd_subgrupo'=>$row['pd_subgrupo'],
			'pd_csosn' =>$row['pd_csosn'],
			'pd_custo' =>$row['pd_custo'],
			'pd_vista' => $row['pd_vista'],
			'pd_markup' => $row['pd_markup'],
			'es_est' => estoque($conexao, $row['pd_id']),
			/*

pd_empresa
pd_matriz

pd_cor
pd_tam
pd_pascomis


pd_vista
pd_prazo
pd_datacad
pd_comis
pd_promocao
pd_codinterno
pd_localizacao
pd_ultcompra
pd_nota
pd_custofab
pd_subgrupo
pd_desc1
pd_desc2
pd_desc3

pd_un
pd_cf

pd_dtinp
pd_dtfnp
pd_vd1
pd_vd2
pd_vd3
pd_dataalt
pd_horaalt
pd_valorant
pd_frete
pd_ipi
pd_encargos
pd_tempocobert
pd_alterado
pd_peso
pd_ativo
pd_ultvenda
pd_m3
pd_grade
pd_tipoicm
pd_customedio
pd_icmscompra
pd_icmsvenda
pd_autor
pd_nomecient
pd_codfab
pd_codorig
pd_volume
pd_origemfab

pd_monofasico
pd_codengenharia
pd_tempoproducao
pd_uncompra
pd_indiceun
pd_margsubtrib
pd_codgrade
pd_serv


pd_valatacado
pd_st1_cp
pd_st2_cp
pd_st_ipi
pd_st_ipi_cp
pd_st_pis
pd_st_pis_cp
pd_st_cofins
pd_st_cofins_cp
pd_codtab421
pd_aliq_pis_vd
pd_aliq_pis_cp
pd_aliq_cofins_vd
pd_aliq_cofins_cp
pd_qntemb
pd_confsaida
pd_perc_st
pd_perc_frete
pd_lanca_site
pd_dimen1
pd_dimen2
pd_dimen3
pd_icmsval
pd_ipival
pd_pisval
pd_cofinsval
pd_iival
pd_perc
pd_destaquesite
pd_st_empr_st
pd_foto
pd_observ
pd_reducao
pd_cfopdest
pd_cfopfest
pd_cod_codif
pd_cod_anp
pd_atualizast
pd_val1
pd_val2
pd_val3
pd_val4
pd_val5
pd_val6
pd_val7
pd_val8
pd_val9
pd_val10
pd_val11
pd_val12
pd_val13
pd_val14
pd_val15
pd_val16
pd_val17
pd_val18
pd_valadd
pd_cenqipi
pd_cest
pd_fcp
pd_quantfixa
pd_desc_anp
pd_sobre_encomenda
pd_un_trib
pd_qnt_trib
pd_bcstret
pd_pstret
pd_icmsstret
pd_icmssubstituto
pd_cod_anvisa
pd_pmc
pd_motivo_anvisa
pd_garc_lanca
pd_impressora
pd_cod_benef
pd_disk
pd_salao
pd_foto_url
*/
		));
	}
}

function estoque($conexao, $id){

	$sql="select es_est from estoque  where es_prod = (select pd_cod from produtos where pd_id=11 and pd_empresa=es_empr);";

	$query = mysqli_query($conexao, $sql);

	$return = mysqli_fetch_assoc($query);

	return $return['es_est'];
	
}
?>
