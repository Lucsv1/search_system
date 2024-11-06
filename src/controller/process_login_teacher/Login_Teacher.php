<?php

namespace Admin\Consulta\controller\process_login_teacher;

use Admin\Consulta\config\Config_db;
use PDO;

/**
 * Class LoginTeacher
 * 
 * Description: Funções da tela de login do professor, verifica email e senha.
 * 
 */

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

    /**
     * Verifica se um professor com o e-mail e senha fornecidos já existe.
     *
     * Esta função verifica se um professor com o e-mail e senha fornecidos já está registrado no banco de dados.
     * Se o professor existir, inicia uma sessão e redireciona para a página de configuração do professor.
     * Caso contrário, exibe uma mensagem de erro informando que o usuário não foi encontrado.
     *
     * @param string $email_teacher E-mail do professor.
     * @param string $senha_teacher Senha do professor.
     * @throws PDOException Se houver um erro ao executar a consulta.
     */
    public function check_teacher_exists($email_teacher, $senha_teacher)
    {
        $db = new Config_db();
        $pdo = $db->auth_db();

        // Verifica se o e-mail e a senha já existem.
        $check_sql = "SELECT ID_pro FROM professor WHERE pro_email = :pro_email AND pro_tag = :pro_tag";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->bindValue(':pro_email', $email_teacher);
        $check_stmt->bindValue(':pro_tag', $senha_teacher);
        $check_stmt->execute();

        $result = $check_stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            session_start();
            $_SESSION['ID_pro'] = $result['ID_pro'];

            // Redireciona para a página de configuração do professor.
            ?>
            <script>
                window.location.href = '../../public/pages/teacher/page_config_teacher.php';
            </script>
            <?php
        } else {
            // Usuário não encontrado.
            echo "Usuário não encontrado.";
        }
    }

}
