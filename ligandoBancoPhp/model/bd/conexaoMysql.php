<?php

/**********************************************************
 * Objetivo: Arquivo para criar a conexão BD Mysql
 * Autor: Laise na aula junto com o professor Marcel
 * Data:25/02/2022
 * Versão: 1.0
 ************************************************************/

//constante para estabelecer a conexão com o BD (local do BD, usuário, senha e database)
const SERVER = 'localhost';
const USER = 'root';
const PASSWORD = 'bcd127';
const DATABASE = 'dbcontatos';


//Abre a conexão com o BD Mysql
function conexaoMysql()
{
    $conexao = array();

    //Se a conexão for estabelecida com o BD, iremos ter um array de dados sobre a conexão
    $conexao = mysqli_connect(SERVER, USER, PASSWORD, DATABASE);

    //Validação para verificar se a conexão foi realizada com sucesso
    if($conexao)
        return $conexao;
    else
        return false;
}


/*
    Existem 3 formas de criar a conexão com o BD Mysql (específico para PHP)

        mysql_connect() - versão antiga do PHP de fazer a conexão com BD
            (Não oferece performace e segurança, só para programção POO)

        mysqli_connect() - versão mais atualizada do PHP de fazer a conexão com BD
            (ela permite ser utilizada para programação POO e estruturada)
        
        PDO() - versão mais completa e eficiente para conexão com BD
            (é indicada pela segurança e POO)
*/
