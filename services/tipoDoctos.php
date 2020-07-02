<?php
    include 'conecta.php';
    include 'log.php';
    include 'getIp.php';

    date_default_timezone_set('America/Bahia');

    $ip = get_client_ip();

    $data = date('Y-m-d');
    $hora = date('H:i:s');

    if (isset($_GET['empresa_matriz'])) {
        $empresa_matriz =base64_decode($_GET['empresa_matriz']);
    }else{
        $empresa_matriz='';
    }

    if (isset($_GET['empresa_filial'])) {
        $empresa_filial= $_GET['empresa_filial'];
    }else{
        $empresa_filial= '';

    }
    if(isset($_GET['token'])){
        $token = $_GET['token'];
    }

    if (isset($_GET['documento'])) {
        if (isset($_GET['lista'])) {
            $lista = '{"result":[' . json_encode(listaTipoDocumento($conexao, $empresa_matriz, $empresa_filial, $token)) . ']}';
            echo $lista;
        }
        
    }

    function listaTipoDocumento($conexao, $empresa_matriz, $empresa_filial, $token){
        $retorno = array();

        $sql = "SELECT * FROM zmpro.tipo_docto where dc_matriz=(SELECT em_cod FROM empresas where em_token = '$token');";

        $query = mysqli_query($conexao, $sql);

        while ($row = mysqli_fetch_assoc($query)) {
            array_push($retorno, array(
                'dc_id' => $row['dc_id'],
                'dc_empr' => $row['dc_empr'],
                'dc_matriz' => $row['dc_matriz'],
                'dc_codigo' => $row['dc_codigo'],
                'dc_descricao' => utf8_encode($row['dc_descricao']),
                'dc_sigla' => utf8_decode($row['dc_sigla']),
                'dc_banco' => $row['dc_banco'],
                'dc_tipo_valor' => $row['dc_tipo_valor'],
                'dc_tipo_condicao' => $row['dc_tipo_condicao'],
                'dc_comportamento' => $row['dc_comportamento'],
                'dc_descto' => $row['dc_descto'],
                'dc_balcao' => $row['dc_balcao'],
                'dc_primeiraqnt' => $row['dc_primeiraqnt'],
                'dc_juro' => $row['dc_juro'],
                'dc_parcelas' => $row['dc_parcelas'],

            ));
        }

        return $retorno;
    }