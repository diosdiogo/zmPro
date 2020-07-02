<div class="modal fade" id="movCaixa" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" style="color:#000;">Movimentações de Caixa <span ng-if="caixasMod == 'caixa_aberto'"> Aberto</span> <span ng-if="caixasMod == 'caixa_fechado'"> Fechado</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button><br>

            </div>

            <div style="color:#000; margin-left:15px;">

                <div class="row">
                    <div class="col-10">
                        <span style="font-weight:bold;">Caixa: {{status}}</span> <span> {{movCaixa[0].bc_descricao}} </span><br>
                        <span style="font-weight:bold;">Data Abertura: </span> <span> {{movCaixa[0].bc_data | date : 'dd/MM/yyyy'}} </span>
                    </div>
                

                    <!--div class="col-2"> 
                        <div class="">
                            <LABEL></LABEL>
                            <md-button class="btnSalvar pull-right" style="border: 1px solid #279B2D; border-radius: 5px;" ng-click="print('Movimentações de Caixa Aberto')">
                                <md-tooltip md-direction="top" md-visible="tooltipVisible">Imprimir</md-tooltip>
                                <i class="fas fa-print" style=""></i> Imprimir
                            </md-button>
                        </div>
                    </div-->
                </div>
                
                
                    
                
                
            </div>
            <hr>
            
            <div class="modal-body">
                <table id="movimento" class="table table-striped table-borderless" style="background-color: #FFFFFFFF; color: black;">
                    <thead class="thead-dark">
                        <tr>
                            <th>Descrição</th>
                            <th align="center">Débito / Credito</th>
                            <th>Valor</th>
<?php
	if (substr($me_caixa_ab, 3,1) == 'S') {?>
                            <th ng-if="caixasMod == 'caixa_aberto'"> Ação </th>
<?php } ?>                  

                        </tr>
                        
                        <tr dir-paginate="caixa in movCaixaAberto | orderBy:'sortKey':reverse | itemsPerPage:6" pagination-id="caixaAB" ng-click="">
                            <td ng-bind="caixa.cx_nome"></td>
                            <td>
                                <span ng-if="caixa.cx_dc == 'D'">Débi.</span>
                                <span ng-if="caixa.cx_dc == 'C'">Créd</span>
                            </td>
                            <td ng-bind="caixa.cx_valor | currency: 'R$'"></td>
<?php
if (substr($me_caixa_ab, 3,1) == 'S') {?>
                            <td align="center" ng-if="caixasMod == 'caixa_aberto'">
                            <div class="btn-group dropleft">
									<button type="button" class="btn btn-outline-light p-0" data-toggle="dropdown" data-static="false" aria-haspopup="true" aria-expanded="false" style="border-width: 0; color: red;">
					                    <i style="color:#520505;" class="fas fa-trash"></i>
					                </button>
					                <div class="dropdown-menu">
					                	<div class="dropdown-header">
                                            <h5 style="color:#000;">Senha Do Usuário Do Caixa</h5>
                                        </div>
                                        <div class="dropdown-item">
                                            <input type="password" class="form-control form-control-sm pb-0" id="login" ng-model="login" placeholder="Senha do Usuário" onfocus="senhaIn();" ng-enter='caixaUser(login)' autocomplete="foo">
                                            
                                            <button type="submit" class="btn btn-outline-danger pull-right" style="color: #000; margin-top: 10px">
                                                Cancelar
                                            </button>

                                            <button  type="submit" class="btn btn-outline-success pull-right" ng-click="removerMov(login,'E',caixa.cx_id)" style="margin-top: 10px;">
											    Confirmar
                                            </button>
                                           
                                        </div>

                                    </div>
                            </div>
<?php } ?>
                        </tr>

                    </thead>
                </table>
                <dir-pagination-controls max-size="5" boundary-links="true" class="ng-isolate-scope" pagination-id="caixaAB" style="background-color: #000; color: #ffffff;"></dir-pagination-controls>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <!--button type="button" class="btn btn-primary">OK</button-->
            </div>
        </div>
    </div>
</div>