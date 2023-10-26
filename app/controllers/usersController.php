<?php
namespace App\controllers;

use App\models\Database;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use function App\lib\send;

require_once __DIR__ . '/../lib/utils.php';


class UsersController
{
  private $container;

  private $db;

  public function __construct($container)
  {
    $this->container = $container;
    $this->db = new Database();
  }
  //Permet d'obtenir la liste des utilisateurs
  public function home(Request $request, Response $response, array $args)
  {
    $users = $this->db->query('SELECT * FROM utilisateur');
    $response->getBody()->write(json_encode($users));
    return $response;
  }

  // Permet de se connecter à l'application
  public function login(Request $request, Response $response, array $args)
  {
    try {
      $data = $request->getParsedBody();

      if (!isset($data['email']) || !isset($data['password'])) {
        return send($response, 'Tous les champs sont obligatoires', true, 400);
      }
      $email = $data['email'];
      $password = hash('sha256', $data['password']);

      $userlogin = $this->db->query("SELECT id_user as id, role_user as role, email_user as email FROM utilisateur WHERE email_user = '$email' AND password_user = '$password'");

      if (!$userlogin) {
        return send($response, 'Email ou mot de passe incorrect', true, 400);
      } else {
        return send($response, $userlogin, false, 200);
      }
    } catch (Exception $e) {
      return send($response, $e->getMessage(), true, 500);
    }
  }



  //Permet de s'inscrire à l'application
  public function register(Request $request, Response $response, array $args)
  {
    try {
    $data = $request->getParsedBody();
    // Vérifier que tous les champs sont renseignés
    if (!isset($data['nom']) || !isset($data['prenom']) || !isset($data['email']) || !isset($data['password']) || !isset($data['numero']) || !isset($data['role'])) {
      return send($response, 'Tous les champs sont obligatoires', true, 400);
    }
    $email = $data['email'];
    $user = $this->db->query("SELECT * FROM utilisateur WHERE email_user = '$email'");
    if ($user) {
      return send($response, 'Cet email est déjà utilisé', true, 400);
    }
    
    $id = uniqid('u');
    $nom = $data['nom'];
    $prenom = $data['prenom'];
    $numero = $data['numero'];
    $role = $data['role'];
    $password = hash('sha256', $data['password']);
    $date = date('Y-m-d');


    $this->db->query("INSERT INTO UTILISATEUR (id_user, firstName_user, lastName_user, email_user, phoneNumber_user, password_user, createdAt_user, role_user) 
    VALUES('$id', '$prenom' , '$nom', '$email', '$numero', '$password', '$date', '$role')");

    $newuser = $this->db->query("SELECT id_user as id, firstName_user as firstName, lastName_user as lastName, email_user as email, phoneNumber_user as phoneNumber, password_user as password, createdAt_user as createdAt, role_user as role FROM utilisateur WHERE id_user = '$id'");
    return send($response, $newuser, false, 200);

    }catch (Exception $e) {
      return send($response, $e->getMessage(), true, 500);
    }
  }
}