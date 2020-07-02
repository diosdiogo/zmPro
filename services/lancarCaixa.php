<?php

function lancaCaixa($conexao, $empresa_matriz, $ct_id, $dc){
        $r="";

        $sql ="INSERT INTO caixa_aberto (cx_idlocal, cx_empresa, cx_matriz, cx_docto, cx_emissao, cx_historico, cx_tpdocto, cx_dc, cx_nome,
        cx_obs, cx_valor, cx_banco, cx_canc, cx_manual, cx_ocorrencia, cx_vendedor, cx_empr, cx_deletado) SELECT (max(cx_idlocal)+1),
        $empresa_matriz,$empresa_matriz, 
        (SELECT ct_docto FROM contas WHERE ct_id = $ct_id), now(), 
        (SELECT ct_historico FROM contas WHERE ct_id = $ct_id),
        (SELECT ct_tipdoc FROM contas WHERE ct_id = $ct_id),'$dc',
        (SELECT ct_nome FROM contas WHERE ct_id = $ct_id),
        (SELECT ct_obs FROM contas WHERE ct_id = $ct_id),
        (SELECT ct_valorpago FROM contas WHERE ct_id = $ct_id),
        (SELECT ct_caixa FROM contas WHERE ct_id = $ct_id),
        'N','N',
        (SELECT ct_ocorrencia FROM contas WHERE ct_id = $ct_id),
        (SELECT ct_vendedor FROM contas WHERE ct_id = $ct_id),$empresa_matriz, 'N' FROM caixa_aberto WHERE cx_matriz= $empresa_matriz;";

        $query = mysqli_query($conexao, $sql);

        
        if (mysqli_affected_rows($conexao) <= 0) {
           $r = "ERROR";

        } else {
            $r = "SUCCESS";
        }
        return $r;

    }