<?php
    /*************************************************************************
     * Objetivo: Arquivo responsável por manipular os dados dentro do BD
     *          (insert,uptade,select e delete)
     * Autor: Laise na aula junto com o professor Marcel
     * Data:11/03/2022   18/03/2022     25/03/2022      01/04/2022  08/04/2022  26/04/2022
     * Versão: 1.0       2.0            3.0             4.0         5.0         6.0
    **************************************************************************/

    //Estabelece a conexão com o BD
    require_once('conexaoMysql.php');

    //Função para realizar o insert no BD
    function insertContato($dadosContatos)
    {

        //Declaração para variável para utilizar no return da função
        $statusResposta = (boolean) false;

        //Abre a conexão com o BD
        $conexao = conexaoMysql();

        //Monta o script para enviar para o BD
        //Obs: números inteiros(int) não precisa de aspas simples
        $sql = "insert into tblcontatos
                    (nome,
                     telefone,
                     celular,
                     email,
                     obs,
                     foto,
                     idestado)
                values
                    ('".$dadosContatos['nome']."',
                     '".$dadosContatos['telefone']."',
                     '".$dadosContatos['celular']."',
                     '".$dadosContatos['email']."',
                     '".$dadosContatos['obs']."',
                     '".$dadosContatos['foto']."',
                     '".$dadosContatos['idEstado']."');";

        //Executa script no BD, manda para o banco
            //Validação para verificar se o script sql está correto
        if(mysqli_query($conexao, $sql))
        {
            //Validação para verificar se uma linha foi acrescentada no BD
            if(mysqli_affected_rows($conexao))
                $statusResposta = true;
        }    
        
            //Solicita o fechamento da conexão com o BD
            fecharConexaoMysql($conexao);

            return $statusResposta;
       
    }

    //Função para realizar o uptade no BD
    function uptadeContato($dadosContatos)
    {
        //Declaração para variável para utilizar no return da função
        $statusResposta = (boolean) false;

        //Abre a conexão com o BD
        $conexao = conexaoMysql();

        //Monta o script para enviar para o BD
        //Obs: números inteiros(int) não precisa de aspas simples
        $sql = "update tblcontatos set
                        nome       = '".$dadosContatos['nome']."',
                        telefone   = '".$dadosContatos['telefone']."',
                        celular    = '".$dadosContatos['celular']."',
                        email      = '".$dadosContatos['email']."',
                        obs        = '".$dadosContatos['obs']."',
                        foto       = '".$dadosContatos['foto']."',
                        idestado   = '".$dadosContatos['idestado']."'
                where idcontato=".$dadosContatos['id'];

        //Executa script no BD, manda para o banco
            //Validação para verificar se o script sql está correto
        if(mysqli_query($conexao, $sql))
        {
            //Validação para verificar se uma linha foi acrescentada no BD
            if(mysqli_affected_rows($conexao))
                $statusResposta = true;
        }    
        
            //Solicita o fechamento da conexão com o BD
            fecharConexaoMysql($conexao);

            return $statusResposta;
    }
    
    //Função para excluir no BD
    function deleteContato($id)
    {
        //Declaração para variável para utilizar no return da função
        $statusResposta = (boolean) false;

        //Abre a conexão com o bando de dado
        $conexao = conexaoMysql();

        //script para deletar um resgistro do BD
        $sql = "delete from tblcontatos where idcontato=".$id;

        //Valida se o script está correto, sem erro de sintaxe e executa no BD
        if(mysqli_query($conexao, $sql))
        {
            //Valida se o BD teve sucesso na execução do script
            if(mysqli_affected_rows($conexao))
                $statusResposta = true;
        }

        //Fecha a conexão com o BD mysql
        fecharConexaoMysql($conexao);
        return $statusResposta;

    }

    //O único que retorna dados(não devolve certo ou errado)
    //Função para listar todos os contatos do BD
    function selectAllContatos()
    {
        //Abre a conexão com o BD
        $conexao = conexaoMysql();

        //script para listar todos os dados do BD
        $sql = "select * from tblcontatos order by idcontato desc"; /*asc = crescente */

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
                    "id"       => $rsDados['idcontato'],
                    "nome"     => $rsDados['nome'],
                    "telefone" => $rsDados['telefone'],
                    "celular"  => $rsDados['celular'],
                    "email"    => $rsDados['email'],
                    "obs"      => $rsDados['obs'],
                    "foto"     => $rsDados['foto'],
                    "idestado" => $rsDados['idestado']
                );

                $cont++;
            }

            //solicita o fechamneto da conexão com o BD
            fecharConexaoMysql($conexao);

            if(isset($arrayDados))
                return $arrayDados;
            else
                return false;

        }

    }

    //Função para buscar um contato no BD através do id do registro
    function selectByIdContato($id)
    {
          //Abre a conexão com o BD
          $conexao = conexaoMysql();

          //script para listar o dado do BD
          $sql = "select * from tblcontatos where idcontato =".$id;
  
          //Executa o script sql no BD e guarda o retorno dos dados, se houver
          $result = mysqli_query($conexao, $sql);
  
          //Valida se o BD retornou registro
          if($result)
          {
              //mysqli_fetch_assoc - permite converter os dados em um array para manipulação no php
              //Nessa validação estamos, convertando os dados do BD em um array ($rsDados)              
              if ($rsDados = mysqli_fetch_assoc($result))
              {
                  //Cria um array com os dados do BD
                  $arrayDados = array (
                      "id"       => $rsDados['idcontato'],
                      "nome"     => $rsDados['nome'],
                      "telefone" => $rsDados['telefone'],
                      "celular"  => $rsDados['celular'],
                      "email"    => $rsDados['email'],
                      "obs"      => $rsDados['obs'],
                      "foto"     => $rsDados['foto'],
                      "idestado" => $rsDados['idestado']
                  );
              }
  
              //solicita o fechamneto da conexão com o BD
              fecharConexaoMysql($conexao);

              if(isset($arrayDados))
                return $arrayDados;
              else
                return false;
  
              
          }
    }
