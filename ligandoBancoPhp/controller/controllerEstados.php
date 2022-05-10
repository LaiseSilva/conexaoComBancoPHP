<?php

/**************************************************************************************
 * Objetivo: Arquivo responsável pela manipulação de dados de estados
 *      obs(Este arquivo a ponte entre a view e a model)
 * Autor:
 *  Data: 10/05/2022  
 *  Versão: 1.0  
 ****************************************************************************************/

//Importe do arquivo de configuração do projeto
require_once('modulo/config.php');


//Função para solicitar os dados da model e encaminhar a lista de estados para a view
function  listarEstado()
{
    //Importe do arquivo que vai buscar os dados no BD
    require_once('model/bd/estado.php');

    //Chama a função que vai buscar os dados no BD
    $dados = selectAllEstados();

    if (!empty($dados))
        return $dados;
    else
        return $false;
}