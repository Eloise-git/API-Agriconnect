<?php
namespace App\Services;

use App\Models\Service;
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
        if (!$aProducer) {
            throw new Exception("Le producteur n'existe pas", 404);
        }
        return $aProducer;
    }
    public function getProducerByUserId($id)
    {
        $sql = "SELECT producteur.id_producer FROM `utilisateur` JOIN producteur ON producteur.id_user=utilisateur.id_user WHERE utilisateur.id_user = :id AND role_user='producer' ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $aProducer = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$aProducer) {
            throw new Exception("Vous n'êtes pas producteur", 404);
        }
        
        return $aProducer;
    }

    public function getProducerByName($name)
    {
        $sql = "SELECT * FROM `producteur` WHERE name_producer = :name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $name]);
        $aProducer = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $id = $aProducer[0]['id_user'];
        $sql = "SELECT createdAt_user FROM `utilisateur` WHERE id_user = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $createdAt_user = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                "phone" => $producer['phoneNumber_producer'],
                "category" => $producer['category_producer'],
                "createdAt" => $createdAt_user[0]['createdAt_user']
            ];
            $result[] = $item;
        }
        return $result;
    }
    

    public function postProducer($producerId, $desc, $payement, $name, $adress,
        $phoneNumber, $category, $producerId_user)
    {
        $sql = "INSERT INTO producteur (id_producer, desc_producer, payement_producer, name_producer, 
                adress_producer, phoneNumber_producer, category_producer, id_user) 
                VALUES (:id,:desc,:payement, :name,:adress,:phone,:category,:id_user);";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $producerId,
            'desc' => $desc,
            'payement' => $payement,
            'name' => $name,
            'adress' => $adress,
            'phone' => $phoneNumber,
            'category' => $category,
            'id_user' => $producerId_user
            ]);
        $producer = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($producer) {
            throw new Exception("Le producteur existe déjà", 409);
        }
        $producer = $this->getProducerById($producerId);
        return $producer;
    }

    public function updateProducerById($id_producer, $desc_producerWanted, $payement_producerWanted, $name_producerWanted, 
        $adress_producerWanted, $phoneNumber_producerWanted, $category_producerWanted)
    {
        $sql = "UPDATE producteur SET desc_producer= :desc, payement_producer= :payement, 
                name_producer= :name, adress_producer= :adress, phoneNumber_producer= :phone, 
                category_producer= :category WHERE id_producer = :id_producer;";
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
}