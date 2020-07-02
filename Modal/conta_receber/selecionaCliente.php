
<div class="modal fade" id="selecionaCliente" tabindex="-1" role="dialog" aria-labelledby="ModalLancarDespesas" aria-hidden="true" data-backdrop="static">
    
        <div class="modal-dialog modal-lg" role="document" style="color: black;">

            <div class="modal-content" style="box-shadow: -0.5em 0.5em 0.5em rgba(0,0,0,0.5);">
                <div class="modal-header">
                    <h4 style="color: black !important;" >Cliente</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button><br>
                </div>

                <div class="modal-body">
                    <span>Nome: </span>
                    <input type="text" class="form-control form-control-sm" id="nomeCliente" ng-model="nomeCliente" value="">

                    <table class="table table-striped" style="background-color: #FFFFFFFF; color: black;">
                    <thead class="thead-dark">
                        <tr style="font-size: 1em !important;">
                            <th scope="col" style=" font-weight: normal;" ng-click="ordenar('pe_codigo')">Código</th>
                            <th scope="col" style=" font-weight: normal;" ng-click="ordenar('pe_nome')">Nome/Razão</th>
                            <th scope="col" style=" font-weight: normal;" ng-click="ordenar('pe_endereco')">Endereço</th>
                        </tr>
                    </table>
                    <tbody>
                        <tr dir-paginate="buscaCliente in  buscaCliente | orderBy:'sortKey' | itemsPerPage:10 | filter:{pe_nome:nomeCliente}" pagination-id="selectCliente" ng-click="selectCliente(buscaCliente)">
                            <td ng-bind="buscaCliente.pe_cod" style="width:10px"></td>
                            <td ng-bind="buscaCliente.pe_nome"></td>
                            <td ng-bind="buscaCliente.pe_endereco"></td>
                        </tr>
                    
                    </tbody>
                    </table>
                    <dir-pagination-controls max-size="5" boundary-links="true" class="ng-isolate-scope" pagination-id="selectCliente" ></dir-pagination-controls>
                </div>
                <div class="modal-footer">

                </div>
            
            </div>

        </div>
    
   
</div>
