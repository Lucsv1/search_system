<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="assets_login/styles/style_login.css">
</head>

<body>
    <header>
        <h2>Registro do professor</h2>
    </header>
    <main>
        <section>
            <div>
                <div>
                    <form action="page_register_to_teacher.php" method="post">
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome">
                        <label for="email">Email:</label>
                        <input type="text" id="email" name="email">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha">
                        <input type="submit" value="Entrar">
                    </form>
                </div>
            </div>
        </section>
    </main>
    <script src="assets_login/scripts/script_login.js"></script>
</body>

</html>

<?php

require_once('../../../vendor/autoload.php');

use Admin\Consulta\controller\process_register_teacher\Register_Teacher;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['senha'])) {
       $register = new Register_Teacher($_POST['nome'], $_POST['email'], $_POST['senha']);
    }
}

?>