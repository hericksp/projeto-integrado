<?php

namespace App\Core;

class Model
{
    //Aplicamos o padrão de projeto singleton
    private static $conexao;

    public static function getConexao()
    {
        //Se a conexão não estiver criada, criamos uma
        if(!isset(self::$conexao)){
            
            //O self é usado para pegar um atributo estático desta classe
            self::$conexao = new \PDO("mysql:host=localhost;port=3306;dbname=fastParking;", "root", "bcd127");
        }

        //retornamos a conexão
        return self::$conexao;
    }
}
