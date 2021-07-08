<?php

namespace App\Core;

class Controller
{
    //recebemos o model a ser instanciado
    //retornamos a instancia ja pronta
    
    public function Model($model)
    {
        require_once("../App/Model/" . $model . ".php");
        return new $model;
    }


    protected function getRequestBody()
    {
        //pegando o corpo da requisição, retona uma string
        $json = file_get_contents("php://input");
       
        //convertendo a string em objeto
        $obj = json_decode($json);

        return $obj;
    }
}
