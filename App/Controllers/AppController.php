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
}
