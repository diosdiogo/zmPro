
<?php

include 'conecta.php';

$array = json_decode(file_get_contents("php://input"), true);

print_r($array);

function insereEmpresa($em_razao,
	$em_fanta,
	$em_end,
	$em_end_num,
	$em_bairro,
	$em_cid,
	$em_uf,
	$em_cep,
	$em_cnpj,
	$em_insc,
	$em_fone,
	$em_email,
	$em_responsavel,
	$em_cont_nome,
	$em_cont_fone,
	$em_cont_email) {

	$query = "insert into empresas (
em_razao,
em_fanta,
em_end,
em_end_num,
em_bairro,
em_cid,
em_uf,
em_cep,
em_cnpj,
em_insc,
em_fone,
em_email,
em_responsavel,
em_cont_nome,
em_cont_fone,
em_cont_email
)

values (
{$em_razao},
{$em_fanta},
{$em_end},
{$em_end_num},
{$em_bairro},
{$em_cid},
{$em_uf},
{$em_cep},
{$em_cnpj},
{$em_insc},
{$em_fone},
{$em_email},
{$em_responsavel},
{$em_cont_nome},
{$em_cont_fone},
{$em_cont_email}
)";

	$inserir = mysqli_query($conexao, $query);

	return $inserir;
}
