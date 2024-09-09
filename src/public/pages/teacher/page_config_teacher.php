<?php

require_once("../../../../vendor/autoload.php");

use Admin\Consulta\config\Config_db;
use Admin\Consulta\controller\process_inserts_infos\Infos;

session_start();

if (!isset($_SESSION['ID_pro'])) {
    echo "Acesso negado. Você precisa fazer login.";
    exit();
}

$id_pro = $_SESSION['ID_pro'];

$db = new Config_db();
$pdo = $db->auth_db();

$sql = "SELECT * FROM professor WHERE ID_pro = :ID_pro";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':ID_pro', $_SESSION['ID_pro'], PDO::PARAM_INT);
$stmt->execute();

$professor = $stmt->fetch(PDO::FETCH_ASSOC);

if ($professor) :

    //resgata todos os numeros da sala
    $sql_sala = "SELECT sala_numero FROM sala";
    $stmt_sala = $pdo->prepare($sql_sala);
    $stmt_sala->execute();

    $salas = $stmt_sala->fetchAll(PDO::FETCH_ASSOC);

    //resgata data hora 
    $sql_search = "SELECT * FROM localizacao WHERE ID_pro = :ID_pro";
    $stmt_search = $pdo->prepare($sql_search);

    $stmt_search->bindValue(':ID_pro', $id_pro);

    $stmt_search->execute();

    $searchs = $stmt_search->fetchAll(PDO::FETCH_ASSOC);

    //resgata todos os nomes do curso

    $sql_get_name_course = "SELECT curso_name FROM curso";
    $stmt_course = $pdo->prepare($sql_get_name_course);
    $stmt_course->execute();

    $courses = $stmt_course->fetchAll(PDO::FETCH_ASSOC);
    function get_number_class($pdo, $id)
    {
        $sql_sala_get_number = "SELECT sala_numero FROM sala WHERE sala_numero = :sala_numero";
        $stmt_get_number = $pdo->prepare($sql_sala_get_number);

        $stmt_get_number->bindValue(':sala_numero', $id);

        $stmt_get_number->execute();

        $number_class = $stmt_get_number->fetch(PDO::FETCH_ASSOC);

        return $number_class['sala_numero'];
    }

    function get_course_by_teacher_id($pdo, $id_pro, $index){
        $sql_course_ = "SELECT ID_curso FROM professor_por_curso WHERE ID_pro = :ID_pro";
        $stmt_course_ = $pdo->prepare($sql_course_);

        $stmt_course_->bindValue(':ID_pro', $id_pro);
        $stmt_course_->execute();

        $get_id_course = $stmt_course_->fetchAll(PDO::FETCH_ASSOC);


        $sql_get_name_course = "SELECT curso_name FROM curso WHERE ID_curso = :ID_curso";
        $stmt_get_name = $pdo->prepare($sql_get_name_course);

        $stmt_get_name->bindValue(':ID_curso', $get_id_course[$index]['ID_curso']);

        $stmt_get_name->execute();

        $get_name_course = $stmt_get_name->fetch(PDO::FETCH_ASSOC);

        return $get_name_course['curso_name'];

    }

    function get_users_by_teacher($pdo, $id_pro){
        $sql_user = "SELECT user_login FROM usuario WHERE ID_pro = :ID_pro";
        $stmt_user = $pdo->prepare($sql_user);

        $stmt_user->bindValue(':ID_pro', $id_pro);
        $stmt_user->execute();

        $get_users = $stmt_user->fetchAll(PDO::FETCH_ASSOC);
        return $get_users;
    }

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" type="text/css" href="../assets_pages/styles/style_pages.css">
    </head>

    <body>
        <header>
            <h1>Bem vindo <?php echo htmlspecialchars($professor['pro_nome']) ?></h1>
        </header>
        <main>
            <section class="first_section">
                <h3>Selecione os seus cursos, sala e horarios</h3>
                <form action="page_config_teacher.php" method="post">
                    <label for="curso">Cursos:</label>
                    <select id="curso" name="curso">
                        <?php foreach ($courses as $course): ?>
                            <option value="<?php echo $course['curso_name'] ?>"><?php echo $course['curso_name'] ?></option>
                        <?php endforeach ?>
                    </select>
                    <label for="sala">Sala:</label>
                    <select name="sala" id="sala">
                        <?php foreach ($salas as $index => $sala): ?>
                            <option value="<?php echo $sala['sala_numero'] ?>"><?php echo $sala['sala_numero'] ?></option>
                        <?php endforeach ?>
                    </select>
                    <label for="horario">Horario</label>
                    <input type="time" id="hora" name="horario">
                    <label for="horario">Data</label>
                    <input type="date" id="data" name="data">
                    <input class="button_confirm" type="submit" value="Enviar">
                </form>
            </section>
            <section class="second_section">
                <div class="base_infos">
                    <h3>Veja seus horarios</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Hora</th>
                                <th>Sala</th>
                                <th>Aulas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($searchs as $index_s => $search): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($search['loc_data']);  ?></td>
                                    <td><?php echo htmlspecialchars($search['loc_hora']);  ?></td>
                                    <td><?php echo get_number_class($pdo, $search['ID_sala']) ?></td>
                                    <td><?php echo get_course_by_teacher_id($pdo, $search['ID_pro'], $index_s) ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </section>
            <section class="users_section">
                <div class="users_base">
                    <h3>Seus Alunos</h3>
                    <ul>
                        <?php 
                        $users = get_users_by_teacher($pdo, $id_pro);
                        foreach($users as $user):
                        ?>
                        <li><?php echo $user['user_login'] ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            </section>
        </main>
    </body>

    </html>

<?php else : ?>
    <div>
        <span>ERRO</span>
    </div>
<?php endif ?>

<?php
require_once("../../../../vendor/autoload.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['curso']) && isset($_POST['sala']) && isset($_POST['horario']) && isset($_POST['data'])) {
        new Infos($_POST['curso'], $_POST['sala'], $_POST['horario'], $_POST['data'], $id_pro);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>