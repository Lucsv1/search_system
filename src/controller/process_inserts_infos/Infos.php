<?php

namespace Admin\Consulta\controller\process_inserts_infos;

use Admin\Consulta\config\Config_db;
use PDO;

class Infos
{
    private $curso;

    private $sala;

    private $horario;

    private $data;

    private $id_pro;



    public function __construct($curso, $sala, $horario, $data, $id_pro)
    {

        $this->setCurso($curso);
        $this->setSala($sala);
        $this->setHorario($horario);
        $this->setIdPro($id_pro);
        $this->setData($data);

        $get_id_course = $this->get_course($this->getCurso());
        $get_id_class = $this->get_class($this->getSala());
        $this->insert_search($this->getHorario(), $this->getData(), $this->getIdPro(), $get_id_class);
        $this->insert_teacher_course($get_id_course, $this->getIdPro());
    }

    /**
     * Get the value of curso
     */
    public function getCurso()
    {
        return $this->curso;
    }

    /**
     * Set the value of curso
     */
    public function setCurso($curso): self
    {
        $this->curso = $curso;

        return $this;
    }

    /**
     * Get the value of sala
     */
    public function getSala()
    {
        return $this->sala;
    }

    /**
     * Set the value of sala
     */
    public function setSala($sala): self
    {
        $this->sala = $sala;

        return $this;
    }

    /**
     * Get the value of horario
     */
    public function getHorario()
    {
        return $this->horario;
    }

    /**
     * Set the value of horario
     */
    public function setHorario($horario): self
    {
        $this->horario = $horario;

        return $this;
    }

    /**
     * Get the value of data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the value of data
     */
    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get the value of id_pro
     */
    public function getIdPro()
    {
        return $this->id_pro;
    }

    /**
     * Set the value of id_pro
     */
    public function setIdPro($id_pro): self
    {
        $this->id_pro = $id_pro;

        return $this;
    }

    public function get_course($curso)
    {

        $db = new Config_db();
        $pdo = $db->auth_db();

        $sql_course = "SELECT ID_curso FROM curso WHERE curso_name = :curso_name";
        $stmt = $pdo->prepare($sql_course);

        $stmt->bindValue(':curso_name', $curso);

        $stmt->execute();

        $get_id = $stmt->fetch(PDO::FETCH_ASSOC);

        return $get_id['ID_curso'] ;
    }

    public function get_class($sala)
    {
        
        $sala_int = intval($sala);

        $db = new Config_db();
        $pdo = $db->auth_db();

        $sql_class = "SELECT ID_sala FROM sala WHERE sala_numero = :sala_numero";
        $stmt = $pdo->prepare($sql_class);

        $stmt->bindValue(':sala_numero', $sala_int);

        $stmt->execute();

        $get_id_class = $stmt->fetch(PDO::FETCH_ASSOC);

        return $get_id_class['ID_sala'];
    }

    public function insert_search($horario, $data, $id_pro, $id_sala)
    {
        $db = new Config_db();
        $pdo = $db->auth_db();

        $sql_search = "INSERT INTO localizacao (loc_data, loc_hora, ID_pro, ID_sala) VALUES (:loc_data, :loc_hora, :ID_pro, :ID_sala)";

        $stmt = $pdo->prepare($sql_search);

        $data_format = date('Y-m-d', strtotime($data));

        $stmt->bindValue(':loc_data', $data_format);
        $stmt->bindValue(':loc_hora', $horario);
        $stmt->bindValue(':ID_pro', $id_pro); // Corrigido aqui
        $stmt->bindValue(':ID_sala', $id_sala); // Corrigido aqui

        $stmt->execute();
    }


    public function insert_teacher_course($id_course, $id_pro)
    {
        $db = new Config_db();
        $pdo = $db->auth_db();

        $sql_teacher_course = "INSERT INTO professor_por_curso (ID_curso, ID_pro) VALUES (:ID_curso, :ID_pro)";

        $stmt = $pdo->prepare($sql_teacher_course);

        $stmt->bindValue(':ID_curso', $id_course);
        $stmt->bindValue(':ID_pro', $id_pro);

        $stmt->execute();
    }
}
