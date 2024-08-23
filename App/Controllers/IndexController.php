<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {

		// Criando uma variável e fazendo um teste ternário
		$this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
		$this->render('index');
	}
	public function inscreverse(){

		$this->view->usuario = array(
			'nome' => '',
			'email' =>'',
			'senha' => ''
		);
		$this->view->erroCadastro = false;		
		$this->render('inscreverse');
	}

	public function registrar()
	{
		
		// receber dados do formulário
		// print_r($_POST);

		$usuario = Container::getModel('Usuario');
		$usuario->__set('nome', $_POST['nome']);
		$usuario->__set('email', $_POST['email']);
		$usuario->__set('senha', md5($_POST['senha']));

		// print_r($usuario);

		if ($usuario->validarCadastro() && count($usuario->getUsuarioPorEmail()) == 0){
			$usuario->salvar();
			$this->render('cadastro');
		} else {

			// Recuperando os valores para não precisar digitar novamente
			$this->view->usuario = array(
				'nome' => $_POST['nome'],
				'email' => $_POST['email'],
				'senha' => $_POST['senha']
			);

			// Atribuindo dinamicamente um atributo
			$this->view->erroCadastro = true;

			$this->render('inscreverse');
		}

	}
	

}


?>
