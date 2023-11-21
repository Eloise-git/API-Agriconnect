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

  public function updateUserById($id,$nom, $prenom, $email, $password, $numero)
  {
    $sql = "UPDATE utilisateur SET firstName_user = :name, lastName_user = :surname, email_user = :email, phoneNumber_user = :phone, password_user= :password WHERE id_user = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      "id" => $id,
      "name" => $nom,
      "surname" => $prenom,
      "email" => $email,
      "phone" => $numero,
      "password" => $password,
    ]);

    $user = $this->getUserById($id);

    return $user;
  }

  public function deleteUserById($id)
  {
    $sql = "DELETE FROM MESSAGERIE WHERE id_user = :id OR id_user_1 = :id;
    DELETE FROM PRODUCTEUR WHERE id_user = :id;
    DELETE FROM CONTENIR WHERE id_order IN (SELECT id_order FROM COMMANDE WHERE id_user = :id);
    DELETE FROM COMMANDE WHERE id_user = :id;
    DELETE FROM UTILISATEUR WHERE id_user = :id;";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['id' => $id]);
  }
}