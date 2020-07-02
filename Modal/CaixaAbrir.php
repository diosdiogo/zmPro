<?php

include 'varInicio.php';
include 'conecta.php';
include 'funcoes-inicio.php';
include 'cabecalho.php';
//include 'services/caixas.php';

?>
<style>
	.dropdown-menu{
		box-shadow: -0.5em 0.5em 0.5em rgba(0,0,0,0.5);
		border-radius:15px;
	 }

	 .dropdown-menu a{
		color:#333;
		text-decoration:none; 
		padding:5px ;
		display:block; 
		background:white;
		cursor:pointer;
	 }
	 
	.dropdown-menu a:hover{
		background:#333;
		color:#fff !important;
		-moz-box-shadow:0 3px 10px 0 #CCC;
		-webkit-box-shadow:0 3px 10px 0 #ccc;
		list-style-type: none;
		
	}

	.dropdown-menu li:hover ul, .dropdown-menu li.dropdown-over ul{
		display:block;
	}

	.dropdown-menu li ul li{
		border:1px solid #c0c0c0; 
		display:block; 
		width:150px;
		list-style-type: none;
	}

	 .abrirCaixa{
	 	position: absolute;
	 	z-index: 998;
	 	height: 100px;
	 	top: 150px;
	    bottom: 0;
	    left: 0;
	    right: 0;
	 }
	 .cash-register{
	 	background-image: url('svg/cash-register-solid.svg');
	 	fill: #fff;
	 	size: 150px;
	 }
</style>

<div ng-controller="ZMProCtrl">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb p-0">
			<li class="breadcrumb-item"><a href="inicio.php?u=<?=$usuario1?>&s=<?=$senha1?>"><i class="fas fa-home"></i></a></li>
			<li class="breadcrumb-item" aria-current="page">Caixas</li>
			<!--li class="breadcrumb-item active" aria-current="page">Abrir e Fechar Caixa</li-->
		</ol>
	</nav>

	<div class="alert {{tipoAlerta}} col-lg-4" role="alert" style="right: 0; position: absolute; z-index: 999">
			{{alertMsg}}
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div ng-if="lista">

		    <div show-on-desktop>
		    	<div class="row bg-dark">
		    		<div class="form-group col-md-6 col-10 pt-3 pb-0">

<?php if (base64_decode($empresa_acesso) == 0) {?>

		    			<label for="empresas">Empresas</label>
						<select class="form-control form-control-sm capoTexto" id="empresa" ng-model="empresa" value="">
							<option value=""> Todas Empresas</option>
							<option ng-repeat="emp in dadosEmpresa" value="{{emp.em_cod}}">{{emp.em_fanta}} </option>

						</select>

									<?php } else {
	echo utf8_encode($dados_empresa["em_fanta"]);
}?>

		    		</div>

		    	</div>

		    	<table class="table table-striped table-borderless" style="background-color: #FFFFFFFF; color: black;" ng-if="caixasVerifica >= 1">
					<thead class="thead-dark">
						<tr>
							<th>Empresa</th>
							<th>Caixa</th>
							<th>Situação</th>
							<th>Data</th>
							<th>Ação</th>

						</tr>

					</thead>
					<tbody>
						<tr dir-paginate="caixa in caixas| filter:{bc_empresa:empresa}  | orderBy:'sortKey':reverse | itemsPerPage:10">
							<td>{{caixa.em_fanta}}</td>
							<td>{{caixa.bc_descricao}}</td>
							<td>{{caixa.bc_situacao}}</td>
							<td>{{caixa.bc_data | date:'dd/MM/yyyy'}}</td>
							<td style="text-align: center;">
								<div class="btn-group dropleft">
									<button type="button" class="btn btn-outline-light p-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-width: 0; color: black;">
					                    <i class="fas fa-ellipsis-v"></i>
					                </button>
					                <div class="dropdown-menu">
					                	<a class="dropdown-item" ng-click="abrirCaixa(caixa.bc_descricao, caixa.bc_id)" ng-if="caixa.bc_situacao == 'Fechado'">Abrir</a>

					                	<a class="dropdown-item"  ng-click="loguin('F')" ng-if="caixa.bc_situacao == 'Aberto'">Fechar</a>

										<a class="dropdown-item"  ng-click="loguin('M')" ng-if="caixa.bc_situacao == 'Aberto'">Movimentar</a>
<?php
	if (substr($me_caixa_ab, 0,1) == 'S') {?>
										
										<a class="dropdown-item"  ng-click="lancAberto(caixa)" ng-if="caixa.bc_situacao == 'Aberto'">Lançamento aberto</a>
<?php
	}
	if (substr($me_caixa_fc, 0,1) == 'S') {
?>
										
										<a class="dropdown-item"  ng-click="loguin('M')" >Lançamento Fechado</a>
<?php } ?>
					                	<a class="dropdown-item"  ng-click="loguin('V')">Verificar Caixa</a>

					                </div>
								</div>
							</td>
						</tr>
					</tbody>

				</table>

				<dir-pagination-controls max-size="5" boundary-links="true" class="ng-isolate-scope"></dir-pagination-controls>

				<div>
					<div class="row justify-content-center" style="height: 100px; margin-top: 15px;" ng-if="caixasVerifica <= 0">
						<div class="card border-dark mb-3" style="width: 25rem; background-color:rgba(0,0,0, .65);">
							<div class="card-header">
								<form>
									<div class="form-row">
										<div class="form-group col-12">
											Nenhum Caixa Criado
											<br>

											<label for="caixa">Deseja Incluir Um Caixa?</label>

											<br>
											<a type="submit" class="btn btn-outline-danger" style="color: white;" href="inicio.php?u=<?=$usuario1?>&s=<?=$senha1?>">
												Não
											</a>

											<button  type="submit" class="btn btn-outline-success" ng-click="loguin('C')">
												Sim
											</button>

										</div>

									</div>

								</form>

							</div>


						</div>

					</div>
<!--abrir caixa-->
					<div class="row justify-content-center abrirCaixa" style="display: none;">

						<div class="card border-dark mb-3" style="width: 25rem; background-color:rgba(0,0,0,1);">
							<div class="card-header">
								<form>
									<div class="form-row">

										<div class="form-group col-12">
											<h3>Abrir Caixa </h3>

											<div class="form-group col-12">
												<label for="dataHora">Data e Hora de Abertura</label>
												<input type="text" class="form-control" id="dataHora" value="{{data |  date:'dd/MM/yyyy HH:mm:ss'}}" readonly>
											</div>
											<md-divider style="background-color:rgba(51,51,51,1);"></md-divider>

										</div>

										<div class="form-group col-6">
											<p>{{nomeCaixa}}</p>
											<p class="cash-register"></p>
											<img src="svg/cash-register-solid.svg" alt="" style="filter: invert(100%) saturate(0%); width: 50px; width: 50px;">
										</div>
										<div class="form-group col-6">
											<label for="valor">Valor (R$)</label>
											<input type="text" class="form-control" autocomplete="off" id="valor" ng-model="valCaixa" money-mask>
										</div>

										<div class="form-group col-12">
											<button type="submit" class="btn btn-outline-danger" style="color: white; margin-left: 250px;" onclick="abrirCaixa()">
													Não
											</button>

											<button  type="submit" class="btn btn-outline-success" ng-click="verificaVal('A',valCaixa,data | date:'yyyy-MM-dd')">
													Sim
											</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<!--final abrir caixa-->

					<div class="row justify-content-center login" style="height: 100px; margin-top: 35px; display: none;">
						<div class="card border-dark mb-3" style="width: 25rem; background-color:rgba(0,0,0, .65);">
							<div class="card-header">
								<form>
									<div class="form-row">
										<label for="caixa">Senha: </label>
										<input type="text" class="form-control form-control-sm pb-0" id="login" ng-model="login" placeholder="Senha do Usuário" onfocus="senhaIn()" ng-enter='caixaUser(login)' autocomplete="off">

										<button  type="submit" class="btn btn-outline-success pull-right" ng-click="caixaUser(login)" style="margin-top: 10px;">
												Confirmar
										</button>
									</div>
								</form>

							</div>

						</div>

					</div>


				</div>
<?php
	include 'modal/caixa/movCaixa.php';
?>

			</div>


		    </div>
		    <?php if (base64_decode($empresa_acesso) == 0) {?>
		    	<!--
				<md-button class="md-fab md-fab-bottom-right color-default-btn" ng-click="MudarVisibilidade(1)" style="position: fixed; z-index: 999; background-color:#279B2D;">
					<md-tooltip md-direction="top" md-visible="tooltipVisible">Abrir Caixa</md-tooltip>
	          		<i class="fas fa-lock-open"></i>
	        	</md-button>

	        -->
	        <?php }?>
		    </div>

			<div ng-if="ficha">
	  			<div class="row justify-content-center" style="height: 100px;">
					<div class="card border-dark mb-3" style="width: 25rem; background-color:rgba(0,0,0, .65);">
						<div class="card-header">
							<form>
								<div class="form-row">
									<div class="form-group col-12">
										<label for="caixa">Selecione o Caixa</label>
										<select class="form-control form-control-sm" id="caixa">
											<option selected>Selecione</option>
										</select>
									</div>
								</div>
							</form>
						</div>
						<div class="card-body">
							<form>
								<div class="form-row">
									<div class="form-group col-12">
										<label for="valor">Valor (R$)</label>
										<input type="number" class="form-control" id="valor">
									</div>

									<div class="form-group col-12">
										<label for="dataHora">Data e Hora de Abertura</label>
										<input type="text" class="form-control" id="dataHora" value="{{data |  date:'dd/MM/yyyy HH:mm:ss'}}" readonly>
									</div>
								</div>
							</form>
						</div>
						<div class="card-footer">
							<button type="submit" class="btn btn-outline-success" ng-click="SalvarCliente()" ng-if="VerificaObrigatorios"><i class="fas fa-save"></i> Salvar</button>
							<button type="submit" class="btn btn-outline-danger" style="color: white;" ng-click="MudarVisibilidade()"><i class="fas fa-window-close"></i> Cancelar</button>
						</div>
					</div>
				</div>
		    </div>
	    </div>
	</div>
</div>


    <script src="js/angular.min.js"></script>
    <script src="js/angular-animate.min.js"></script>
    <script src="js/angular-messages.min.js"></script>
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/mCustomScrollbar.concat.min.js"></script>
	<script src="js/angular-match-media.js"></script>
	<script src="js/angular-material.min.js"></script>
	<script src="js/angular-aria.min.js"></script>
	<script src="js/dirPagination.js"></script>
	<script src="js/material-components-web.min.js"></script>
	<script src="js/angular-locale_pt-br.js"></script>
	<script src="js/jquery.mask.min.js"></script>
	<script src="js/mask/angular-money-mask.js"></script>

    <script  id="INLINE_PEN_JS_ID">

		angular.module("ZMPro",['ngMessages','ngMatchMedia','ngMaterial','angularUtils.directives.dirPagination','money-mask']);
	    angular.module("ZMPro").controller("ZMProCtrl", function ($scope, $http, $timeout, $mdSidenav, $log) {

		    $scope.tab = 1;
			$scope.lista = true;
			$scope.ficha = false;
  			$scope.data = new Date();
  			$scope.urlBase = 'services/'
  			$scope.tipoAlerta = "alert-success";
			$scope.alertMsg = "Cadastro realizado com sucesso";
			$scope.caixasVerifica = 1;
			$scope.status = 'A';
			$scope.login = '';

			//caixas
			$scope.statusCaixa=[];


			<?php if (base64_decode($empresa_acesso) == 0) {?>
				$scope.bc_cod_func='';
			<?php } else {?>
				$scope.bc_cod_func='<?=$us_cod?>';
			<?php }?>

			$scope.dadosEmpresa=[];
			$scope.caixas=[];

			$scope.movCaixa=[];
			$scope.movCaixaAberto=[];


			var dadosEmpresa = function () {
				$http({
					method: 'GET',
					url: $scope.urlBase+'ConsultaCad.php?dadosEmpresa=S&us_id=<?=$us_id?>&e=<?=$empresa?>&eA=<?=$empresa_acesso?>'
				}).then(function onSuccess(response){
					$scope.dadosEmpresa=response.data.result[0];
				}).catch(function onError(response){
					$scope.resultado=response.data;
					alert("Erro ao carregar empresas. Caso este erro persista, contate o suporte.");
			//        alert("idtreinoAluno");
				});
			};
<?php if (base64_decode($empresa_acesso) == 0) {?>
			dadosEmpresa();
<?php }?>



			var caixas = function (empresa){
				$http({
					method:'GET',
					url: $scope.urlBase + 'caixas.php?caixa=S&contrCaixa=S&&us_id=<?=$us_id?>&e=<?=$empresa?>&eA=<?=$empresa_acesso?>&bc_cod_func=' + $scope.bc_cod_func
				}).then(function onSuccess(response){
					$scope.caixas= response.data.result[0];
				}).catch(function onError(response){
					$scope.caixa = response.data;
				});
			};
<?php if (base64_decode($empresa_acesso) == 0) {?>
			caixas();
<?php }?>

			var caixasVerifica = function (empresa){
				$http({
					method:'GET',
					url: $scope.urlBase + 'caixas.php?caixa=S&caixasVerifica=S&&us_id=<?=$us_id?>&e=<?=$empresa?>&eA=<?=$empresa_acesso?>&bc_cod_func=' + $scope.bc_cod_func
				}).then(function onSuccess(response){
					$scope.caixasVerifica= response.data;
					if ($scope.caixasVerifica == 1) {
						caixas();
					}
				}).catch(function onError(response){
					$scope.caixasVerifica = response.data;
				});
			};

<?php if (base64_decode($empresa_acesso) != 0) {?>
			caixasVerifica();
<?php }?>
//caixasVerifica();

		    $scope.MudarVisibilidade = function() {

		    	$scope.lista = !$scope.lista;
		    	$scope.ficha = !$scope.ficha;

		    };

		    var criarCaixa = function(){

		    	$http({
		    		method:'GET',
		    		url: $scope.urlBase + 'caixas.php?caixa=S&criarCaixa=S&&us_id=<?=$us_id?>&e=<?=$empresa?>&eA=<?=$empresa_acesso?>&bc_cod_func=' + $scope.bc_cod_func+'&us_name=<?=base64_encode($dados_usuario['us_fantasia'])?>'
		    	}).then(function onSuccess(response){

		    		if (response.data == 1) {
		    			$scope.tipoAlerta = "alert-success";
		    			$scope.alertMsg = "Caixa Incluido Com Sucesso";
		    			chamarAlerta();
		    			caixasVerifica();
		    			caixas();
		    		}else{
		    			chamarAlerta();
		    			$scope.tipoAlerta = "alert-danger";
		    			$scope.alertMsg = "Caixa Não Pode Ser Incluido";
		    		}

		    	}).catch(function onError(response){

		    	});
		    };

		    var abrirCaixaBC = function(){
		    	var caixa = $scope.statusCaixa;
		    	$http({
		    		method:'POST',
		    		headers: {
			           'Content-Type':'application/json'
			         },
			          data: {
			          	caixa
			          },
		    		url: $scope.urlBase + 'caixas.php?caixa=S&abrirCaixa=S&&us_id=<?=$us_id?>&e=<?=$empresa?>&eA=<?=$empresa_acesso?>&bc_cod_func='+ $scope.bc_cod_func+'&us_name=<?=base64_encode($dados_usuario['us_fantasia'])?>'
		    	}).then(function onSuccess(response){

		    		if (response.data == 1) {
		    			$scope.tipoAlerta = "alert-success";
		    			$scope.alertMsg = "Caixa Aberto";
		    			chamarAlerta();
		    			caixasVerifica();
		    			caixas();
		    		}else{
		    			chamarAlerta();
		    			$scope.tipoAlerta = "alert-danger";
		    			$scope.alertMsg = "Caixa Não Pode Ser Aberto";
		    		}

		    	}).catch(function onError(response){

		    	});
		    };


		    $scope.loguin = function (e){
		    	$scope.status = e;
		    	loguin();
		    }

		    $scope.verificaVal = function(e,val,data){
		    	console.log('status= '+e + 'valor='+val);
		    	$scope.statusCaixa=[{
		    		bc_id : btoa($scope.idCaixa),
		    		bc_data : data,
		    		bc_val : val
		    	}];

		    	if (val == undefined){
		    		chamarAlerta();
	    			$scope.tipoAlerta = "alert-danger";
	    			$scope.alertMsg = "O Caixa Não Pode Ser Aberto Com R$0,00";
		    	}
		    	else if(val == 0){
					chamarAlerta();
	    			$scope.tipoAlerta = "alert-danger";
	    			$scope.alertMsg = "O Caixa Não Pode Ser Aberto Com R$0,00";
		    	}
		    	else if(val == '00') {
					chamarAlerta();
	    			$scope.tipoAlerta = "alert-danger";
	    			$scope.alertMsg = "O Caixa Não Pode Ser Aberto Com R$0,00";
		    	}
		    	else if(val == '0,00'){
					chamarAlerta();
	    			$scope.tipoAlerta = "alert-danger";
	    			$scope.alertMsg = "O Caixa Não Pode Ser Aberto Com R$0,00";
		    	}
		    	else if(val == 'R$0,00'){
		    		chamarAlerta();
	    			$scope.tipoAlerta = "alert-danger";
	    			$scope.alertMsg = "O Caixa Não Pode Ser Aberto Com R$0,00";
		    	}
		    	else{
		    		abrirCaixa();
		    		 $scope.loguin(e);
		    		 console.log($scope.statusCaixa);
		    	}
		    }

		    $scope.abrirCaixa = function(e,id){
				$scope.nomeCaixa=e;
				$scope.idCaixa=id;
		    	abrirCaixa();
		    }

		    $scope.caixaUser = function(login,status){
		    	//console.log(login);
		    	$http({
		    		method: 'POST',
		    		 headers: {
			           'Content-Type':'application/json'
			         },
			          data: {
			          	us_id:'<?=$us_id?>',
			          	pass: btoa(login),
			          	e: '<?=$empresa?>'
			          },
			          url: $scope.urlBase + 'verifica_login.php'
		    	}).then(function onSuccess(response){
		    		if (response.data == 1) {
		    			loguin();
		    			if ($scope.status == 'C') {
		    				criarCaixa();
		    			}
		    			else if($scope.status == 'A'){
		    				abrirCaixaBC();
		    				console.log('Abrir')
		    			}
		    			else if($scope.status == 'F'){
		    				console.log('Fechar')
		    			}
		    			else if($scope.status == 'M'){
		    				console.log('Mover')
		    			}

		    			document.getElementById('login').value='';

		    		}else{
		    			chamarAlerta();
		    			$scope.tipoAlerta = "alert-danger";
		    			$scope.alertMsg = "Login Do Usuário Incorreto";


		    		}
		    	}).catch(function onError(response){

		    	});
			}
			
			//movimentação aberta

			$scope.lancAberto = function(caixa){
				$http({
					method:'GET',
					url: $scope.urlBase + 'caixas.php?caixa=S&contrCaixa=S&&us_id=<?=$us_id?>&e=<?=$empresa?>&eA=<?=$empresa_acesso?>&token=<?=$token?>&bc_cod_func=' + $scope.bc_cod_func+'&bc_id='+btoa(caixa.bc_id)
				}).then(function onSuccess(response){

					$scope.movCaixa= response.data.result[0];

					$http({
					method:'GET',
						url: $scope.urlBase + 'caixas.php?movimentacao=S&movAb=S&&us_id=<?=$us_id?>&e=<?=$empresa?>&eA=<?=$empresa_acesso?>&token=<?=$token?>&bc_cod_func=' + $scope.bc_cod_func+'&bc_id='+btoa(caixa.bc_id)
					}).then(function onSuccess(response){
					
						$scope.movCaixaAberto = response.data.result[0];
						$('#movCaixa').modal('show');

					}).catch(function onError(response){

					});

				
					$('#movCaixa').modal('show');
				}).catch(function onError(response){

				});
				console.log($scope.movCaixa);
			}


		}).directive('ngEnter', function () {
		   return function (scope, element, attrs) {
		     element.bind("keydown keypress", function (event) {
		       if(event.which === 13) {
		         scope.$apply(function (){
		           scope.$eval(attrs.ngEnter);
		         });
		         event.preventDefault();
		       }
		     });
		   };

		 }).directive("moneyDir", function MoneyDir() {

			return {
				link : function(scope, element, attrs) {
					var options = {
						onKeyPress: function(val, e, field, options) {
							putMask();
						}
					}

					$(element).mask('999.999.990,00', {reverse: true});

					function putMask() {
						var mask;
						var cleanVal = element[0].value.replace(/\D/g, '');//pega o valor sem mascara
						if(cleanVal.length > 3) {//verifica a quantidade de digitos.
							mask = "999.999.990,00";
						}
					$(element).mask(mask, options);//aplica a mascara novamente
					}
				}
			}
		});


	</script>


	<script type="text/javascript">


		function chamarAlerta(){
			$('.alert').toggle("slow");
			setTimeout( function() {
				$('.alert').toggle("slow");
			},3000);
		};
		function loguin(){
			$('.login').toggle("slow");
		}
		function abrirCaixa(){
			$('.abrirCaixa').toggle("slow");
		}

		function senhaIn(e){
	 		$('#login').attr('type', 'password');
		}

		function senhaOut(){
			//$('#login').attr('type', 'text');
			//alert('certo');
		}


	    $(document).ready(function () {
	        $("#sidebar").mCustomScrollbar({
	            theme: "minimal"
	        });

	        $('#sidebarCollapse').on('click', function () {
	            $('#sidebar, #content').toggleClass('active');
	            $('.collapse.in').toggleClass('in');
	            $('a[aria-expanded=true]').attr('aria-expanded', 'false');
	        });
	    });

	</script>


</body>
</html>