<?php

namespace Admin\Consulta\config;

use PDO;
use PDOException;

class Config_db
{

    private $dsn;

    private $user;

    private $password;

    function __construct()
    {
        $this->setDsn('mysql:host=127.0.0.1;dbname=bancoconsulta');
        $this->setUser('root');
        $this->setPassword('');
    }

    /**
     * Get the value of dsn
     */
    public function getDsn()
    {
        return $this->dsn;
    }

    /**
     * Set the value of dsn
     */
    public function setDsn($dsn): self
    {
        $this->dsn = $dsn;

        return $this;
    }

    /**
     * Get the value of user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     */
    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     */
    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }


    public function auth_db()
    {

        $dns = $this->getDsn();
        $username = $this->getUser();
        $password = $this->getPassword();

        $pdo  = new PDO($dns, $username);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //VERIFICAÇÃO DE EXISTENCIA DE TABELA

        $sql_user =  "SELECT 1 FROM usuario LIMIT 1";
        $sql_professor =  "SELECT 1 FROM professor LIMIT 1";
        $sql_localizacao =  "SELECT 1 FROM localizacao LIMIT 1";
        $sql_sala =  "SELECT 1 FROM sala LIMIT 1";
        $sql_curso =  "SELECT 1 FROM curso LIMIT 1";
        $sql_professor_por_curso =  "SELECT 1 FROM professor_por_curso LIMIT 1";

        try {
            $pdo->query($sql_professor);
            $pdo->query($sql_user);
            $pdo->query($sql_localizacao);
            $pdo->query($sql_sala);
            $pdo->query($sql_curso);
            $pdo->query($sql_professor_por_curso);
        } catch (PDOException $e) {

            $sql_professor = "CREATE TABLE `professor` (
                `ID_pro` INT(11) NOT NULL AUTO_INCREMENT,
                `pro_email` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                `pro_nome` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                `pro_tag` TEXT NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                PRIMARY KEY (`ID_pro`)
            );";

            $sql_user = "CREATE TABLE `usuario` (
                `ID_user` INT(11) NOT NULL AUTO_INCREMENT,
                `user_login` VARCHAR(355) NULL DEFAULT NULL,
                `user_senha` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                `user_perission` TEXT NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                `ID_pro` INT(11) NOT NULL,
                PRIMARY KEY (`ID_user`),
                FOREIGN KEY (`ID_pro`) REFERENCES `professor`(`ID_pro`) 
            );";

            $sql_localizacao = "CREATE TABLE `localizacao` (
                `loc_data` DATE DEFAULT NULL,
                `loc_hora` TIME DEFAULT NULL,
                `ID_pro` INT(11) NOT NULL,
                `ID_sala` INT(11) NOT NULL,
                FOREIGN KEY (`ID_sala`) REFERENCES `sala`(`ID_sala`),
                FOREIGN KEY (`ID_pro`) REFERENCES `professor`(`ID_pro`) 
            );";

            $sql_sala = "CREATE TABLE `sala` (
                `ID_sala` INT(11) NOT NULL AUTO_INCREMENT,
                `sala_numero` INT NULL DEFAULT NULL,
                PRIMARY KEY (`ID_sala`)
            );";

            $sql_curso = "CREATE TABLE `curso` (
                `ID_curso` INT(11) NOT NULL AUTO_INCREMENT,
                `curso_name` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                PRIMARY KEY (`ID_curso`)
            );";

            $sql_professor_por_curso = "CREATE TABLE `professor_por_curso` (
                `ID_curso` INT(11) NOT NULL,
                `ID_pro` INT(11) NOT NULL,
                FOREIGN KEY (`ID_curso`) REFERENCES `curso`(`ID_curso`),
                FOREIGN KEY (`ID_pro`) REFERENCES `professor`(`ID_pro`)
            );";

            $pdo->exec($sql_professor);
            $pdo->exec($sql_user);
            $pdo->exec($sql_sala);
            $pdo->exec($sql_localizacao);
            $pdo->exec($sql_curso);
            $pdo->exec($sql_professor_por_curso);
        }

        return $pdo;
    }
}
