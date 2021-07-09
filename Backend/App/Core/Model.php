<?php

namespace App\Core;

class Model
{
    //aplicamos o padrão de projeto singleton
    private static $conexao;

    public static function getConexao()
    {
        //se a conexão não estiver criada, criamos uma
        if(!isset(self::$conexao)){
            
            //self é usado para pegar um atributo estático desta classe
            self::$conexao = new \PDO("mysql:host=localhost;port=3306;dbname=fastParking;", "root", "Lilicalegal1");
        }

        //retornamos a conexão
        return self::$conexao;
    }
}
