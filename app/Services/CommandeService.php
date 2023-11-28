<?php
namespace App\Services;

use App\Models\Service;
use Exception;
use PDO;

class CommandeService extends Service
{
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllOrders()
    {
        $sql = "SELECT * FROM COMMANDE";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $orders;
    }
    public function getAllOrdersbyProducerId($id_producer)
    {
        $sql = "SELECT * FROM COMMANDE JOIN CONTENIR ON CONTENIR.id_order=COMMANDE.id_order JOIN PRODUIT ON PRODUIT.id_product=CONTENIR.id_product JOIN UTILISATEUR ON COMMANDE.id_user=UTILISATEUR.id_user WHERE COMMANDE.id_producer = :id_producer";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_producer' => $id_producer]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($orders as $order) {
            $item = [
                "numero" => $order['id_order'],
                "statut" => $order['status_order'],
                "date" => $order['date_order'],
                "montant" => $order["price_product"].' '.$order["unit_product"],
                "client" => $order["firstName_user"].' '.$order["lastName_user"],
            ];
    
            $all[] = $item;
        }
    
        return $all;
    }

    public function getAnOrderById($id)
    {
        $sql = "SELECT * FROM COMMANDE WHERE id_order = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $order;
    }

    public function postOrder($id_orderWanted, $status_orderWanted, 
                $date_orderWanted, $payement_orderWanted, $id_producer_orderWanted, $id_user_orderWanted)
    {
        $sql = "INSERT INTO COMMANDE (id_order, status_order, date_order, payement_order, id_producer, id_user) 
                VALUES (:id_order, :status, :date, :payement, :id_producer, :id_user)";

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
        
        return $this->getAnOrderById($id_orderWanted);
    }

    public function updateOrderById($id_orderWanted, $status_orderWanted, 
    $date_orderWanted, $payement_orderWanted, $id_producer_orderWanted, $id_user_orderWanted)
    {
        $sql = "UPDATE COMMANDE SET status_order=:status, date_order=:date, payement_order=:payement,
        id_producer=:id_producer, id_user=:id_user WHERE id_order =:id_order;";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'status' => $status_orderWanted,
            'date' => $date_orderWanted,
            'payement' => $payement_orderWanted,
            'id_producer' => $id_producer_orderWanted,
            'id_user' => $id_user_orderWanted,
            'id_order' => $id_orderWanted
        ]);
        
        return [
            'status' => $status_orderWanted,
            'date' => $date_orderWanted,
            'payement' => $payement_orderWanted,
            'id_producer' => $id_producer_orderWanted,
            'id_user' => $id_user_orderWanted,
            'id_order' => $id_orderWanted
        ];
    }

    public function deleteOrderById($id)
    {
        $sql = "DELETE FROM COMMANDE WHERE id_order = :id;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
}