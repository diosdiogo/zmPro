<?php

      function ocorrencia($conexao, $empresa_matriz){
        $sql = "INSERT INTO doctos (dc_ocorrencia, dc_matriz, dc_empresa) SELECT max(dc_ocorrencia)+1, $empresa_matriz, $empresa_matriz FROM doctos WHERE dc_matriz=$empresa_matriz;";
        $query=mysqli_query($conexao, $sql);
        
        if (mysqli_affected_rows($conexao) <= 0) {
            //echo 0;
        } else {
            //echo 1;
        }

    }