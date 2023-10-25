<?php
namespace App\controllers;

use App\models\Database;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

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
  public function home(RequestInterface $request, ResponseInterface $response, array $args)
  {
    $users = $this->db->query('SELECT * FROM utilisateur');
    $response->getBody()->write(json_encode($users));
    return $response;
  }

  //Permet de voir si un client est connecté
  public function login (Request $request, Response $response) {

    $data = $request->getParsedBody();
    if (!isset($data['email']) || !isset($data['password'])) {
      return $response->withStatus(400)->getBody()->write('Tous les champs sont obligatoires');
    }
    $email = $data['email'];
    $password= $data['password'];
    $userlogin = $this->db->query("SELECT * FROM utilisateur WHERE email_user = '$email' AND password_user = '$password'");
    if ($userlogin===false) {
      return $response->withStatus(401);
    }else{ 
      $data = $userlogin;
      $response->getBody()->write(json_encode($data));
      return $response
      ->withHeader('Content-Type', 'application/json')
      ->withStatus(200);
    }
  }


    //Permet de s'inscrire à l'application
    public function register(RequestInterface $request, ResponseInterface $response, array $args)
    {
      $data = $request->getParsedBody();

      // Vérifier que tous les champs sont renseignés
      if (!isset($data['nom']) || !isset($data['prenom']) || !isset($data['email']) || !isset($data['password'])) {
        return $response->withStatus(400)->getBody()->write('Tous les champs sont obligatoires');
      }
      
      // Vérifier que l'email n'est pas déjà utilisé
      $email = $data['email'];
      $user = $this->db->query("SELECT * FROM utilisateur WHERE email_user = '$email'");
      if ($user===true) {
        return $response->withStatus(400)->getBody()->write('Cet email est déjà utilisé');
      }

      // Insérer l'utilisateur dans la base de données
      $nom = $data['nom'];
      $prenom = $data['prenom'];
      $password = crypt($data['password'],CRYPT_SHA256 );
      $this->db->query("INSERT INTO UTILISATEUR (id_user, firstName_user, lastName_user, email_user, phoneNumber_user, password_user, createdAt_user, role_user) 
      VALUES('u1',$prenom , $nom, $email, $numero, $password, '2023-01-01', 'client')");

      // Retourner un message de confirmation
      return $response->getBody()->write('Inscription réussie');
    }

    
}