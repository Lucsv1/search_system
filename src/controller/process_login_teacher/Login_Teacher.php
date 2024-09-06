<?php

namespace Admin\Consulta\controller\process_login_teacher;

use Admin\Consulta\config\Config_db;

class Login_Teacher
{

    private $email_teacher;
    private $senha_teacher;

    public function __construct($email_teacher, $senha_teacher)
    {
        $this->setEmailTeacher($email_teacher);
        $this->setSenhaTeacher($senha_teacher);

        $this->check_teacher_exists($this->getEmailTeacher(), $this->getSenhaTeacher());
    }

    /**
     * Get the value of name_teacher
     */


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

    public function check_teacher_exists($email_teacher, $senha_teacher)
    {
        $db = new Config_db();
        $pdo = $db->auth_db();

        // Verifica se o email e a senha já existem
        $check_sql = "SELECT COUNT(*) FROM professor WHERE pro_email = :pro_email AND pro_tag = :pro_tag";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->bindValue(':pro_email', $email_teacher);
        $check_stmt->bindValue(':pro_tag', $senha_teacher);
        $check_stmt->execute();
        $user_exists = $check_stmt->fetchColumn();

        if ($user_exists) {
            ?> <script>
            window.location.href='../../public/pages/teacher/page_config_teacher.php';
            </script> <?php
        } else {
            // Usuário não encontrado
            echo "Usuário não encontrado.";
            
        }
    }
}
