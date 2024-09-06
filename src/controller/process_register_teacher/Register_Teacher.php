<?php

namespace Admin\Consulta\controller\process_register_teacher;

use Admin\Consulta\config\Config_db;
use Exception;
use PDOException;

class Register_Teacher
{
    private $name_teacher;
    private $email_teacher;
    private $senha_teacher;
    public function __construct($name_teacher, $email_teacher, $senha_teacher)
    {
        $this->setNameTeacher($name_teacher);
        $this->setEmailTeacher($email_teacher);
        $this->setSenhaTeacher($senha_teacher);

        $this->insert_teacher($this->getNameTeacher(), $this->getEmailTeacher(), $this->getSenhaTeacher());
    }

    /**
     * Get the value of name_user
     */
    public function getNameTeacher()
    {
        return $this->name_teacher;
    }

    /**
     * Set the value of name_teacher
     */
    public function setNameTeacher($name_teacher): self
    {
        $this->name_teacher = $name_teacher;

        return $this;
    }

    /**
     * Get the value of email_teacher
     */
    public function getEmailTeacher()
    {
        return $this->email_teacher;
    }

    /**
     * Set the value of email_teacher
     */
    public function setEmailTeacher($email_teacher): self
    {
        $this->email_teacher = $email_teacher;

        return $this;
    }

    /**
     * Get the value of senha_teacher
     */
    public function getSenhaTeacher()
    {
        return $this->senha_teacher;
    }

    /**
     * Set the value of senha_teacher
     */
    public function setSenhaTeacher($senha_teacher): self
    {
        $this->senha_teacher = $senha_teacher;

        return $this;
    }

    public function insert_teacher($name_teacher, $email_teacher, $senha_teacher)
    {
        $db = new Config_db();
        $pdo = $db->auth_db();

        // Verifica se o email já existe
        $check_sql = "SELECT COUNT(*) FROM professor WHERE pro_email = :pro_email";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->bindValue(':pro_email', $email_teacher);
        $check_stmt->execute();
        $email_exists = $check_stmt->fetchColumn();

        if ($email_exists) {
            // Email já existe, retorna uma mensagem de erro ou toma outra ação
            echo "O email já está registrado.";
            return false;
        }

        // Insere o novo professor
        $sql = "INSERT INTO professor (pro_email, pro_nome, pro_tag) VALUES (:pro_email, :pro_nome, :pro_tag)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':pro_email', $email_teacher);
        $stmt->bindValue(':pro_nome', $name_teacher);
        $stmt->bindValue(':pro_tag', $senha_teacher);

        try {
            $stmt->execute();
            echo "Professor registrado com sucesso.";
            ?> <script>
                window.location.href='../../pages/teacher/page_config_teacher.php?id=<?php echo $pdo->lastInsertId() ?>';
            </script> <?php
        } catch (PDOException $e) {
            print_r($e);
        }
    }
}
