<?php

namespace App\Services;

use Exception;
use PDO;
use App\Models\Service;
use function App\Lib\getDistanceBetweenPoints;

class ProducerService extends Service
{
    private $api_url;
    private $path_image;
    public function __construct($db)
    {
        $this->db = $db;
        $settings = require dirname(__DIR__) . '/Settings/Settings.php';
        $this->api_url = $settings['settings']['app']['url'];
        $this->path_image = '/ressource/image/';
    }


    public function getAllProducer()
    {
        $sql = "SELECT * FROM PRODUCTEUR";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $producers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($producers as $producer) {
            $item = [
                "id" => $producer['id_producer'],
                "name" => $producer['name_producer'],
                "description" => $producer['desc_producer'],
                "paymentMethod" => $producer['payement_producer'],
                "address" => $producer['adress_producer'],
                "phoneNumber" => $producer['phoneNumber_producer'],
                "image" => $this->api_url . $this->path_image . $producer['image_producer'],
                "category" => $producer['category_producer']
            ];
            $result[] = $item;
        }

        return $result;
    }

    public function getProducerById($id)
    {
        $sql = "SELECT * FROM PRODUCTEUR WHERE id_producer = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $aProducer = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$aProducer) {
            throw new Exception("Le producteur n'existe pas", 404);
        }

        $result = [];
        foreach ($aProducer as $producer) {
            $item = [
                "id" => $producer['id_producer'],
                "name" => $producer['name_producer'],
                "description" => $producer['desc_producer'],
                "paymentMethod" => $producer['payement_producer'],
                "address" => $producer['adress_producer'],
                "phoneNumber" => $producer['phoneNumber_producer'],
                "image" => $this->api_url . $this->path_image . $producer['image_producer'],
                "category" => $producer['category_producer']
            ];
            $result[] = $item;
        }

        return $result;
    }
    public function getProducerByUserId($id)
    {
        $sql = "SELECT PRODUCTEUR.id_producer FROM UTILISATEUR JOIN PRODUCTEUR ON PRODUCTEUR.id_user=UTILISATEUR.id_user WHERE UTILISATEUR.id_user = :id AND role_user='producer' ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $aProducer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$aProducer) {
            throw new Exception("Vous n'êtes pas producteur", 404);
        }

        return $aProducer;
    }

    public function getProducerByName($name)
    {
        $sql = "SELECT * FROM PRODUCTEUR WHERE name_producer = :name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $name]);
        $aProducer = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$aProducer) {
            throw new Exception("Le producteur n'existe pas", 404);
        }
        $id = $aProducer[0]['id_user'];
        $sql = "SELECT createdAt_user FROM UTILISATEUR WHERE id_user = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $createdAt_user = $stmt->fetchAll(PDO::FETCH_ASSOC);


        $settings = require dirname(__DIR__) . '/Settings/Settings.php';
        $dbSettings = $settings['settings']['app'];

        $url = $dbSettings['url'];

        $chemin = "/ressource/image/";

        $result = [];
        foreach ($aProducer as $producer) {
            $item = [
                "id" => $producer['id_producer'],
                "name" => $producer['name_producer'],
                "description" => $producer['desc_producer'],
                "paymentMethod" => $producer['payement_producer'],
                "address" => $producer['adress_producer'],
                "phoneNumber" => $producer['phoneNumber_producer'],
                "category" => $producer['category_producer'],
                'image' => $this->api_url . $this->path_image . $producer['image_producer'],
                "createdAt" => $createdAt_user[0]['createdAt_user']
            ];
            $result[] = $item;
        }

        return $result;
    }

    public function searchByNameLocationTypeDistance($name, $location, $type, $distance)
    {
        $sql = "SELECT * FROM PRODUCTEUR WHERE (name_producer LIKE :name OR category_producer LIKE :type) AND name_producer LIKE :producerName AND category_producer LIKE :producerType;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':name' => "%$name%", ':type' => "%$type%", ':producerName' => "%$name%", ':producerType' => "%$type%"]);
        $producers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($producers)) {
            return [];
        }

        $result = [];
        $locationParts = $this->parseLocation($location);
        if ($locationParts == null || $distance == null) {
            foreach ($producers as $producer) {
                $result[] = $this->formatProducer($producer);
            }

            return $result;
        }
        $location = explode(',', $location);
        $latitudeLocation = trim($location[0]);
        $longitudeLocation = trim($location[1]);

        foreach ($producers as $producer) {
            $producerDistance = getDistanceBetweenPoints(
                $latitudeLocation,
                $longitudeLocation,
                $producer['latitude_producer'],
                $producer['longitude_producer']
            );

            if ($producerDistance <= $distance) {
                $result[] = $this->formatProducer($producer);
            }
        }

        return $result;
    }

    public function postProducer(
        $producerId,
        $desc,
        $payement,
        $name,
        $adress,
        $phoneNumber,
        $category,
        $image,
        $producerId_user
    ) {
        $sql = "INSERT INTO PRODUCTEUR (id_producer, desc_producer, payement_producer, name_producer, adress_producer, phoneNumber_producer, category_producer,image_producer, id_user) VALUES (:id,:desc,:payement, :name,:adress,:phone,:category,:image,:id_user);";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $producerId,
            'desc' => $desc,
            'payement' => $payement,
            'name' => $name,
            'adress' => $adress,
            'phone' => $phoneNumber,
            'category' => $category,
            'image' => $image,
            'id_user' => $producerId_user
        ]);
        $producer = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($producer) {
            throw new Exception("Le producteur existe déjà", 409);
        }

        return $this->getProducerById($producerId);
    }

    public function updateProducerById(
        $id_producer,
        $desc_producerWanted,
        $payement_producerWanted,
        $name_producerWanted,
        $adress_producerWanted,
        $phoneNumber_producerWanted,
        $category_producerWanted
    ) {
        $sql = "UPDATE PRODUCTEUR SET desc_producer= :desc, payement_producer= :payement, name_producer= :name, adress_producer= :adress, phoneNumber_producer= :phone,  category_producer= :category WHERE id_producer = :id_producer;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'desc' => $desc_producerWanted,
            'payement' => $payement_producerWanted,
            'name' => $name_producerWanted,
            'adress' => $adress_producerWanted,
            'phone' => $phoneNumber_producerWanted,
            'category' => $category_producerWanted,
            'id_producer' => $id_producer
        ]);

        $producer = $this->getProducerById($id_producer);

        if (!$producer) {
            throw new Exception("Erreur lors de la mise à jour du producteur : " . implode(", ", $stmt->errorInfo()));
        }

        return $producer;
    }

    public function deleteProducerById($id)
    {
        $sql = "DELETE FROM MESSAGERIE WHERE id_user = :id OR id_user_1 = :id;
        DELETE FROM PRODUCTEUR WHERE id_user = :id;
        DELETE FROM CONTENIR WHERE id_order IN (SELECT id_order FROM COMMANDE WHERE id_user = :id);
        DELETE FROM COMMANDE WHERE id_user = :id;
        DELETE FROM UTILISATEUR WHERE id_user = :id;";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
    }


    private function formatProducer($producer)
    {
        return [
            "id" => $producer['id_producer'],
            "name" => $producer['name_producer'],
            "description" => $producer['desc_producer'],
            "paymentMethod" => $producer['payement_producer'] ?? null,
            "address" => $producer['adress_producer'],
            "latitude" => (float) $producer['latitude_producer'],
            "longitude" => (float) $producer['longitude_producer'],
            "phoneNumber" => (int) $producer['phoneNumber_producer'],
            "category" => $producer['category_producer'],
            "image" => $this->api_url . $this->path_image . $producer['image_producer']
        ];
    }

    private function parseLocation($location)
    {
        if (!empty($location) && is_string($location)) {
            $parts = explode(',', $location);
            if (count($parts) === 2) {
                return array_map('trim', $parts);
            }
        }
        return null;
    }
}
