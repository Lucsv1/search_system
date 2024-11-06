<?php

namespace Admin\Consulta\controller\process_login_user;

use Admin\Consulta\config\Config_db;
use PDO;

/**
 * Class Login_User
 * 
 * Description: Funções da tela de login user, verifica email e senha.
 */
class Login_User
{

    private $email_user;
    private $senha_user;

    public function __construct($email_user, $senha_user)
    {
        $this->setEmailUser($email_user);
        $this->setSenhaUser($senha_user);

        $this->check_user_exists($this->getEmailUser(), $this->getSenhaUser());
    }

    /**
     * Get the value of name_user
     */


    /**
     * Get the value of email_user
     */
    public function getEmailUser()
    {
        return $this->email_user;
    }

    /**
     * Set the value of email_user
     */
    public function setEmailUser($email_user): self
    {
        $this->email_user = $email_user;

        return $this;
    }

    /**
     * Get the value of senha_user
     */
    public function getSenhaUser()
    {
        return $this->senha_user;
    }

    /**
     * Set the value of senha_user
     */
    public function setSenhaUser($senha_user): self
    {
        $this->senha_user = $senha_user;

        return $this;
    }

    /**
     * Verifica se um usuário com o login e senha fornecidos já existe.
     *
     * Esta função verifica se um usuário com o login e senha fornecidos já está registrado no banco de dados.
     * Se o usuário existir, inicia uma sessão e redireciona para a página de busca de professores.
     * Caso contrário, exibe uma mensagem de erro informando que o usuário não foi encontrado.
     *
     * @param string $user_login Nome de login do usuário.
     * @param string $user_senha Senha do usuário.
     * @throws PDOException Se houver um erro ao executar a consulta.
     */
    public function check_user_exists($user_login, $user_senha)
    {
        $db = new Config_db();
        $pdo = $db->auth_db();

        // Verifica se o email e a senha já existem.
        $check_sql = "SELECT ID_user FROM usuario WHERE user_login = :user_login AND user_senha = :user_senha";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->bindValue(':user_login', $user_login);
        $check_stmt->bindValue(':user_senha', $user_senha);
        $check_stmt->execute();

        $result = $check_stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            session_start();
            $_SESSION['ID_user'] = $result['ID_user'];

            // Redireciona para a página de busca de professores.
            ?>
            <script>
                window.location.href = '../../public/pages/user/page_search_teacher.php';
            </script>
            <?php
        } else {
            // Usuário não encontrado.
            echo "Usuário não encontrado.";
        }
    }

}
