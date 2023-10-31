<?php
namespace App\controllers;

use App\models\Controller;
use App\models\Database;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;
use function App\lib\sendJSON;


require_once __DIR__ . '/../lib/utils.php';

class AuthController extends Controller
{
  public function __construct()
  {
    $this->db = new Database();
  }

  /**
   * Fonction de connexion de l'utilisateur
   *
   * @param string $email
   * @param string $password
   * 
   * @return Response HTTP response
   */
  public function login(Request $request, Response $response, array $args)
  {
    try {
      $data = $request->getParsedBody();

      $email = $data['email'] ?? null;
      $password = $data['password'] ?? null;

      if (!$email || !$password) {
        return sendJSON($response, 'Tous les champs sont obligatoires', true, 400);
      }

      $hashedPassword = hash('sha256', $password);
      $sql = "SELECT * FROM utilisateur WHERE email_user = '$email' AND password_user = '$hashedPassword'";
      $userlogin = $this->db->query($sql);

      if (!$userlogin) {
        return sendJSON($response, 'Email ou mot de passe incorrect', true, 400);
      }
      $userlogin = $userlogin[0];

      $settings = require __DIR__ . '/../settings/settings.php';
      $key = $settings['settings']['jwt']['secret'];
      $payload = array(
        "iat" => time(),
        "exp" => time() + 30 * 24 * 60 * 60, // 30 jours
        "data" => [
          "id" => $userlogin['id_user'],
          "role" => $userlogin['role_user']
        ]
      );

      $jwt = JWT::encode($payload, $key, 'HS256');
      $res = [
        'id' => $userlogin['id_user'],
        'role' => $userlogin['role_user'],
        'accessToken' => $jwt
      ];

      return sendJSON($response, $res, false, 200);

    } catch (Exception $e) {
      return sendJSON($response, $e->getMessage(), true, 500);
    }
  }

  /**
   * Enregistre un nouvel utilisateur dans la base de données.
   *
   * @param string $nom
   * @param string $prenom
   * @param string $email
   * @param string $password
   * @param string $numero
   * @param string $role
   *
   * @return Response HTTP response
   */
  public function register(Request $request, Response $response, array $args)
  {
    try {
      $data = $request->getParsedBody();

      $nom = $data['nom'] ?? null;
      $prenom = $data['prenom'] ?? null;
      $email = $data['email'] ?? null;
      $password = $data['password'] ?? null;
      $numero = $data['numero'] ?? null;
      $role = $data['role'] ?? null;

      if (!$nom || !$prenom || !$email || !$password || !$numero || !$role) {
        return sendJSON($response, 'Tous les champs sont obligatoires', true, 400);
      }

      $sql = "SELECT * FROM utilisateur WHERE email_user = '$email'";
      $user = $this->db->query($sql);

      if ($user) {
        return sendJSON($response, 'Cet email est déjà utilisé', true, 400);
      }

      $id = uniqid('u');
      $hashedPassword = hash('sha256', $password);
      $date = date('Y-m-d');

      $sql = "INSERT INTO UTILISATEUR (id_user, firstName_user, lastName_user, email_user, phoneNumber_user, password_user, createdAt_user, role_user) VALUES('$id', '$prenom' , '$nom', '$email', '$numero', '$hashedPassword', '$date', '$role')";
      $this->db->query($sql);

      $sql = "SELECT id_user as id, firstName_user as firstName, lastName_user as lastName, email_user as email, phoneNumber_user as phoneNumber, password_user as password, createdAt_user as createdAt, role_user as role FROM utilisateur WHERE id_user = '$id'";
      $newuser = $this->db->query($sql)[0];

      return sendJSON($response, $newuser, false, 200);

    } catch (Exception $e) {
      return sendJSON($response, $e->getMessage(), true, 500);
    }
  }

}