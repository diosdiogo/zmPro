
<div class="modal fade" id="baixarContaReceber" tabindex="-1" role="dialog" aria-labelledby="ModalLancarDespesas" aria-hidden="true" data-backdrop="static">
    
        <div class="modal-dialog modal-lg" role="document" style="color: black;">

            <div class="modal-content" style="box-shadow: -0.5em 0.5em 0.5em rgba(0,0,0,0.5);">
                <div class="modal-header">
                    <h3 style="color: black !important;" >Baixar Conta Receber</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button><br>
                </div>

                <div class="modal-body">
                    <div layout="row" layout-xs="column">
                        <div flex="">
                            <div layout="row" layout-wrap="">
                                <div flex="30">
                                    <span>Cliente</span>
                                    <input type="text" class="form-control form-control-sm" id="codCliente" ng-model="codCliente" value="" ng-blur="seachClienteByCod(codCliente)" onkeypress="return somenteNumeros(event)">
                                </div>
                                <div flex="70">
                                    <span style="color: #fff;">.</span>
                                    <input type="text" class="form-control form-control-sm" id="nomeCliente" ng-model="nomeCliente" value="">
                                </div>
                            </div>
                        </div>

                        <div flex="">
                            
                        </div>
                    </div>

                </div>
                <div class="modal-footer">

                </div>
            
            </div>

        </div>
    
   
</div>
