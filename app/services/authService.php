<?php

namespace App\services;

use App\models\Service;
use Exception;
use PDO;

class AuthService extends Service
{
  public function __construct($db)
  {
    $this->db = $db;
  }

  public function login($email, $password)
  {
    $sql = "SELECT * FROM utilisateur WHERE email_user = :email";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['email' => $email]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$users) {
      throw new Exception("L'utilisateur n'existe pas", 404);
    }
    $user = $users[0];

    if ($password != $user['password_user']) {
      throw new Exception("Le mot de passe est incorrect", 401);
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

  public function register($nom, $prenom, $email, $password, $numero,$createdAt, $role)
  {
    $sql = "SELECT * FROM utilisateur WHERE email_user = :email";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
      throw new Exception("L'utilisateur existe déjà", 409);
    }

    $sql = "INSERT INTO utilisateur (id_user, firstName_user, lastName_user, email_user, password_user, phoneNumber_user,createdAt_user, role_user) VALUES (:id, :nom, :prenom, :email, :password, :numero,:createdAt, :role)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      'id' => uniqid(),
      'nom' => $nom,
      'prenom' => $prenom,
      'email' => $email,
      'password' => $password,
      'numero' => $numero,
      'createdAt' => $createdAt,
      'role' => $role
      
    ]);
    
    $sql = "SELECT * FROM utilisateur WHERE email_user = :email";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
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