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

    public function getAnOrderById($id)
    {
        $sql = "SELECT * FROM `commande` WHERE id_order = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $order;
    }

    public function postOrder($id_orderWanted, $status_orderWanted, 
                $date_orderWanted, $payement_orderWanted, $id_producer_orderWanted, $id_user_orderWanted)
    {
        $sql = "INSERT INTO commande (id_order, status_order, date_order, payement_order, id_producer, id_user) 
                VALUES (':id_order',':status', ':date',':payement',':id_producer',':id_user';";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_order' => $id_orderWanted,
            'status' => $status_orderWanted,
            'date' => $date_orderWanted,
            'payement' => $payement_orderWanted,
            'id_producer' => $id_producer_orderWanted,
            'id_user' => $id_user_orderWanted
            ]);
        $order = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($order) {
            throw new Exception("La commande existe déjà", 409);
        }
        return $order;
    }

    public function updateOrderById($id_orderWanted, $status_orderWanted, 
    $date_orderWanted, $payement_orderWanted, $id_producer_orderWanted, $id_user_orderWanted)
    {
        $sql = "UPDATE commmande SET (status_order=':status', 
            date_order=':date', payement_order=':payement', 
            id_producer=':id_producer', id_user=':id_user';) WHERE id_order = :id_order";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'status_order' => $status_orderWanted,
            'date_order' => $date_orderWanted,
            'payement_order' => $payement_orderWanted,
            'id_producer' => $id_producer_orderWanted,
            'id_user' => $id_user_orderWanted,
            'id_order' => $id_orderWanted
        ]);

        $order = $this->getOrderById($id_orderWanted);
        if (!$order) {
        throw new Exception("Erreur lors de la mise à jour de la commande : " . implode(", ", $stmt->errorInfo()));
        }
        return $order;
    }

    public function deleteOrderById($id)
    {
        $sql = "DELETE FROM COMMANDE WHERE id_order = :id;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
}