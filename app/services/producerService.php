<?php

namespace App\services;

use App\models\Service;
use Exception;
use PDO;

class ProducerService extends Service
{
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllProducer()
    {
        $sql = "SELECT * FROM `producteur`";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $producers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $producers;
    }

    public function getProducerById($id)
    {
        $sql = "SELECT * FROM `producteur` WHERE id_producer = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $aProducer = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $aProducer;
    }

    public function postProducer($desc_producerWanted, $payement_producerWanted, 
                $name_producerWanted, $adress_producerWanted, $phoneNumber_producerWanted, $category_producerWanted)
    {
        $sql = "INSERT INTO producer (desc_producer, payement_producer, name_producer, adress_producer, phoneNumber_producer, category_producer) 
                VALUES (':desc',':payement', ':name',':adress',':phone',':category';";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'desc' => $desc_producerWanted,
            'payement' => $payement_producerWanted,
            'name' => $name_producerWanted,
            'adress' => $adress_producerWanted,
            'phone' => $phoneNumber_producerWanted,
            'category' => $category_producerWanted
            ]);
        $producer = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($producer) {
            throw new Exception("L'utilisateur existe déjà", 409);
        }
        return $producer;
    }

    public function putProducer($id_producer, $desc_producerWanted, $payement_producerWanted, $name_producerWanted, 
                    $adress_producerWanted, $phoneNumber_producerWanted, $category_producerWanted, $id_user)
    {
        $sql = "SELECT * FROM producteur WHERE id_producer = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id_producer]);
        $producer = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($producer) {
        throw new Exception("Le producteur existe déjà", 409);
        }

        $sql = "UPDATE producer SET (desc_producer=':desc', 
        payement_producer=':payement', name_producer=':name', 
        adress_producer=':adress', phoneNumber_producer=':phone', 
        category_producer=':category';) WHERE id_producer = :id_producer AND id_user = :id_user";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'desc' => $desc_producerWanted,
            'payement' => $payement_producerWanted,
            'name' => $name_producerWanted,
            'adress' => $adress_producerWanted,
            'phone' => $phoneNumber_producerWanted,
            'category' => $category_producerWanted,
            'id_producer' => $id_producer,
            'id_user' => $id_user
        ]);
        
        $sql = "SELECT * FROM producteur WHERE id_producer = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id_producer]);
        $producer = $stmt->fetch(PDO::FETCH_ASSOC);
        var_dump($producer);
        
        return [
        "id" => $producer['id_producer'],
        "desc" => $producer['desc_producer'],
        "payement" => $producer['payement_producer'],
        "name" => $producer['name_producer'],
        "adress" => $producer['adress_producer'],
        "category" => $producer['category'],
        "id_user" => $producer['id_user']
        ];
    }
}