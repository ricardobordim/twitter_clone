<?php

namespace App\Models;

use MF\Model\Model;

class Tweet extends Model
{

    private $id;
    private $id_usuario;
    private $tweet;
    private $data;

    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }


    public function salvar()
    {
        $query = "insert into tweets(id_usuario, tweet) values (:id_usuario, :tweet) ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->bindValue(':tweet', $this->__get('tweet'));
        $stmt->execute();

        //Retornando o próprio objeto
        return $this;
    }

    // Teste simples somente para validar o local onde deve ser inserido esse processo.
    public function validarCadastro()
    {
        $valido = true;

        if (strlen($this->__get('nome')) < 3) {
            $valido = false;
        }

        if (strlen($this->__get('email')) < 3) {
            $valido = false;
        }

        if (strlen($this->__get('senha')) < 3) {
            $valido = false;
        }

        return $valido;
    }

    public function getAll()
    {
        $query = "select t.id, t.id_usuario, u.nome,t.tweet, DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data 
                  from tweets as t left join usuarios as u on (t.id_usuario = u.id)  
                  where t.id_usuario = :id_usuario 
                  or t.id_usuario in (select id_usuario_seguindo from usuarios_seguidores where id_usuario = :id_usuario)
                  order by t.data desc";
        $stmt =  $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));

        $stmt->execute();

        // retorna como um array associativo
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPorPagina($limit, $offset)
    {
        $query = "select 
                    t.id, 
                    t.id_usuario, 
                    u.nome,t.tweet, DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data 
                  from 
                    tweets as t left join usuarios as u on (t.id_usuario = u.id)  
                  where 
                    t.id_usuario = :id_usuario 
                    or t.id_usuario in (select id_usuario_seguindo from usuarios_seguidores where id_usuario = :id_usuario)
                  order by 
                    t.data desc
                  limit 
                    $limit
                  offset
                    $offset";
        $stmt =  $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));

        $stmt->execute();

        // retorna como um array associativo
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function autenticar()
    {
        $query = "select id,nome,email from usuarios where email = :email and senha = :senha";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':senha', $this->__get('senha'));
        $stmt->execute();

        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($usuario['id'] != '' && $usuario['nome'] != '') {
            $this->__set('id', $usuario['id']);
            $this->__set('nome', $usuario['nome']);
        }

        return $this;
    }

    public function remover(){
        $query = "delete from tweets where id = :id_tweet";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_tweet', $this->__get('id_tweet'));
        $stmt->execute();

        return true;
    }
}
