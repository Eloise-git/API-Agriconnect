<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\ResponseFactory;

class UserControllerTest extends TestCase
{
    private $app;

    protected function setUp(): void
    {
        $this->app = (require __DIR__ . '/../public/index.php');
        $this ->userToken = $this->loginAndGetUserInfo()['accessToken'];
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
        return $body;
    }


    public function testGetUser()
    {
        $request = (new ServerRequestFactory())->createServerRequest('GET', '/user');
        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty((string) $response->getBody()); 
    }

    public function testPutUser()
    {
        $request = (new ServerRequestFactory())->createServerRequest('PUT', '/user');
        $request = $request->withParsedBody([
            'name' => 'Jean',
            'surname' => 'Dupont',
            'email' => 'jean.dupont@example.com',
            'phone' => '0606060606',
            'password' => 'motdepasse2'
        ]);
        $response = $this->app->handle($request);
        $body = json_decode((string) $response->getBody(), true);
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty((string) $response->getBody());
        
    }
    public function testDeleteUser()
    {
        $request = (new ServerRequestFactory())->createServerRequest('DELETE', '/user');
        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty((string) $response->getBody());
    }

}