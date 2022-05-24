<?php

 /*************************************************************************************
     * Objetivo: Arquivo responsável pela criação de váriaveis e constantes do projeto
     * Autor: Laise na aula junto com o professor Marcel
     * Data:25/04/2022
     * Versão: 1.0   
 *****************************************************************************/

 //Limitação de 5mb para upload de imagens
 const MAX_FILE_UPLOAD = 5120;

 const EXT_FILE_UPLOAD = array("image/jpg", "image/jpeg", "image/gif", "image/png");
 
 const DIRETORIO_FILE_UPLOAD = "/arquivos/";


 //diretorio raiz do projeto
 define('SRC', $_SERVER['DOCUMENT_ROOT'].'/Laise/LigacaoComBanco/ligandoBancoPhp');

 //função para converter um array em formato json
 function createJSON($arrayDados)
 {
     //validação para tratar array sem dados
     if(!empty($arrayDados)){
         //json_encode converte o array para json
         //json_decode faz o inverso

         //configura o padrão da conversão para formato json
         header('Content-Type: application/json');

         return json_encode($arrayDados);
     }else{
         return false;
     }
 }
