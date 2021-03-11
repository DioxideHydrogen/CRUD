<?php


namespace App\Models\Database;

require dirname(__DIR__,2).'/vendor/autoload.php';

interface DBInterface {
    /**
     * Inicializa as variáveis de conexão
     * com o banco de dados.
     */
    public function __construct();

    /**
     * Conecta ao banco de dados e retorna
     * um objeto PDO de conexão.
     */
    public function connect() : \PDO;
}