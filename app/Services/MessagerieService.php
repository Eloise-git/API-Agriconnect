<?php

namespace App\Services;

use App\Models\Service;
use Exception;
use PDO;

class MessagerieService extends Service
{
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllMessages($userId)
    {
        $sql = "SELECT * FROM MESSAGERIE WHERE id_user = :id OR id_user_1 = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $userId]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $all = [];
        foreach ($messages as $message) {
            $item = [
                "id" => $message['id_message'],
                "date" => $message['date_message'],
                "content" => $message['content_message'],
                "sender" => $message['id_user'] ?? null,
                "receiver" => $message['id_user_1']
            ];

            $all[] = $item;
        }
        return $all;
    }

    public function getAMessageById($id)
    {
        $sql = "SELECT * FROM MESSAGERIE WHERE id_message = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $message = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$message) {
            throw new Exception("Le message n'existe pas", 404);
        }

        return [
            "id" => $message['id_message'],
            "date" => $message['date_message'],
            "content" => $message['content_message'],
            "sender" => $message['id_user'] ?? null,
            "receiver" => $message['id_user_1']
        ];
    }

    public function getAMessageByUserId($userId)
    {
        $sql = "SELECT * FROM MESSAGERIE WHERE id_user = :id OR id_user_1 = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $userId]);
        $message = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$message) {
            throw new Exception("Le message n'existe pas", 404);
        }

        return [
            "id" => $message['id_message'],
            "date" => $message['date_message'],
            "content" => $message['content_message'],
            "sender" => $message['id_user'] ?? null,
            "receiver" => $message['id_user_1']
        ];
    }

    public function postMessage($id, $date, $content, $userId, $id_user1)
    {
        $sql = "INSERT INTO MESSAGERIE (id_message, date_message, content_message, id_user, id_user_1) VALUES (:id_message, :date_message, :content_message, :id_user, :id_user1)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_message' => $id,
            'date_message' => $date,
            'content_message' => $content,
            'id_user' => $userId,
            'id_user1' => $id_user1
        ]);

        return $this->getAMessageById($id);
    }


    public function deleteMessageById($id)
    {
        $sql = "DELETE FROM MESSAGERIE WHERE id_message = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $message = $stmt->fetch(PDO::FETCH_ASSOC);

        return $message;
    }
}
