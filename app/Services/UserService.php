<?php

namespace App\Services;

use App\Models\Service;
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
    $sql = "SELECT * FROM UTILISATEUR WHERE id_user = :id";
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
      "phoneNumber" => (int) $user['phoneNumber_user'],
      "role" => $user['role_user'],
      "createdAt" => $user['createdAt_user']
    ];
  }

  public function getAll()
  {
    $sql = "SELECT * FROM UTILISATEUR";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $all = [];
    foreach ($users as $user) {
      $item = [
        "id" => $user['id_user'],
        "name" => $user['firstName_user'],
        "surname" => $user['lastName_user'],
        "email" => $user['email_user'],
        "phoneNumber" => (int) $user['phoneNumber_user'],
        "role" => $user['role_user'],
        "createdAt" => $user['createdAt_user']
      ];

      $all[] = $item;
    }

    return $all;
  }

  public function changeAVisitorToClient($userId)
  {
    $sql = "UPDATE UTILISATEUR SET role_user = 'client' WHERE id_user = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      "id" => $userId
    ]);
   
  }

  public function updateUserById($id, $nom, $prenom, $email, $password, $numero)
  {
    $sql = "UPDATE UTILISATEUR SET firstName_user = :name, lastName_user = :surname, email_user = :email, phoneNumber_user = :phoneNumber, password_user= :password WHERE id_user = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      "id" => $id,
      "name" => $nom,
      "surname" => $prenom,
      "email" => $email,
      "phoneNumber" => $numero,
      "password" => $password,
    ]);

    $user = $this->getUserById($id);

    if (!$user) {
      throw new Exception("Erreur lors de la mise à jour de l'utilisateur : " . implode(", ", $stmt->errorInfo()));
    }

    return $user;
  }

  public function deleteUserById($id)
  {
    $sql = "DELETE FROM UTILISATEUR WHERE id_user = :id;";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['id' => $id]);
  }
}
