<?php
include 'conecta.php';
include 'log.php';

date_default_timezone_set('America/Bahia');

$us_id = base64_decode($_GET['us_id']);
$ip = get_client_ip();

$data = date('Y-m-d');
$hora = date('H:i:s');

    $empresaMatriz = base64_decode($_GET['matriz']);

    $empresaAcesso = base64_decode($_GET['empresa']);

    //echo 'Matriz '.$empresaMatriz . 'Acesso '.$empresaAcesso;

    $token = $_GET['token'];
    
    if (isset($_GET['historico'])) {

        if (isset($_GET['lista'])) {
            $lista = '{"result":[' . json_encode(dadosHistóricoBancario($conexao, $empresaMatriz, $empresaAcesso, $token)) . ']}';
	        echo $lista;
        }

        if (isset($_GET['buscar'])) {
            $grp_id = $_GET['grp_id'];

            $lista = '{"result":[' . json_encode(históricoBancario($conexao, $empresaMatriz, $empresaAcesso, $token, $grp_id)) . ']}';
	        echo $lista;
        }

        if (isset($_GET['salvar'])) {
            $ht_descricao = utf8_decode($_GET['ht_descricao']);
            $ht_dc = $_GET['ht_dc'];
            salvarHistóricoBancario($conexao, $empresaMatriz, $empresaAcesso, $token, $ht_descricao, $ht_dc, $data, $hora, $ip, $us_id);
        }

        if (isset($_GET['editar'])) {
            $ht_id = $_GET['ht_id'];
            $ht_descricao = utf8_decode($_GET['ht_descricao']);
            $ht_dc = $_GET['ht_dc'];
            editarHistóricoBancario($conexao, $empresaMatriz, $empresaAcesso, $token, $ht_descricao, $ht_dc, $ht_id, $data, $hora, $ip, $us_id);
        }
        if (isset($_GET['excluir'])) {
            $ht_id = $_GET['ht_id'];
            $ht_descricao = utf8_decode($_GET['ht_descricao']);
            
            excluirHistóricoBancario($conexao, $empresaMatriz, $empresaAcesso, $token, $ht_descricao, $ht_id, $data, $hora, $ip, $us_id);
        }
        
    }

    function dadosHistóricoBancario($conexao, $empresaMatriz, $empresaAcesso, $token){
        $retorno = array();
        $sql = "SELECT ht_id, ht_empresa, (SELECT em_fanta FROM empresas where em_cod = ht_empresa) as pd_empresa, ht_matriz
        ht_cod, ht_descricao, ht_grupo, ht_tipogrup, ht_dc, ht_ordem, ht_centro_custo, ht_desc_centrocusto
         FROM historico where ht_matriz = (SELECT em_cod FROM empresas where em_token = '$token') ORDER BY ht_descricao LIMIT 0, 100;";

        $query = mysqli_query($conexao, $sql);

        while ($row = mysqli_fetch_assoc($query)) {
            array_push($retorno, array(
                'ht_id' =>$row['ht_id'],
                'ht_empresa' =>$row['ht_empresa'],
                'pd_empresa' => utf8_encode($row['pd_empresa']),
                'ht_cod' =>$row['ht_cod'],
                'ht_descricao' =>utf8_encode($row['ht_descricao']),
                'ht_grupo' =>$row['ht_grupo'],
                'ht_tipogrup' =>$row['ht_tipogrup'],
                'ht_dc' =>utf8_encode($row['ht_dc']),
                'ht_ordem' =>$row['ht_ordem'],
                'ht_centro_custo' =>$row['ht_centro_custo'],
                'ht_desc_centrocusto' =>utf8_encode($row['ht_desc_centrocusto']),

            ));

    }
    return $retorno;
}

function históricoBancario($conexao, $empresaMatriz, $empresaAcesso, $token, $grp_id){
    $retorno = array();
    $sql = "SELECT ht_id, ht_empresa, (SELECT em_fanta FROM empresas where em_cod = ht_empresa) as pd_empresa, ht_matriz
    ht_cod, ht_descricao, ht_grupo, ht_tipogrup, ht_dc, ht_ordem, ht_centro_custo, ht_desc_centrocusto
     FROM historico where ht_matriz = (SELECT em_cod FROM empresas where em_token = '$token') AND ht_id = $grp_id ORDER BY ht_descricao LIMIT 0, 100;";

    $query = mysqli_query($conexao, $sql);

    while ($row = mysqli_fetch_assoc($query)) {
        array_push($retorno, array(
            'ht_id' =>$row['ht_id'],
            'ht_empresa' =>$row['ht_empresa'],
            'pd_empresa' => utf8_encode($row['pd_empresa']),
            'ht_cod' =>$row['ht_cod'],
            'ht_descricao' =>utf8_encode($row['ht_descricao']),
            'ht_grupo' =>$row['ht_grupo'],
            'ht_tipogrup' =>$row['ht_tipogrup'],
            'ht_dc' =>utf8_encode($row['ht_dc']),
            'ht_ordem' =>$row['ht_ordem'],
            'ht_centro_custo' =>$row['ht_centro_custo'],
            'ht_desc_centrocusto' =>utf8_encode($row['ht_desc_centrocusto']),

        ));

}
return $retorno;
}

function salvarHistóricoBancario($conexao, $empresaMatriz, $empresaAcesso, $token, $ht_descricao, $ht_dc, $data, $hora, $ip, $us_id){
    
    $sql = "INSERT INTO historico (ht_cod, ht_empresa, ht_matriz,  ht_descricao, ht_grupo, ht_tipogrup, ht_dc, ht_ordem, ht_centro_custo, 
    ht_desc_centrocusto) select (max(ht_cod)+1), $empresaMatriz, $empresaMatriz, '$ht_descricao', 0, 0,'$ht_dc', 0, 0, 'NENHUM' FROM historico where (SELECT em_cod FROM empresas where em_token = '$token');";

    $query = mysqli_query($conexao,$sql);
    $retorno = mysqli_affected_rows($conexao);

    if ($retorno <= 0) {
        echo 0;
    }else if($retorno >= 1){
        echo 1;
        logSistema_forID($conexao, $data, $hora, $ip, $us_id, utf8_decode('Histórico Criado Nome - ' . $ht_descricao . ''), $empresaAcesso, $empresaMatriz);
    }
}

function editarHistóricoBancario($conexao, $empresaMatriz, $empresaAcesso, $token, $ht_descricao, $ht_dc, $ht_id, $data, $hora, $ip, $us_id){
    $sql = "UPDATE historico SET ht_descricao = '$ht_descricao', ht_dc = '$ht_dc' where ht_matriz = (SELECT em_cod FROM empresas where em_token = '$token') 
    AND ht_id=$ht_id";
    $query = mysqli_query($conexao, $sql);
    
    $retorno = mysqli_affected_rows($conexao);

    if ($retorno <= 0) {
        echo 0;
    }else if($retorno >= 1){
        echo 1;
        logSistema_forID($conexao, $data, $hora, $ip, $us_id, utf8_decode('Histórico Modificado  Nome - ' . $ht_descricao . ''), $empresaAcesso, $empresaMatriz);
    }
}

function excluirHistóricoBancario($conexao, $empresaMatriz, $empresaAcesso, $token, $ht_descricao, $ht_id, $data, $hora, $ip, $us_id){
    
    $sql = "DELETE FROM historico WHERE ht_matriz = (SELECT em_cod FROM empresas where em_token = '$token') 
    AND ht_id=$ht_id;";
    $query = mysqli_query($conexao,$sql);

    $retorno = mysqli_affected_rows($conexao);

    if ($retorno <= 0) {
        echo 0;
    }else if($retorno >= 1){
        echo 1;
        logSistema_forID($conexao, $data, $hora, $ip, $us_id, utf8_decode('Histórico Deletado  Nome - ' . $ht_descricao . ''), $empresaAcesso, $empresaMatriz);
    }

}
function get_client_ip() {
	$ipaddress = '';
	if (isset($_SERVER['HTTP_CLIENT_IP'])) {
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	} else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	} else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	} else if (isset($_SERVER['HTTP_FORWARDED'])) {
		$ipaddress = $_SERVER['HTTP_FORWARDED'];
	} else if (isset($_SERVER['REMOTE_ADDR'])) {
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	} else {
		$ipaddress = 'UNKNOWN';
	}

	return $ipaddress;
}
?>