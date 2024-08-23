<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AuthController extends Action
{

    public function autenticar()
    {

        // echo ("Chegamos aqui");
        // print_r($_POST);
        $usuario = Container::getModel('Usuario');
        $usuario->__set('email', $_POST['email']);
        $usuario->__set('senha', md5($_POST['senha']));

        // print_r($usuario);

        $retorno =  $usuario->autenticar();

        // print_r($usuario);

        if ($usuario->__get('id') !='' && $usuario->__get('nome') !=''){
            // echo ("autenticao");
            session_start();
            $_SESSION['id'] = $usuario->__get('id');
            $_SESSION['nome'] = $usuario->__get('nome');
            header('Location:/timeline');

        }
        else{
            header('Location:/?login=erro');
        }
    }
    public function sair(){
        session_start();
        session_destroy();
        header('Location:/');
    }
    
}
