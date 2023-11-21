<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\ResponseFactory;

class ProducerControllerTest extends TestCase
{
    private $app;

    protected function setUp(): void
    {
        $this->app = (require __DIR__ . '/../public/index.php');
    }

    public function testGetAllProducer()
    {
        $request = (new ServerRequestFactory())->createServerRequest('GET', '/producers');
        
        $response = $this->app->handle($request);
        var_dump($response->getBody());
        $this->assertNotEmpty((string) $response->getBody());
    }

    public function testGetProducerById()
    {
        $id_producerWanted = 'p1';
        $request = (new ServerRequestFactory())->createServerRequest('GET', "/producer/$id_producerWanted");
        $response = $this->app->handle($request);
    
        $this->assertNotEmpty((string) $response->getBody());
    }

    // public function testPostProducer(){
    //     $id_producerWanted = 'p11';
    //     $desc_producerWanted = 'Producteur local de légumes';
    //     $payement_producerWanted = 'Carte';
    //     $name_producerWanted = 'BioToo';
    //     $adress_producerWanted = '405 allée de Ville';
    //     $phoneNumber_producerWanted = '0525101856';
    //     $category_producerWanted = 'Légumes';
    //     $id_userWanted = 'u25';

    //     $request = (new ServerRequestFactory())->createServerRequest('POST', '/producer');
    //     $response = $this->app->handle($request);

    //     $this->assertNotEmpty((string) $response->getBody());
    // }

    // public function testUpdateProducerById()
    // {
    //     $request = (new ServerRequestFactory())->createServerRequest('PUT', "/producer/$id_producerWanted");
    //     $response = $this->app->handle($request);

    //     $this->assertNotEmpty((string) $response->getBody());
    // }

    public function testDeleteProducer()
    {
        $request = (new ServerRequestFactory())->createServerRequest('DELETE', '/producer')
            ->withHeader('Authorization', 'Bearer ' . $this->userToken);
        $response = $this->app->handle($request);

        $this->assertNotEmpty((string) $response->getBody());
    }

}