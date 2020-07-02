            <div class="modal fade" id="ModalContaPagar" tabindex="-1" role="dialog" aria-labelledby="ModalContaPagar" aria-hidden="true" data-backdrop="static">
              <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="color: black;" >
                <div class="modal-content">
                  <div class="modal-header">
                    <h3 style="color: black !important;" >Lançar Conta a Pagar</h3>
                  </div>
                  <div class="modal-body">
                    <div class="container-fluid">
                      <form id="formConta">
                          <div class="row">
                          <div class="form-group col-md-3" >
                            <label for="numDocumento">Documento</label>
                            <input type="text" class="form-control" id="numDocumento" ng-model="despesa.ct_docto">
                            <input type="hidden" class="form-control" ng-value="empresa" ng-model="despesa.ct_empresa">
                            <input type="hidden" class="form-control" id="numDocumento" ng-model="despesa.ct_matriz">
                          </div>
                          <div class="form-group col-md-9">
                            <label for="selectFornecedor">Fornecedor</label>
                            <div class="input-group">
                              <select class="form-control" id="selectFornecedor" ng-model="despesa.ct_cliente_forn" >
                                <option value="">Selecione o Fornecedor</option>
                                <option ng-repeat="fornecedor in dadosFornecedores" ng-value="fornecedor.pe_id">{{fornecedor.pe_nome}}</option>
                              </select>
                              <input type="hidden" class="form-control" id="inputFornecedor" ng-value="fornecedor.pe_nome" ng-model="despesa.ct_nome">
                              <div class="input-group-btn">
                                <button type="button" class="btn btn-outline-light" style="color: black;" data-toggle="tooltip" data-placement="top" title="Adicionar Fornecedor" ng-click="goPagina()">
                                  <i class="fas fa fa-plus"></i>
                                </button>
                              </div>
                            </div>
                          </div>
                          <div class="form-group col-md-5">
                            <label for="ct_emissao">Data Emissão</label>
                            <input type="date" class="form-control" id="ct_emissao" value="{{dataI}}" ng-model="despesa.ct_emissao">
                          </div>
                          <div class="form-group col-md-7" >
                            <label for="formaPagto">Forma de Pagamento</label>
                            <select class="form-control" id="formaPagto" ng-model="despesa.ct_tipdoc" ng-blur="ConsultaCaixa(despesa.ct_tipdoc)">
                              <option value="">Selecione a Forma de Pagamento</option>
                              <option ng-repeat="forma in dadosFormaPagto" ng-value="forma.dc_codigo">{{forma.dc_descricao}} </option>
                            </select>
                          </div>
                          <div class="form-group col-md-12" >
                            <label for="histPagto">Descrição da Despesa</label>
                            <input type="text" class="form-control" id="descHistorico" ng-model="despesa.ct_obs" onKeyUp="tabenter(event,getElementById('inputValor'))">
                          </div>
                          <div class="form-group col-md-4">
                            <label for="inputValor">Valor Principal</label>
                            <input type="text" class="form-control" id="inputValor" ng-model="despesa.inputValor"  money-dir>
                          </div>
                          <div class="form-group col-md-6" >
                            <label for="histPagto">Histórico Pagamento</label>
                            
                            <select class="form-control" id="histPagto" ng-model="despesa.ct_historico" ng-change="numParcelasNota(despesa.ct_emissao  | date: 'yyyy-MM-dd', despesa.inputValor, qtdParcelas)" value="">
                              <option value="">Selecione o Histórico</option>
                              <option ng-repeat="historico in dadosHistorico" value="{{historico.ht_cod}}" ng-if="historico.ht_dc == 'D'" >{{historico.ht_descricao}} </option>
                            </select>

                          </div>
                          <div class="form-group col-md-2" ng-init="qtdParcelas = 1">
                            <label for="qtdParcelas">Qtde. Parcelas</label>
                            <input type="number" class="form-control" id="qtdParcelas" value = "1" ng-model="qtdParcelas" ng-blur="numParcelasNota(despesa.ct_emissao | date: 'yyyy-MM-dd', despesa.inputValor, qtdParcelas)" onKeyUp="tabenter(event,getElementById('dataVencto'))">
                          </div>
                        </div>

                        <div class="row" ng-show="dinheiro">
                          <div class="col-4 mt-0" >
                            <md-switch ng-model="selecionarCaixa" class="fa-pull-right" ng-disabled="qtdParcelas != 1">
                            Lançar no Caixa
                            </md-switch>
                          </div>
                          <div class="col-8" ng-show="selecionarCaixa">
                            <select class="form-control" id="caixa" ng-model="bc_codigo" >
                              <option value="">Selecione o Caixa</option>
                              <option ng-repeat="caixa in CaixasAbertos" ng-value="caixa.bc_codigo">{{caixa.bc_descricao}} </option>
                            </select>
                          </div>
                        </div>

                        <div class="row" ng-repeat="parc in notaParcelas" ng-if="despesa.ct_historico">
                          <div class="form-group col-md-2" >
                            <label for="numParcela">Parcela</label>
                            <input type="text" class="form-control" id="numParcela" ng-model="parc.vezes" readonly>
                          </div>
                          <div class="form-group col-md-5" >
                            <label for="dataVencto">Vencimento</label>
                            <input type="date" class="form-control" id="dataVencto" value="{{parc.vencimento}}" ng-model="despesa.vencimento">
                          </div>
                          <div class="form-group col-md-5" >
                            <label for="valParcela">Valor por Parcela</label>
                            <input type="text" class="form-control" id="valParcela" ng-value="parc.parcela | currency: 'R$ '" ng-model="despesa.parcela">
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <a href="FinPagar.php?u=<?=$usuario1?>&s=<?=$senha1?>" class="btn btn-lg btn-secondary">Concluir Lançamentos</a>
                    <button type="button" class="btn btn-lg btn-primary" ng-click="AdicionarDespesa(despesa, selecionarCaixa, bc_codigo, ct_emissao | date: 'yyyy-MM-dd')" ng-if="despesa.ct_tipdoc && despesa.ct_historico && notaParcelas[0].vezes">Lançar Despesa</button>


                  </div>
                </div>
              </div>
            </div>
