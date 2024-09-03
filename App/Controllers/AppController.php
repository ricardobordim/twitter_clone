<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action
{

    public function timeline()
    {
        // Protegendo a rota
        // session_start();

        // echo ("Chegamos aqui");
        // print_r($_SESSION);

        $this->validaAutenticacao();

        // Recuperar os tweets

        $tweet = Container::getModel('Tweet');

        $tweet->__set('id_usuario',$_SESSION['id']);

        // variáveis de paginação
        $total_registros_pagina = 10;
        $deslocamento = 0;
        
        // Se vier a variável pela super global $_GET, atribui a pagina senão é a primeira
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

        $deslocamento = ($pagina-1) * $total_registros_pagina;

        // echo ('Deslocamento : ' . $deslocamento . ' pagina:' . $pagina . ' total: ' . $total_registros_pagina);

         $tweets = $tweet->getPorPagina($total_registros_pagina, $deslocamento);

         $total_tweets = $tweet->getTotalRegistros();
        
         //  Criando a variavel dinamicamente
         $this->view->total_de_paginas = ceil($total_tweets['total']/$total_registros_pagina);
         $this->view->pagina_ativa = $pagina;

        //  print_r($total_paginas);


        // atributo dinâmico
        $this->view->tweets = $tweets;





        $usuario = Container::getModel('Usuario');
        $usuario->__set('id',$_SESSION['id']);
        
        $this->view->usuario = $usuario->getTotal();

        // print_r($this->view->usuario);
         $this->render('timeline');

    }
    public function tweet(){

        // Protegendo a rota

        // session_start();

        $this->validaAutenticacao();

        $tweet = Container::getModel('Tweet');
        $tweet->__set('tweet',$_POST['tweet']);
        $tweet->__set('id_usuario', $_SESSION['id']);

        $tweet->salvar();

        header(('Location:/timeline'));
        // print_r($_POST);
    }

    public function validaAutenticacao(){

        session_start();

        if (!isset($_SESSION['id']) || $_SESSION['id'] == '' |  !isset($_SESSION['nome']) ||  $_SESSION['id']== '') {
            header('Location:/?login=erro');
        } 
    }

    public function quemSeguir(){
        $this->validaAutenticacao();
        // echo 'oi';

        $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

        $usuarios= array();

        if ($pesquisarPor !=''){
            // retorna o objeto com a conexão estabelecida
            $usuario = Container::getModel('Usuario');
            $usuario->__set('nome', $pesquisarPor);
            $usuario->__set('id',$_SESSION['id']);
            $usuarios = $usuario->getAll();
            

        }

        $this->view->usuarios = $usuarios;

        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_SESSION['id']);
        $this->view->info_usuario = $usuario->getTotal();
        
        // print_r($_GET);
        $this->render('quemSeguir');


    }

    public function acao(){
        $this->validaAutenticacao();

        // print_r($_GET);
        $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
        $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

        // usando a classe usuario por facilidade nao por ser o ideal
        $usuario = Container::getModel('Usuario');

        // Pegando o id do usuario da sessao
        $usuario->__set('id',$_SESSION['id']);

        if($acao=='seguir'){
            $usuario->seguirUsuario($id_usuario_seguindo);

        }
        elseif ($acao == 'deixar_de_seguir') {
            $usuario->deixarSeguirUsuario($id_usuario_seguindo);
            
        }
        header("Location:/quem_seguir");

    }

    public function remover_tweet(){
        $this->validaAutenticacao();
        $tweet = Container::getModel('Tweet');
        $tweet->__set('id_tweet', $_GET['id_tweet']);
        $tweet->remover();

        header(('Location:/timeline'));

    }
}
