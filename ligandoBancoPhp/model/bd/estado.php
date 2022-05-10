<?php

/*************************************************************************
 * Objetivo: Arquivo responsável por manipular os dados dentro do BD
 *          (select)
 * Autor: Laise na aula junto com o professor Marcel
 * Data:10/05/2022 
 * Versão: 1.0      
 **************************************************************************/

//Importe do arquivo de configuração do projeto
require_once('modulo/config.php');
require_once('conexaoMysql.php');

//Função para listar todos os estados do BD
function selectAllEstados()
{
    //Abre a conexão com o BD
    $conexao = conexaoMysql();

    //script para listar todos os dados do BD
    $sql = "select * from tblestados order by nome asc"; /*asc = crescente */

    //Executa o script sql no BD e guarda o retorno dos dados, se houver
    $result = mysqli_query($conexao, $sql);

    //Valida se o BD retornou registros
    if($result)
    {
        //mysqli_fetch_assoc - permite converter os dados em um array para manipulação no php
        //Nessa repetição estamos, convertando os dados do BD em um array ($rsDados), além de
        //o próprio while conseguir gerenciar a qtde de vezes que deverá ser feita a repetição

        $cont = 0;
        while($rsDados = mysqli_fetch_assoc($result))
        {
            //Cria um array com os dados do BD
            $arrayDados[$cont] = array (
                "idestado"       => $rsDados['idestado'],
                "nome"     => $rsDados['nome'],
                "sigla" => $rsDados['sigla'],
            );

            $cont++;
        }

        //solicita o fechamneto da conexão com o BD
        fecharConexaoMysql($conexao);

        return $arrayDados;

    }

}

