<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\ResponseFactory;

class AuthControllerTest extends TestCase
{
    private $app;

    protected function setUp(): void
    {
        $this->app = (require __DIR__ . '/../public/index.php');
    }

    public function testLogin()
    {
        $request = (new ServerRequestFactory())->createServerRequest('POST', '/login');
        $request = $request->withParsedBody([
            'email' => 'jean.dupont@example.com',
            'password' => 'motdepasse1',
        ]);
        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty((string) $response->getBody());
    }

    public function testRegister()
    {
        $request = (new ServerRequestFactory())->createServerRequest('POST', '/register');
        $request = $request->withParsedBody([
            'nom' => 'Jipé',
            'prenom' => 'Jean',
            'email' => 'azertyTest@gmail.com',
            'password' => 'azerty12345678',
            'numero' => '0123456789',
            'role' => 'client',
        ]);
        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty((string) $response->getBody());
    }
}