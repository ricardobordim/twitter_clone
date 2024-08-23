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
        $tweets = $tweet->getAll();
        // print_r($tweets);

        // atributo dinâmico
        $this->view->tweets = $tweets;

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
            $usuarios = $usuario->getAll();
            

        }

        $this->view->usuarios = $usuarios;
        
        // print_r($_GET);
        $this->render('quemSeguir');


    }
}
