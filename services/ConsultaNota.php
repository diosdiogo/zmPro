<?php

require_once 'conecta.php';

$empresaMatriz = base64_decode($_GET['e']);

$empresaAcesso = base64_decode($_GET['eA']);

if (isset($_GET['empresa'])) {
	$empresa = $_GET['empresa'];

	if ($empresa == null) {
		$empresa = '';

	} else {
		$empresa = ' and cp_empresa = ' . $empresa;
	}

} else {
	$empresa = '';
}

if (isset($_GET['dataI'])) {
	$dataI = $_GET['dataI'];
} else {
	$dataI = '';
}

if (isset($_GET['dataF'])) {
	$dataF = $_GET['dataF'];
} else {
	$dataF = '';
}

if (isset($_GET['cp_id'])) {
	$cp_id = $_GET['cp_id'];
} else {
	$cp_id = '';
}

if (isset($_GET['funcionario'])) {
	$funcionario = $_GET['funcionario'];
	if ($funcionario == null) {
		$vd_func = '';
	}
} else {
	$vd_func = '';
}

//$EditarCadastrarExcluir = $_GET['EditarCadastrarExcluir'];


//$array = json_decode(file_get_contents("php://input"), true);

//$id = $_GET['id'];

if (isset($_GET['dadosNotaEntrada'])) {

	$lista = '{"result":[' . json_encode(dadosNotaEntrada($conexao, $empresaMatriz, $empresa, $dataI, $dataF)) . ']}';
	echo $lista;

} 

if (isset($_GET['dadosNotaSaida'])) {

	$lista = '{"result":[' . json_encode(dadosNotaSaida($conexao, $empresaMatriz, $empresa, $dataI, $dataF)) . ']}';
	echo $lista;

} 

if (isset($_GET['itensNotaEntrada'])) {

	$lista = '{"result":[' . json_encode(itensNotaEntrada($conexao, $empresaMatriz, $dataI, $dataF, $cp_id)) . ']}';
	echo $lista;

}

if (isset($_GET['itensNotaSaida'])) {

	$lista = '{"result":[' . json_encode(itensNotaSaida($conexao, $empresaMatriz, $dataI, $dataF, $nf_id)) . ']}';
	echo $lista;

}

if (isset($_GET['consultaNota'])) {

	$lista = '{"result":[' . json_encode(consultaNota($conexao, $empresaMatriz, $dataI, $dataF, $cp_id)) . ']}';
	echo $lista;

}

if (isset($_GET['dadosFormaPagto'])) {

	$lista = '{"result":[' . json_encode(dadosFormaPagto($conexao, $empresaMatriz, $empresa, $dataI, $dataF)) . ']}';
	echo $lista;

}

if (isset($_GET['dadosHistorico'])) {

	$lista = '{"result":[' . json_encode(dadosHistorico($conexao, $empresaMatriz, $empresa, $dataI, $dataF)) . ']}';
	echo $lista;

}

if (isset($_GET['numParcelasNota'])) {

	$lista = '{"result":[' . json_encode(numParcelasNota($conexao, $ct_tipodoc, $ct_historico, $numParcelas)) . ']}';
	echo $lista;

}

if (isset($_GET['CaixasAbertos'])) {

	if (isset($_GET['empresa'])) {
		$empresa = $_GET['empresa'];

		if ($empresa == null) {
			$empresa = '';

		} else {
			$empresa = ' and bc_empresa = ' . $empresa;
		}

	} else {
		$empresa = '';
	}

	if (isset($_GET['funcionario'])) {
		$funcionario = $_GET['funcionario'];

		if ($funcionario == null) {
			$funcionario = '';

		} else {
			$funcionario = ' and bc_cod_func = ' . $funcionario;
		}

	} else {
		$funcionario = '';
	}

	$lista = '{"result":[' . json_encode(CaixasAbertos($conexao, $empresaMatriz, $empresa, $funcionario)) . ']}';
	echo $lista;

}

function dadosNotaEntrada($conexao, $empresaMatriz, $empresa, $dataI, $dataF) {
	$retorno = array();

	$sql = "select 	compra.cp_id,
					compra.cp_nota,
					compra.cp_emis,
					compra.cp_forn,
					compra.cp_fnraz,
					compra.cp_valor,
					compra.cp_aberto,
					compra.cp_tipdoc,
					compra.cp_empresa,
					compra.cp_matriz,
				(select empresas.em_fanta from empresas where empresas.em_cod = compra.cp_empresa) as emp_entrada,
				(select tipo_docto.dc_descricao from tipo_docto where tipo_docto.dc_codigo = compra.cp_tipdoc and tipo_docto.dc_empr = compra.cp_empresa) as dc_descricao
			from compra
 			where cp_matriz = $empresaMatriz $empresa and cp_emis between '$dataI' and '$dataF' order by cp_emis DESC;";


	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'cp_id' => $row['cp_id'],
			'cp_nota' => utf8_encode($row['cp_nota']),
			'cp_emis' => utf8_encode($row['cp_emis']),
			'cp_forn' => $row['cp_forn'],
			'cp_fnraz' => ucwords(strtolower(utf8_encode($row['cp_fnraz']))),
			'cp_valor' => utf8_encode($row['cp_valor']),
			'cp_aberto' => $row['cp_aberto'],
			'cp_tipdoc' => $row['cp_tipdoc'],
			'cp_empresa' => $row['cp_empresa'],
			'cp_matriz' => $row['cp_matriz'],
			'dc_descricao' => ucwords(strtolower(utf8_encode($row['dc_descricao']))),
			'emp_entrada' => ucwords(strtolower(utf8_encode($row['emp_entrada']))),

		));
	}

	//echo $sql;

	return $retorno;
}

function dadosNotaSaida($conexao, $empresaMatriz, $empresa, $dataI, $dataF) {
	$retorno = array();

	$sql = "select 	nf_id,
					nf_empresa,
					nf_matriz,
					nf_modelo,
					nf_nf,
					nf_emis,
					nf_natop,
					nf_saida,
					nf_cli,
					nf_clraz,
					nf_es,
					nf_prot_uso,
					nf_chavenfe,
					nf_totnf,
				(select empresas.em_fanta from empresas where empresas.em_cod = nota.nf_empresa) as emp_entrada
			from nota
 			where nf_matriz = $empresaMatriz $empresa and nf_emis between '$dataI' and '$dataF' order by nf_emis DESC;";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
					'nf_id' => $row['nf_id'],
					'nf_empresa' => $row['nf_empresa'],
					'nf_matriz' => $row['nf_matriz'],
					'nf_modelo' => utf8_encode($row['nf_modelo']),
					'nf_nf' => $row['nf_nf'],
					'nf_emis' => $row['nf_emis'],
					'nf_natop' => utf8_encode($row['nf_natop']),
					'nf_saida' => utf8_encode($row['nf_saida']),
					'nf_cli' => $row['nf_cli'],
					'nf_clraz' => ucwords(strtolower(utf8_encode($row['nf_clraz']))),
					'nf_es' => $row['nf_es'],
					'nf_prot_uso' => utf8_encode($row['nf_prot_uso']),
					'nf_chavenfe' => utf8_encode($row['nf_chavenfe']),
					'nf_totnf' => $row['nf_totnf'],
					'emp_entrada' => ucwords(strtolower(utf8_encode($row['emp_entrada']))),

		));
	}

	//echo $sql;

	return $retorno;
}

function consultaNota($conexao, $empresaMatriz, $dataI, $dataF, $cp_id) {
	$retorno = array();

	$sql = "select 	cp_id,
					cp_nota,
					cp_emis,
					cp_forn,
					cp_fnraz,
					cp_valor,
					cp_aberto,
					cp_empresa,
					cp_matriz
				from compra 
 				where cp_id = '$cp_id';";


	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'cp_id' => $row['cp_id'],
			'cp_nota' => utf8_encode($row['cp_nota']),
			'cp_emis' => utf8_encode($row['cp_emis']),
			'cp_forn' => $row['cp_forn'],
			'cp_fnraz' => ucwords(strtolower(utf8_encode($row['cp_fnraz']))),
			'cp_valor' => utf8_encode($row['cp_valor']),
			'cp_aberto' => $row['cp_aberto'],
			'cp_empresa' => $row['cp_empresa'],
			'cp_matriz' => $row['cp_matriz'],

		));
	}

	//echo $sql;

	return $retorno;
}

function itensNotaEntrada($conexao, $empresaMatriz, $dataI, $dataF, $cp_id) {

	$resultado = array();

	//$sql = "select count(distinct(vd_cli)) totalCliente from vendas where vd_emis >= '$dataI' and vd_emis <= '$dataF' and vd_canc<>'S' and vd_pgr<>'D' and vd_matriz = $empresaMatriz $vd_empr $vd_cli $vd_func";

	$sql = "SELECT 	cpi_id,
					cpi_empresa,
					cpi_matriz,
					cpi_prod,
					cpi_descricao,
					cpi_quant,
					cpi_preco,
					cpi_total
			from cp_item
			where cpi_idprim = $cp_id
			order by cpi_descricao;";

	$query = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($query)) {
		
		array_push($resultado, array(

				'cpi_id' => $row['cpi_id'],
				'cpi_empresa' => $row['cpi_empresa'],
				'cpi_matriz' => $row['cpi_matriz'],
				'cpi_prod'  => $row['cpi_prod'],
				'cpi_descricao' => ucwords(strtolower(utf8_encode($row['cpi_descricao']))),
				'cpi_quant' => $row['cpi_quant'],
				'cpi_preco' => utf8_encode($row['cpi_preco']),
				'cpi_total' => utf8_encode($row['cpi_total']),
		));

	}

	//echo $sql;
	return $resultado;
}

function itensNotaSaida($conexao, $empresaMatriz, $dataI, $dataF, $cp_id) {

	$resultado = array();

	//$sql = "select count(distinct(vd_cli)) totalCliente from vendas where vd_emis >= '$dataI' and vd_emis <= '$dataF' and vd_canc<>'S' and vd_pgr<>'D' and vd_matriz = $empresaMatriz $vd_empr $vd_cli $vd_func";

	$sql = "SELECT 	nfi_id,
					nfi_empresa,
					nfi_matriz,
					nfi_prod,
					nfi_desc,
					nfi_quant,
					nfi_preco,
					nfi_total
			from nf_item
			where nfi_idprim = $nf_item_id
			order by nfi_desc;";

	$query = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($query)) {
		
		array_push($resultado, array(

				'nfi_id' => $row['nfi_id'],
				'nfi_empresa' => $row['nfi_empresa'],
				'nfi_matriz' => $row['nfi_matriz'],
				'nfi_prod'  => $row['nfi_prod'],
				'nfi_desc' => ucwords(strtolower(utf8_encode($row['nfi_desc']))),
				'nfi_quant' => $row['nfi_quant'],
				'nfi_preco' => utf8_encode($row['nfi_preco']),
				'nfi_total' => utf8_encode($row['nfi_total']),
		));

	}

	//echo $sql;
	return $resultado;
}

function dadosFormaPagto($conexao, $empresaMatriz, $empresa, $dataI, $dataF) {

	$resultado = array();

	//$sql = "select count(distinct(vd_cli)) totalCliente from vendas where vd_emis >= '$dataI' and vd_emis <= '$dataF' and vd_canc<>'S' and vd_pgr<>'D' and vd_matriz = $empresaMatriz $vd_empr $vd_cli $vd_func";

	$sql = "SELECT 	distinct(dc_codigo),
					dc_descricao
			from tipo_docto
			where dc_matriz = $empresaMatriz 
			order by dc_descricao;";

	$query = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($query)) {
		
		array_push($resultado, array(

				'dc_codigo' => utf8_encode($row['dc_codigo']),
				'dc_descricao' => ucwords(utf8_encode($row['dc_descricao'])),
		));

	}

	//echo $sql;
	return $resultado;
}

function dadosHistorico($conexao, $empresaMatriz, $empresa, $dataI, $dataF) {

	$resultado = array();

	//$sql = "select count(distinct(vd_cli)) totalCliente from vendas where vd_emis >= '$dataI' and vd_emis <= '$dataF' and vd_canc<>'S' and vd_pgr<>'D' and vd_matriz = $empresaMatriz $vd_empr $vd_cli $vd_func";

	$sql = "SELECT 	distinct(ht_cod),
					ht_id,
					ht_descricao,
					ht_dc,
					ht_empresa,
                    ht_matriz
			from historico
			where ht_matriz = $empresaMatriz 
			order by ht_descricao;";

	$query = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($query)) {
		
		array_push($resultado, array(

				'ht_id' => $row['ht_id'],
				'ht_cod' => $row['ht_cod'],
				'ht_descricao' => ucwords(utf8_encode($row['ht_descricao'])),
				'ht_dc' => ucwords(utf8_encode($row['ht_dc'])),
				'ht_empresa' => $row['ht_empresa'],
				'ht_matriz' => $row['ht_matriz'],
		));

	}

	//echo $sql;
	return $resultado;
}

function CaixasAbertos($conexao, $empresaMatriz, $empresa, $funcionario) {

	$resultado = array();

	//$sql = "select count(distinct(vd_cli)) totalCliente from vendas where vd_emis >= '$dataI' and vd_emis <= '$dataF' and vd_canc<>'S' and vd_pgr<>'D' and vd_matriz = $empresaMatriz $vd_empr $vd_cli $vd_func";

	$sql = "SELECT 	bc_codigo,
			bc_descricao
			from bancos
			where bc_matriz = $empresaMatriz $empresa and bc_situacao = 'Aberto' $funcionario
			order by bc_descricao;";

	$query = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($query)) {
		
		array_push($resultado, array(

				'bc_codigo' => $row['bc_codigo'],
				'bc_descricao' => ucwords(utf8_encode($row['bc_descricao'])),
		));

	}

	//echo $sql;
	return $resultado;
}

?>