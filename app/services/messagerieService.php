<?php

namespace App\services;

use App\models\Service;
use Exception;
use PDO;

class MessagerieService extends Service
{
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllMessages()
    {
        $sql = "SELECT * FROM messagerie;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $message = $stmt->fetch(PDO::FETCH_ASSOC);
        return $message;
    }

    public function getAMessageById($id)
    {
        $sql = "SELECT * FROM messagerie WHERE id_message = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $message = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$message) {
        throw new Exception("Le message n'existe pas", 404);
        }

        return $message;
    }

    public function postMessage($id, $date, $content, $id_user, $id_user1)
    {


    $sql = "INSERT INTO messagerie (id_message, date_message, content_message, id_user, id_user1) 
        VALUES (':id_message',':date_message', ':content_message',':id_user',':id_user1';";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
    'id_message' => $id,
    'date_message' => $date,
    'content_message' => $content,
    'id_user' => $id_user,
    'id_user1' => $id_user1
    ]);
    $message = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($message) {
    throw new Exception("Le message existe déjà", 409);
    }
    return $message;
    }


    public function deleteUserById($id)
    {
        $sql = "DELETE FROM messagerie WHERE id_message = :id;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
}