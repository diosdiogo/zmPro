
<div class="modal fade" id="editarContaAPagar" tabindex="-1" role="dialog" aria-labelledby="ModalLancarDespesas" aria-hidden="true" data-backdrop="static">
    <div>
        <div class="modal-dialog" role="document" style="color: black;" >
            <div class="modal-content" style="box-shadow: -0.5em 0.5em 0.5em rgba(0,0,0,0.5);">
            <div class="modal-header">
                <h3 style="color: black !important;" >Editar Conta A Pagar</h3>
            </div>
            <div class="modal-body">
                <div class="container-fluid">

                    <div style="color:#000;">
                    <span style="font-weight:bold;margin-right:5px;">Docto: </span> <span style="margin-right:25px;;"> {{contas[0].ct_docto}} </span> <br>
                    
                    <span style="font-weight:bold;">Nome Fornecedor: </span> 
                    <select class="form-control form-control-sm" id="fornecedor" ng-model="fornecedor" ng-init="fornecedor = contas[0].ct_cliente_fornecedor_id">
                        <option value="">Todos os Fornecedores</option>
                        <option ng-repeat="dadosCliente in dadosFornecedores | filter:{pe_empresa:<?=base64_decode($empresa)?>}" value="{{dadosCliente.pe_id}}" ng-selected="dadosCliente.pe_cod == contas[0].ct_cliente_forn">{{dadosCliente.pe_nome}} </option>
                    </select>
                                            
                    </div>
                    <hr>
                    <form id="formConta">
                    
                        <div class="row" style="margin-top:8px;">
                            
                            
                            <div class="col-3" ng-init="parc = contas[0].ct_parc">
                                <span style="font-weight:bold;">Parcela: </span>
                                <input type="text" class="form-control form-control-sm" ng-model="parc" ng-value="contas[0].ct_parc" parcela-dir>
                            </div>

                            <div class="col-5" ng-init="mudarVencimento = contas[0].ct_vencto">
                                <span style="font-weight:bold; margin-right:15px; margin-top:8px;">Vencto.: </span> 
                                <input type="date" class="form-control form-control-sm" ng-model="mudarVencimento" ng-value="contas[0].ct_vencto">
                            </div>

                            <div class="col-4" ng-init="mudarValor = contas[0].ct_valor">
                                <span style="font-weight:bold; margin-right:15px; margin-top:8px;">Valor: </span> 
                                <input type="text" class="form-control form-control-sm" ng-model="mudarValor" ng-value="contas[0].ct_valor" money-mask>
                            </div>

                                                       
                        </div>
                        <div class="row" style="margin-top:8px;">

                            <div class="col-12" ng-init="descricao = contas[0].ct_obs">
                                <span style="font-weight:bold; margin-right:15px; margin-top:8px;">Descrição </span> 
                                <input type="text" class="form-control form-control-sm" ng-model="descricao" ng-value="contas[0].ct_obs">
                            </div>

                        </div>
                        
                    </form>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-lg btn-secondary">Fechar</a>
                <button type="button" class="btn btn-lg btn-primary" ng-click="editarDispesa(parc, mudarVencimento | date: 'yyyy-MM-dd', mudarValor, mudarTipoDocto, descricao, fornecedor)">Editar Despesa</button>


            </div>
            
            </div>
        </div>
    
    </div>
</div>
