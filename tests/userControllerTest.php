<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\ResponseFactory;

class UserControllerTest extends TestCase
{
    private $app;
    private $userToken;

    protected function setUp(): void
    {
        $this->app = (require __DIR__ . '/../public/index.php');
        $this->userToken = $this->loginAndGetUserInfo()['accessToken'];
    }

    private function loginAndGetUserInfo()
    {
        $request = (new ServerRequestFactory())->createServerRequest('POST', '/login');
        $request = $request->withParsedBody([
            'email' => 'jean.dupont@example.com',
            'password' => 'motdepasse1',
        ]);
        $response = $this->app->handle($request);
        $body = json_decode((string) $response->getBody(), true);
        return $body['data'];
    }

    public function testGetUser()
    {
        $request = (new ServerRequestFactory())->createServerRequest('GET', '/user')
            ->withHeader('Authorization', 'Bearer ' . $this->userToken);
        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty((string) $response->getBody());
    }

  /*  public function testPutUser()
        {
            $request = (new ServerRequestFactory())->createServerRequest('PUT', '/user')
                ->withHeader('Authorization', 'Bearer ' . $this->userToken);

            // Adjust the request body before sending it
            $request = $request->withParsedBody([
                'nom' => 'Jean',
                'prenom' => 'Fernaud',
                'email' => 'jean.Fernaud@example.com',
                'password' => 'motdepasse1',
                'numero' => '0606060606',
            ]);

            $response = $this->app->handle($request);
            $body = json_decode((string) $response->getBody(), true);

            $this->assertEquals('Jean', $body['nom']);
        }*/

    public function testDeleteUser()
    {
        $request = (new ServerRequestFactory())->createServerRequest('DELETE', '/user')
            ->withHeader('Authorization', 'Bearer ' . $this->userToken);
        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty((string) $response->getBody());
    }
}