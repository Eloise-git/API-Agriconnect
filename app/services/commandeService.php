<?php

namespace App\services;

use App\models\Service;
use Exception;
use PDO;

class CommandesService extends Service
{
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllOrders()
    {
        $sql = "SELECT * FROM `commande`";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $orders;
    }

    public function getAnOrder($id)
    {
        $sql = "SELECT * FROM `commande` WHERE id_order = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $order;
    }

    // public function getProducerById($id)
    // {
    //     $sql = "SELECT * FROM `producteur` WHERE id_producer = :id";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute(['id' => $id]);
    //     $aProducer = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //     return $aProducer;
    // }

    // public function postProducer($desc_producerWanted, $payement_producerWanted, 
    //             $name_producerWanted, $adress_producerWanted, $phoneNumber_producerWanted, $category_producerWanted)
    // {
    //     $sql = "INSERT INTO producer (desc_producer, payement_producer, name_producer, adress_producer, phoneNumber_producer, category_producer) 
    //             VALUES (':desc',':payement', ':name',':adress',':phone',':category';";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute([
    //         'desc' => $desc_producerWanted,
    //         'payement' => $payement_producerWanted,
    //         'name' => $name_producerWanted,
    //         'adress' => $adress_producerWanted,
    //         'phone' => $phoneNumber_producerWanted,
    //         'category' => $category_producerWanted
    //         ]);
    //     $producer = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     if ($producer) {
    //         throw new Exception("Le producteur existe déjà", 409);
    //     }
    //     return $producer;
    // }

    // public function updateProducerById($id_producer, $desc_producerWanted, $payement_producerWanted, $name_producerWanted, 
    //     $adress_producerWanted, $phoneNumber_producerWanted, $category_producerWanted, $id_user)
    // {
    //     $sql = "UPDATE producer SET (desc_producer=':desc', 
    //         payement_producer=':payement', name_producer=':name', 
    //         adress_producer=':adress', phoneNumber_producer=':phone', 
    //         category_producer=':category';) WHERE id_producer = :id_producer AND id_user = :id_user";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute([
    //         'desc' => $desc_producerWanted,
    //         'payement' => $payement_producerWanted,
    //         'name' => $name_producerWanted,
    //         'adress' => $adress_producerWanted,
    //         'phone' => $phoneNumber_producerWanted,
    //         'category' => $category_producerWanted,
    //         'id_producer' => $id_producer,
    //         'id_user' => $id_user
    //     ]);

    //     $producer = $this->getProducerById($id_producer);
    //     if (!$producer) {
    //     throw new Exception("Erreur lors de la mise à jour du producteur : " . implode(", ", $stmt->errorInfo()));
    //     }
    //     return $producer;
    // }

    // public function deleteProducerById($id)
    // {
    //     $sql = "DELETE FROM MESSAGERIE WHERE id_user = :id OR id_user_1 = :id;
    //     DELETE FROM PRODUCTEUR WHERE id_user = :id;
    //     DELETE FROM CONTENIR WHERE id_order IN (SELECT id_order FROM COMMANDE WHERE id_user = :id);
    //     DELETE FROM COMMANDE WHERE id_user = :id;
    //     DELETE FROM UTILISATEUR WHERE id_user = :id;";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute(['id' => $id]);
    // }
}