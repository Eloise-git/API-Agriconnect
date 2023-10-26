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
    $data = $request->getParsedBody();
    $role = getbody();

    // Vérifier que tous les champs sont renseignés
    if (!isset($data['nom']) || !isset($data['prenom']) || !isset($data['email']) || !isset($data['password'])) {
      return $response->withStatus(400)->getBody()->write('Tous les champs sont obligatoires');
    }

    // Vérifier que l'email n'est pas déjà utilisé
    $email = $data['email'];
    $user = $this->db->query("SELECT * FROM utilisateur WHERE email_user = '$email'");
    if ($user === true) {
      return $response->withStatus(400)->getBody()->write('Cet email est déjà utilisé');
    }

    // Insérer l'utilisateur dans la base de données
    $id = uniqid('u');
    $nom = $data['nom'];
    $prenom = $data['prenom'];
    $password = crypt($data['password'], CRYPT_SHA256);
    $date = date('Y-m-d');
    $this->db->query("INSERT INTO UTILISATEUR (id_user, firstName_user, lastName_user, email_user, phoneNumber_user, password_user, createdAt_user, role_user) 
      VALUES('u1',$prenom , $nom, $email, $numero, $password, '2023-01-01', $role");

    // Retourner un message de confirmation
    return $response->getBody()->write('Inscription réussie');
  }


}