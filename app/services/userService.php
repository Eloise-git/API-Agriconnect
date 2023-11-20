<?php

namespace App\services;

use App\models\Service;
use Exception;
use PDO;

class UserService extends Service
{
  public function __construct($db)
  {
    $this->db = $db;
  }

  public function getUserById($id)
  {
    $sql = "SELECT * FROM utilisateur WHERE id_user = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
      throw new Exception("L'utilisateur n'existe pas", 404);
    }

    return [
      "id" => $user['id_user'],
      "name" => $user['firstName_user'],
      "surname" => $user['lastName_user'],
      "email" => $user['email_user'],
      "phone" => $user['phoneNumber_user'],
      "role" => $user['role_user'],
      "createdAt" => $user['createdAt_user']
    ];
  }
}