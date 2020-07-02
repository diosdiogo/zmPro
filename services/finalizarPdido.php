<?php

include 'conecta.php';

if (isset($_GET['ped_id'])) {
    $ped_id = $_GET['ped_id'];
}
$empresaMatriz = base64_decode($_GET['empresa_matriz']);

$empresaAcesso = base64_decode($_GET['empresa_filial']);

if (isset($_GET['ComunicaSistema'])) {
    $finalizarPedidoSemSistema =finalizarPedidoSemSistema($conexao, $empresaMatriz, $empresaAcesso, $ped_id);    
}

function finalizarPedidoSemSistema($conexao, $empresaMatriz, $empresaAcesso, $ped_id){
    $sql = "update pedido_food set ped_finalizado='S' where ped_matriz = $empresaMatriz and ped_empresa = $empresaAcesso and ped_id= $ped_id;";

    $query = mysqli_query($conexao, $sql);

    $row = mysqli_affected_rows($conexao);

    if ($row > 0) {
       echo 1;
    }else{
        echo 0;
    }
    
}

?>