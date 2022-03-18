<?php
    /*************************************************************************
     * Objetivo: Arquivo responsável por manipular os dados dentro do BD
     *          (insert,uptade,select e delete)
     * Autor: Laise na aula junto com o professor Marcel
     * Data:11/03/2022   18/03/2022
     * Versão: 1.0       2.0
    **************************************************************************/

    //Estabelece a conexão com o BD
    require_once('conexaoMysql.php');

    //Função para realizar o insert no BD
    function insertContato($dadosContatos)
    {
        //Abre a conexão com o BD
        $conexao = conexaoMysql();

        //Monta o script para enviar para o BD
        //Obs: números inteiros(int) não precisa de aspas simples
        $sql = "insert into tblcontatos
                    (nome,
                     telefone,
                     celular,
                     email,
                     obs)
                values
                    ('".$dadosContatos['nome']."',
                     '".$dadosContatos['telefone']."',
                     '".$dadosContatos['celular']."',
                     '".$dadosContatos['email']."',
                     '".$dadosContatos['obs']."');";

        //Executa script no BD, manda para o banco
            //Validação para verificar se o script sql está correto
        if(mysqli_query($conexao, $sql))
        {
            //Validação para verificar se uma linha foi acrescentada no BD
            if(mysqli_affected_rows($conexao))
                return true;
            else
                return false;
        }    
        else
            return false;
        
    }

    

    //Função para realizar o uptade no BD
    function uptadeContato()
    {

    }
    
    //Função para excluir no BD
    function deleteContato()
    {

    }

    //O único que retorna dados(não devolve certo ou errado)
    //Função para listar todos os contatos do BD
    function selectAllContatos()
    {
        //Abre a conexão com o BD
        $conexao = conexaoMysql();

        //script para listar todos os dados do BD
        $sql = "select * from tblcontatos";

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
                    "nome"     => $rsDados['nome'],
                    "telefone" => $rsDados['telefone'],
                    "celular"  => $rsDados['celular'],
                    "email"    => $rsDados['email'],
                    "obs"      => $rsDados['obs'],
                );

                $cont++;
            }

            return $arrayDados;

        }

    }


?>