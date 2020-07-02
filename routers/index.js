var compression = require('compression');
const http = require('http');
const express = require('express');
const app = express();         
const bodyParser = require('body-parser');
const port = 3000; //porta padrão
const mysql = require('mysql');

const server = http.createServer((req, res) => {
    res.statusCode = 200;
    res.setHeader('Content-Type', 'text/plain');
    

  });
app.use(compression());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

const router = express.Router();
router.get('/relatorio/dadosRegistro/:token/', (req, res) => {
    
    let filterDataInicial = ' and vd_emis between "'+ req.body.dataInicial+'"';
    let filterDataFinal = ' and "'+ req.body.dataFinal+ '"';
    execSQLQuery('SELECT vendas.vd_id, vendas.vd_doc, vendas.vd_canc, vendas.vd_aberto, vendas.vd_forma, '+ 
    ' vendas.vd_status, vendas.vd_pgr, vendas.vd_cli, vendas.vd_func, vendas.vd_empr, empresas.em_fanta, '+
    'vendas.vd_matriz, DATE_FORMAT(vendas.vd_emis, "%Y-%m-%d") as vd_emis, vendas.vd_valor, vendas.vd_desc, vendas.vd_total, vendas.vd_vl_pagto_dn, '+
    'vendas.vd_vl_pagto_ca , vendas.vd_vl_pagto_bl, vendas.vd_vl_pagto_dp, vendas.vd_nome '+
    'FROM vendas inner join empresas on (vendas.vd_empr = empresas.em_cod) '+
    'where vd_matriz =(select em_cod from empresas where em_token="'+ req.params.token +'")' + filterDataInicial + filterDataFinal+' and vd_canc<>"S" and vd_pgr<> "D"', res);
   console.log('body:', filterDataInicial);
 });

app.use('/', router);

//iniciando o servidor
app.listen(port);
console.log('API Rodando porta: '+ port);

function execSQLQuery(sqlQry, res){
    const connection = mysql.createConnection({
      host : 'db-brasilmobile.caungtcgcwfr.sa-east-1.rds.amazonaws.com',
      //port : 3000,
      user :'root',
      password : 'lxmcz2016',
      database : 'zmpro'
});

connection.query(sqlQry, function(error, results, fields){
    if(error) {
      res.json(error);
      console.log('erro');
    }
    else{
      res.json(results);
    connection.end();
    console.log('executou!');
    }
});


}