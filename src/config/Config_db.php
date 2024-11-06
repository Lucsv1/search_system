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


    /**
     * Estabelece uma conexão com o banco de dados e cria as tabelas necessárias.
     *
     * Esta função configura uma nova instância de PDO para interagir com o banco de dados,
     * define o modo de erro para exceções e chama uma função para criar as tabelas no banco de dados.
     *
     * @return PDO Retorna a instância de PDO configurada.
     * @throws PDOException Se houver um erro ao tentar se conectar ao banco de dados ou configurar o PDO.
     */
    public function auth_db()
    {
        // Obtém as credenciais e informações do banco de dados.
        $dns = $this->getDsn();
        $username = $this->getUser();
        $password = $this->getPassword();

        // Cria uma nova instância de PDO para se conectar ao banco de dados.
        $pdo = new PDO($dns, $username);

        // Define o modo de erro do PDO para lançar exceções em caso de erro.
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Chama a função para criar as tabelas necessárias no banco de dados.
        $this->create_tables($pdo);

        // Retorna a instância de PDO configurada.
        return $pdo;
    }


    /** * Cria as tabelas necessárias no banco de dados. * * Esta função verifica se as tabelas 'usuario', 'professor', 'localizacao', 'sala', 'curso' e 'professor_por_curso' * já existem. Se alguma tabela não existir, ela a cria. * * @param PDO $pdo Instância de PDO para executar as consultas no banco de dados. */



    private function create_tables($pdo)
    {
        // Consultas SQL para verificar a existência das tabelas.
        $sql_user = "SELECT 1 FROM usuario LIMIT 1";
        $sql_professor = "SELECT 1 FROM professor LIMIT 1";
        $sql_localizacao = "SELECT 1 FROM localizacao LIMIT 1";
        $sql_sala = "SELECT 1 FROM sala LIMIT 1";
        $sql_curso = "SELECT 1 FROM curso LIMIT 1";
        $sql_professor_por_curso = "SELECT 1 FROM professor_por_curso LIMIT 1";

        try {
            // Verifica a existencia da tabela
            $pdo->query($sql_professor);
            $pdo->query($sql_user);
            $pdo->query($sql_localizacao);
            $pdo->query($sql_sala);
            $pdo->query($sql_curso);
            $pdo->query($sql_professor_por_curso);
        } catch (PDOException $e) {

            // Se alguma tabela não existir, cria-as
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
                `user_permission` TEXT NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
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

            // Criação da conta do professor 
            $id_proa = $this->create_teachers($pdo, 'professora@gmail.com', 'Professor Luiz', 'PF123A');
            $id_prob = $this->create_teachers($pdo, 'professorb@gmail.com', 'Professor Inacio', 'PF123B');
            $id_proc = $this->create_teachers($pdo, 'professorc@gmail.com', 'Professor Ricardo', 'PF123C');
            $id_prod = $this->create_teachers($pdo, 'professord@gmail.com', 'Professor Geraldo', 'PF123D');

            $pdo->exec($sql_user);

            // Criação da conta dos alunos - professor a
            $this->create_users($pdo, 'aluno1', 'RM1231', 'ATIVO', $id_proa);
            $this->create_users($pdo, 'aluno2', 'RM1232', 'ATIVO', $id_proa);
            $this->create_users($pdo, 'aluno3', 'RM1233', 'ATIVO', $id_proa);

            // Criação da conta dos alunos - professor b
            $this->create_users($pdo, 'aluno11', 'RM12311', 'ATIVO', $id_prob);
            $this->create_users($pdo, 'aluno22', 'RM12322', 'ATIVO', $id_prob);
            $this->create_users($pdo, 'aluno33', 'RM12333', 'ATIVO', $id_prob);

            // Criação da conta dos alunos - professor c
            $this->create_users($pdo, 'aluno12', 'RM12312', 'ATIVO', $id_proc);
            $this->create_users($pdo, 'aluno23', 'RM12323', 'ATIVO', $id_proc);
            $this->create_users($pdo, 'aluno34', 'RM12334', 'ATIVO', $id_proc);

            // Criação da conta dos alunos - professor d
            $this->create_users($pdo, 'aluno13', 'RM12313', 'ATIVO', $id_prod);
            $this->create_users($pdo, 'aluno24', 'RM12324', 'ATIVO', $id_prod);
            $this->create_users($pdo, 'aluno35', 'RM12335', 'ATIVO', $id_prod);

            $pdo->exec($sql_sala);

            // Criação da conta dos alunos -
            $this->create_class($pdo, 1);
            $this->create_class($pdo, 2);
            $this->create_class($pdo, 3);
            $this->create_class($pdo, 4);
            $this->create_class($pdo, 5);
            $this->create_class($pdo, 6);

            $pdo->exec($sql_localizacao);
            $pdo->exec($sql_curso);

            // Criação dos cursos
            $this->create_courses($pdo, 'redes');
            $this->create_courses($pdo, 'Devops');
            $this->create_courses($pdo, 'Front-end');
            $this->create_courses($pdo, 'Backend');
            $this->create_courses($pdo, 'Python');

            $pdo->exec($sql_professor_por_curso);
        }
    }


    /**
     * Insere um novo professor na tabela `professor`.
     *
     * @param PDO $pdo Instância de PDO para executar as consultas no banco de dados.
     * @param string $pro_email E-mail do professor.
     * @param string $pro_nome Nome do professor.
     * @param string $pro_tag Tags ou observações relacionadas ao professor.
     * @return int Retorna o ID do último professor inserido.
     * @throws PDOException Se houver um erro ao tentar inserir o professor.
     */
    private function create_teachers($pdo, $pro_email, $pro_nome, $pro_tag)
    {
        $sql_create = "INSERT INTO professor (pro_email, pro_nome, pro_tag) VALUES (:pro_email, :pro_nome, :pro_tag)";
        $stmt = $pdo->prepare($sql_create);

        $stmt->bindValue(":pro_email", $pro_email);
        $stmt->bindValue(":pro_nome", $pro_nome);
        $stmt->bindValue(":pro_tag", $pro_tag);

        $stmt->execute();

        return $pdo->lastInsertId();
    }

    /**
     * Insere um novo usuário na tabela `usuario`.
     *
     * @param PDO $pdo Instância de PDO para executar as consultas no banco de dados.
     * @param string $user_login Nome de login do usuário.
     * @param string $user_senha Senha do usuário.
     * @param string $user_permission Permissões ou papéis do usuário.
     * @param int $ID_pro Identificador do professor associado ao usuário.
     * @throws PDOException Se houver um erro ao tentar inserir o usuário.
     */
    private function create_users($pdo, $user_login, $user_senha, $user_permission, $ID_pro)
    {
        $sql_create = "INSERT INTO usuario (user_login, user_senha, user_perission, ID_pro) VALUES (:user_login, :user_senha, :user_perission, :ID_pro)";
        $stmt = $pdo->prepare($sql_create);

        $stmt->bindValue(":user_login", $user_login);
        $stmt->bindValue(":user_senha", $user_senha);
        $stmt->bindValue(":user_perission", $user_permission);
        $stmt->bindValue(":ID_pro", $ID_pro);

        $stmt->execute();
    }

    /**
     * Insere uma nova sala na tabela `sala`.
     *
     * @param PDO $pdo Instância de PDO para executar as consultas no banco de dados.
     * @param int $number_class Número da sala.
     * @throws PDOException Se houver um erro ao tentar inserir a sala.
     */
    private function create_class($pdo, $number_class)
    {
        $sql_create = "INSERT INTO sala (sala_numero) VALUES (:sala_numero)";
        $stmt = $pdo->prepare($sql_create);

        $stmt->bindValue(":sala_numero", $number_class);

        $stmt->execute();
    }

    /**
     * Insere um novo curso na tabela `curso`.
     *
     * @param PDO $pdo Instância de PDO para executar as consultas no banco de dados.
     * @param string $name_course Nome do curso.
     * @throws PDOException Se houver um erro ao tentar inserir o curso.
     */
    private function create_courses($pdo, $name_course)
    {
        $sql_course = "INSERT INTO curso (curso_name) VALUES (:curso_name)";
        $stmt = $pdo->prepare($sql_course);

        $stmt->bindValue(':curso_name', $name_course);

        $stmt->execute();
    }

}
