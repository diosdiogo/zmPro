<?php 
date_default_timezone_set('UTC');
if (isset($_GET['vencimento']) && isset($_GET['valorTotal']) && isset($_GET['numParcelas'])) {


	$dados = array();


	$vencimento = $_GET['vencimento'];
	$valorTotal = $_GET['valorTotal'];
	$numParcelas = $_GET['numParcelas'];
	$tipoDocto =  $_GET['tipoDocto'];
	//$tipoPagamento = $_GET['ct_tipdoc'];
	//$parcelas = '';
	//$valorParcela = '';
	//$dataVencto = '';

	//echo $valorTotal;
	
	$valorParcela = str_replace('R','',$valorTotal);
	$valorParcela = str_replace('$','',$valorParcela);

	//echo $valorParcela;	//if ($tipoPagamento == 1) {

		for ($i = 1; $i <= $numParcelas; $i++) {
			
			if($tipoDocto == 1){
				array_push($dados, array(
				
					'vezes'=>$parcelas = $i . '/' . $numParcelas,
					'vencimento'=>$dataVencto = date('Y-m-d', strtotime($vencimento)),
					'parcela'=>$parcelaValor = $valorParcela / $numParcelas
		
				));
			}
			else{
				array_push($dados, array(
				
					'vezes'=>$parcelas = $i . '/' . $numParcelas,
					'vencimento'=>$dataVencto = date('Y-m-d', strtotime("+".$i." month", strtotime($vencimento))),
					'parcela'=>$parcelaValor = $valorParcela / $numParcelas
	
				));
			}
			
		}

/*	} else {

		for ($i = 1; $i <= $numParcelas; $i++) {
			array_push($dados, array(
				
				'vezes'=>$parcelas = $i . '/' . $numParcelas,
				'vencimento'=>$dataVencto = date('Y-m-d', strtotime("+".$i." month", strtotime($vencimento))),
				'parcela'=>$parcelaValor = $valorParcela / $numParcelas
	
			));
			
		}
		
	}*/
	
	echo '{"result":[' . json_encode($dados) . ']}';
	
	
}

?>