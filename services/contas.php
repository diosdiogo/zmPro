<?php


include 'conecta.php';
include 'log.php';
include 'ocorrencia.php';
include 'lancarCaixa.php';

date_default_timezone_set('America/Bahia');

$ip = get_client_ip();

$data = date('Y-m-d');
$hora = date('H:i:s');

    if (isset($_GET['empresa_matriz'])) {
        $empresa_matriz =base64_decode($_GET['empresa_matriz']);
    }else{
        $empresa_matriz=0;
    }

    if (isset($_GET['empresa_filial'])) {
        $empresa_filial= $_GET['empresa_filial'];
        if ($empresa_filial != '') {
            $empresa_filial= $_GET['empresa_filial'];
            $ct_empresa = '  and ct_empresa = ' . $empresa_filial;
        }else{
            $ct_empresa = ' ';
        }
        
    }else{
        $empresa_filial = 0;
        $ct_empresa = ' ';
    }
    //echo $ct_empresa;
    if (isset($_GET['canc'])) {
       $canc = $_GET['canc'];

       if ($canc != '') {
            $ct_canc = " and ct_canc = '". $canc. "'";
       }else{
            $ct_canc = '';
       }

    }else{
        $ct_canc = '';
    }

    if (isset($_GET['cliente'])) {
        $cliente = $_GET['cliente'];
         if ($cliente != null) {
             $ct_cliente_forn = ' and ct_cliente_forn= '. $cliente;
         }else{
             $ct_cliente_forn = '';
         }
    }else{
        $ct_cliente_forn='';
    }

    if (isset($_GET['us_id'])) {
        $us_id = $_GET['us_id'];
    }else{
        $us_id = null;
    }
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
    }

    if (isset($_GET['quitado'])) {

        $quitado = $_GET['quitado'];

        if(isset($_GET['dataI']) or isset($_GET['dataF'])){
            $dataI = $_GET['dataI'];
            $dataF = $_GET['dataF'];
            if($dataI == ''){
                $ct_vencto = "";
            }else{
                if ($quitado == 'N') {
                   $ct_vencto = " and ct_vencto between '".$dataI . "' and '". $dataF."'";
                }
                else if ($quitado == 'S'){
                    $ct_vencto = " and ct_pagto between '".$dataI . "' and '". $dataF."'";
                }
                //echo 'Data '. $dataI;
            }
            
        }else{
            $ct_vencto = "";
        }
    
    
    }

    if (isset($_GET['ct_id'])) {
        $conta_id = $_GET['ct_id'];

        if ($conta_id == '') {
            $ct_id='';
        }else {
            $ct_id = '   and ct_id = '.$conta_id;
        }
    }else {
        $ct_id='';
    }

    if (isset($_GET['receber'])) {

        $ct_receber_pagar = 'R';

        if (isset($_GET['listaContasReceber'])) {
           
            if(isset($_GET['buscarConta'])){
                $select = "(SELECT pe_id FROM pessoas WHERE pe_cod = ct_cliente_forn and pe_empresa = ct_empresa) as ct_cliente_fornecedor_id,";
            }else{
                $select = '';
            }

            $lista = '{"result":[' . json_encode(contasReceberPagarLista($conexao, $select, $empresa_matriz, $ct_empresa, $ct_vencto, $ct_canc, $ct_cliente_forn, $ct_receber_pagar, $quitado, $ct_id)) . ']}';
            echo $lista;
        }

        if ($quitado == 'N') {

            if(isset($_GET['totalContasReceber'])){
                $lista = '{"result":[' . json_encode(totalContasReceberPagar($conexao, $empresa_matriz, $ct_empresa, $ct_canc, $ct_cliente_forn, $ct_vencto,  $ct_receber_pagar, $quitado)). ']}';
                echo $lista;
            }
        }

        if ($quitado == 'S') {
            if(isset($_GET['totalContasReceber'])){
                $lista = '{"result":[' . json_encode(totalContasRecebidasPagas($conexao, $empresa_matriz, $ct_empresa, $ct_canc, $ct_cliente_forn, $ct_vencto,  $ct_receber_pagar, $quitado)). ']}';
                echo $lista;
            }
        }
        

    }

    if (isset($_GET['pagar'])) {

        $ct_receber_pagar = 'P';

        if (isset($_GET['listaContasPagar'])) {

            if(isset($_GET['buscarConta'])){
                $select = "(SELECT pe_id FROM pessoas WHERE pe_cod = ct_cliente_forn and pe_empresa = ct_empresa) as ct_cliente_fornecedor_id,";
            }else{
                $select = '';
            }

            $lista = '{"result":[' . json_encode(contasReceberPagarLista($conexao, $select, $empresa_matriz, $ct_empresa, $ct_vencto, $ct_canc, $ct_cliente_forn, $ct_receber_pagar, $quitado, $ct_id)) . ']}';
            echo $lista;
        }
        if ($quitado == 'N') {
            if(isset($_GET['totalContasPagar'])){
            $lista = '{"result":[' . json_encode(totalContasReceberPagar($conexao, $empresa_matriz, $ct_empresa, $ct_canc, $ct_cliente_forn, $ct_vencto, $ct_receber_pagar, $quitado)). ']}';
            echo $lista;
            }
        }
        if ($quitado == 'S') {
            if(isset($_GET['totalContasPagar'])){
                $lista = '{"result":[' . json_encode(totalContasRecebidasPagas($conexao, $empresa_matriz, $ct_empresa, $ct_canc, $ct_cliente_forn, $ct_vencto,  $ct_receber_pagar, $quitado)). ']}';
                echo $lista;
            }
        }
    }

    if (isset($_GET['buscarConta'])) {
        $ct_receber_pagar = $_GET['RecPag'];

        if(isset($_GET['buscarConta'])){
            $select = "(SELECT pe_id FROM pessoas WHERE pe_cod = ct_cliente_forn and pe_empresa = ct_empresa) as ct_cliente_fornecedor_id,";
        }else{
            $select = '';
        }

        $lista = '{"result":[' . json_encode(contasReceberPagarLista($conexao, $select, $empresa_matriz, $ct_empresa, $ct_vencto, $ct_canc, $ct_cliente_forn, $ct_receber_pagar, $quitado, $ct_id)) . ']}';
        echo $lista;
    }

    if (isset($_GET['baixarConta'])) {
        $array = json_decode(file_get_contents("php://input"), true);

        $conta = $array['contas'][0];
        $lancaCaixa = $array['lancaCaixa'];
        $valData = $array['valData'];
        

        if ($lancaCaixa['statusCheck'] == true) {
           //print_r($lancaCaixa);
        }
        
        if($lancaCaixa['caixa'] != 0){
            $caixa = ', ct_caixa= ' . $lancaCaixa['caixa'];
        }else{
            $caixa ='';
        }
        $dc ='D';
        baixarConta($conexao, $data, $hora, $ip, $valData['dataBaixa'], $conta['ct_valor'], $us_id, $caixa, $empresa_matriz, $token, $conta['ct_id'], $lancaCaixa['statusCheck'], $dc, $valData['tipoDoctos']);
        
    }

    if(isset($_GET['excluir'])){
        $ct_id = $_GET['ct_id'];
        excluir($conexao, $ct_id, $token);
    }

    
    function contasReceberPagarLista($conexao, $select, $empresa_matriz, $ct_empresa, $ct_vencto, $ct_canc, $ct_cliente_forn, $ct_receber_pagar, $quitado, $ct_id){
        $retorno = array();

        $sql = "SELECT ct_id, ct_idlocal, ct_empresa, em_fanta, ct_matriz, ct_docto, ct_cliente_forn,
        $select
        ct_vendedor, pe_nome vendedor, ct_emissao,
        ct_vencto, ct_valor, ct_parc, ct_nome, ct_canc, ct_tipdoc, 
        (SELECT dc_descricao FROM tipo_docto where dc_empr = ct_empresa and dc_codigo = ct_tipdoc) as dc_descricao,
        (SELECT dc_sigla FROM tipo_docto where dc_empr = ct_empresa and dc_codigo = ct_tipdoc) as dc_sigla,
        ct_pagto, ct_valorpago, ct_tipo_ocorrencia, ct_receber_pagar, ct_quitado, ct_obs
         FROM zmpro.contas 
         left join empresas on(ct_empresa = em_cod) 
         left join pessoas on (ct_vendedor = (select pe_cod where pe_vendedor = 'S' and pe_empresa = ct_empresa))
         where ct_matriz = $empresa_matriz $ct_empresa $ct_canc $ct_cliente_forn and ct_receber_pagar = '$ct_receber_pagar' and ct_quitado= '$quitado' $ct_vencto $ct_id order by ct_vencto;";

        $query = mysqli_query($conexao,$sql);

        while ($row = mysqli_fetch_assoc($query)) {
            if ($select != null) {
               array_push($retorno, array(
                    'ct_id' => $row['ct_id'],
                    'ct_idlocal' => $row['ct_idlocal'],
                    'ct_empresa' => $row['ct_empresa'],
                    'em_fanta' => utf8_decode($row['em_fanta']),
                    'ct_matriz' => $row['ct_matriz'],
                    'ct_docto' => $row['ct_docto'],
                    'ct_cliente_forn' => $row['ct_cliente_forn'],            
                    'ct_cliente_fornecedor_id'=> $row['ct_cliente_fornecedor_id'],
                    'ct_vendedor' => $row['ct_vendedor'],
                    'vendedor' => utf8_decode($row['vendedor']),
                    'ct_emissao' => utf8_decode($row['ct_emissao']),
                    'ct_vencto' => utf8_decode($row['ct_vencto']),
                    'ct_vencta' => verVencimento($row['ct_vencto']),
                    'ct_pagto'=> utf8_decode($row['ct_pagto']),
                    'ct_valorpago' => $row['ct_valorpago'],
                    'ct_valor' => $row['ct_valor'],
                    'ct_parc' => utf8_decode($row['ct_parc']),
                    'ct_nome' => utf8_decode($row['ct_nome']),
                    'ct_canc' => utf8_decode($row['ct_canc']),
                    'ct_tipdoc' => $row['ct_tipdoc'],
                    'dc_descricao' => utf8_decode($row['dc_descricao']),
                    'dc_sigla' => utf8_decode($row['dc_sigla']),
                    'ct_tipo_ocorrencia' => utf8_decode($row['ct_tipo_ocorrencia']),
                    'ct_receber_pagar' => utf8_decode($row['ct_receber_pagar']),
                    'ct_quitado' => utf8_decode($row['ct_quitado']),
                    'ct_obs' => utf8_encode($row['ct_obs']),
            
                ));
            }else{
                array_push($retorno, array(
                    'ct_id' => $row['ct_id'],
                    'ct_idlocal' => $row['ct_idlocal'],
                    'ct_empresa' => $row['ct_empresa'],
                    'em_fanta' => utf8_decode($row['em_fanta']),
                    'ct_matriz' => $row['ct_matriz'],
                    'ct_docto' => $row['ct_docto'],
                    'ct_cliente_forn' => $row['ct_cliente_forn'],            
                    //'ct_cliente_fornecedor_id'=> $row['ct_cliente_fornecedor_id'],
                    'ct_vendedor' => $row['ct_vendedor'],
                    'vendedor' => utf8_decode($row['vendedor']),
                    'ct_emissao' => utf8_decode($row['ct_emissao']),
                    'ct_vencto' => utf8_decode($row['ct_vencto']),
                    'ct_vencta' => verVencimento($row['ct_vencto']),
                    'ct_pagto'=> utf8_decode($row['ct_pagto']),
                    'ct_valorpago' => $row['ct_valorpago'],
                    'ct_valor' => $row['ct_valor'],
                    'ct_parc' => utf8_decode($row['ct_parc']),
                    'ct_nome' => utf8_decode($row['ct_nome']),
                    'ct_canc' => utf8_decode($row['ct_canc']),
                    'ct_tipdoc' => $row['ct_tipdoc'],
                    'dc_descricao' => utf8_decode($row['dc_descricao']),
                    'dc_sigla' => utf8_decode($row['dc_sigla']),
                    'ct_tipo_ocorrencia' => utf8_decode($row['ct_tipo_ocorrencia']),
                    'ct_receber_pagar' => utf8_decode($row['ct_receber_pagar']),
                    'ct_quitado' => utf8_decode($row['ct_quitado']),
                    'ct_obs' => utf8_encode($row['ct_obs']),
            
                ));
            }
           
        }
        //echo $sql;
        return $retorno;
    }

    function totalContasReceberPagar($conexao, $empresa_matriz, $ct_empresa, $ct_canc, $ct_cliente_forn, $ct_vencto, $ct_receber_pagar, $quitado){
        $retorno = array();

        array_push($retorno, array(
            'ct_valorVencida'=> totalContasReceberVencidas($conexao, $empresa_matriz, $ct_empresa, $ct_canc, $ct_cliente_forn, $ct_vencto, $ct_receber_pagar, $quitado),
            'ct_valorHoje' => totalContasReceberVencidasHoje($conexao, $empresa_matriz, $ct_empresa, $ct_canc, $ct_cliente_forn, $ct_vencto, $ct_receber_pagar, $quitado),
            'ct_valorAvencer' => totalContasReceber_A_Vencer($conexao, $empresa_matriz, $ct_empresa, $ct_canc, $ct_cliente_forn, $ct_vencto, $ct_receber_pagar, $quitado)
        ));

        return $retorno;
    }

    function totalContasRecebidasPagas($conexao, $empresa_matriz, $ct_empresa, $ct_canc, $ct_cliente_forn, $ct_vencto,  $ct_receber_pagar, $quitado){
        $retorno = array();

        array_push($retorno, array(
            'ct_valorpago'=> totalContasRecebidasList($conexao, $empresa_matriz, $ct_empresa, $ct_canc, $ct_cliente_forn, $ct_vencto,  $ct_receber_pagar, $quitado),
            
        ));

        return $retorno;
    }

    function totalContasReceberVencidas($conexao, $empresa_matriz, $ct_empresa, $ct_canc, $ct_cliente_forn, $ct_vencto, $ct_receber_pagar, $quitado){
        //$retorno = array();

        $sql = "SELECT sum(ct_valor) as ct_valorVencida FROM zmpro.contas where ct_matriz = $empresa_matriz $ct_empresa $ct_canc $ct_cliente_forn  $ct_vencto and ct_receber_pagar = '$ct_receber_pagar' and ct_quitado= '$quitado' and ct_vencto < current_date();";

        $query = mysqli_query($conexao,$sql);

        $row = mysqli_fetch_assoc($query);

        $ct_valorVencida = $row['ct_valorVencida'];

        if ($ct_valorVencida == null) {
            $ct_valorVencida = 0.00;
        }
        return $ct_valorVencida;
    }

    function totalContasReceberVencidasHoje($conexao, $empresa_matriz, $ct_empresa, $ct_canc, $ct_cliente_forn, $ct_vencto, $ct_receber_pagar, $quitado){
        //$retorno = array();

        $sql = "SELECT sum(ct_valor) as ct_valorHoje FROM zmpro.contas where ct_matriz = $empresa_matriz $ct_empresa $ct_canc $ct_cliente_forn  $ct_vencto and ct_receber_pagar = '$ct_receber_pagar' and ct_quitado= '$quitado' and ct_vencto = current_date();";

        $query = mysqli_query($conexao,$sql);

        $row = mysqli_fetch_assoc($query);

        $ct_valor= $row['ct_valorHoje'];

        if ($ct_valor == null) {
            $ct_valor= 0.00;
        }
        return  $ct_valor;
    }

    function totalContasReceber_A_Vencer($conexao, $empresa_matriz, $ct_empresa, $ct_canc, $ct_cliente_forn, $ct_vencto, $ct_receber_pagar, $quitado){
        //$retorno = array();

        $sql = "SELECT sum(ct_valor) as ct_valorAvencer FROM zmpro.contas where ct_matriz = $empresa_matriz $ct_empresa $ct_canc $ct_cliente_forn  $ct_vencto and ct_receber_pagar = '$ct_receber_pagar' and ct_quitado= '$quitado' and ct_vencto > current_date();";

        $query = mysqli_query($conexao,$sql);

        $row = mysqli_fetch_assoc($query);

        $ct_valorAvencer = $row['ct_valorAvencer'];

        if ($ct_valorAvencer == null) {
            $ct_valorAvencer = 0.00;
        }
        return $row['ct_valorAvencer'];
    }

    function totalContasRecebidasList($conexao, $empresa_matriz, $ct_empresa, $ct_canc, $ct_cliente_forn, $ct_vencto,  $ct_receber_pagar, $quitado){
        //$retorno = array();

        $sql = "SELECT sum(ct_valorpago) as ct_valorpago FROM zmpro.contas where ct_matriz =  $empresa_matriz $ct_empresa $ct_canc $ct_cliente_forn  $ct_vencto and ct_receber_pagar = '$ct_receber_pagar' and ct_quitado= '$quitado'";

        $query = mysqli_query($conexao,$sql);

        $row = mysqli_fetch_assoc($query);

        $ct_valorVencida = $row['ct_valorpago'];

        if ($ct_valorVencida == null) {
            $ct_valorVencida = 0.00;
        }
        //echo $sql;
        return $ct_valorVencida;
    }

    /*

    baixar contas

    */

    function baixarConta($conexao, $data, $hora, $ip, $dataBaixa, $ct_valor, $us_id, $caixa, $empresa_matriz, $token, $ct_id, $lancaCaixa,$dc, $tipoDoctos){
        
        $retorno = array();

        $ocorrencia = ocorrencia($conexao, $empresa_matriz);

      
            
                $sql="update contas set ct_pagto = '$dataBaixa', ct_valorpago=$ct_valor, ct_vendbaixa = (SELECT us_cod FROM usuarios where us_id=$us_id), 
            ct_quitado = 'S' $caixa, ct_tipdoc=$tipoDoctos, ct_ocorrencia=(SELECT max(dc_ocorrencia) FROM doctos where dc_matriz =$empresa_matriz)
            where ct_matriz=(SELECT em_cod FROM empresas where em_token='$token') and ct_id = $ct_id;";

            $query=mysqli_query($conexao, $sql);

            if (mysqli_affected_rows($conexao) <= 0) {
                array_push($retorno, array(
                    'status'=> $row = 'ERROR',
                    'lancaCaixa' => false
                ));

            } else {
               

                $sqlOcorrencis = 'concat("'.'Conta Baixada Ocorrencia"'. ",(SELECT max(dc_ocorrencia) FROM doctos where dc_matriz =".$empresa_matriz.')';

                if($lancaCaixa == true){
                    array_push($retorno, array(
                        'status'=> $row = 'SUCCESS',
                        'lancaCaixa' => $row = lancaCaixa($conexao, $empresa_matriz, $ct_id, $dc)
                    ));
                    
                }else{
                
                    array_push($retorno, array(
                        'status'=> $row = 'SUCCESS',
                        'lancaCaixa' => false
                    ));
                }
                logSistema_Baixar_Conta_Pagar_forOcorrencia($conexao, $data, $hora, $ip, $us_id, 'Conta Baixada Ocorrencia N ', $empresa_matriz , $empresa_matriz);
            }
            //echo $sql;
        
            echo '{"result":[' . json_encode($retorno). ']}';

        
    }

    function excluir($conexao, $ct_id, $token){

        $retorno = array();
        $sql="DELETE FROM contas where ct_matriz = (SELECT em_cod FROM empresas where em_token='$token') and ct_id = $ct_id;";
        $query=mysqli_query($conexao, $sql);

        if (mysqli_affected_rows($conexao) <= 0) {
            array_push($retorno, array(
                'status'=> $row = 'ERROR',
                
            ));
            echo '{"result":[' . json_encode($retorno). ']}';
        }
        else{
            array_push($retorno, array(
                'status'=> $row = 'SUCCESS',
                
            ));
            echo '{"result":[' . json_encode($retorno). ']}';
        }

    }
      

    function verVencimento($e){
        $data = date('Y-m-d');
        $retorno =''; 
        if($e == $data){
            $retorno = 'Hoje';
        }
        else if($e < $data){
            $retorno = 'Vencido';
        }else{
            $retorno = "A Vencer";
        }
        return $retorno ;
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