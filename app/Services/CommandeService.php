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

    public function getAllOrders($id_producer)
    {
        $sql = "SELECT COMMANDE.id_order, SUM(PRODUIT.price_product) AS total_price, COMMANDE.status_order, COMMANDE.date_order, COMMANDE.payement_order, UTILISATEUR.firstName_user, UTILISATEUR.lastName_user FROM COMMANDE JOIN CONTENIR ON CONTENIR.id_order = COMMANDE.id_order JOIN PRODUIT ON PRODUIT.id_product = CONTENIR.id_product JOIN UTILISATEUR ON COMMANDE.id_user = UTILISATEUR.id_user WHERE COMMANDE.id_producer = :id_producer GROUP BY COMMANDE.id_order, COMMANDE.status_order, COMMANDE.date_order, COMMANDE.payement_order, UTILISATEUR.firstName_user, UTILISATEUR.lastName_user";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_producer' => $id_producer]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($orders as $order) {
            $item = [
                "numero" => $order['id_order'],
                "statut" => $order['status_order'],
                "date" => $order['date_order'],
                "montant" => $order['total_price'].' €',
                "client" => $order["firstName_user"].' '.$order["lastName_user"],
            ];
    
            $all[] = $item;
        }
    
        return $all;
    }

    public function getAnOrderById($id)
    {
        $sql = "SELECT * FROM COMMANDE JOIN CONTENIR ON CONTENIR.id_order = COMMANDE.id_order JOIN PRODUIT ON PRODUIT.id_product = CONTENIR.id_product JOIN UTILISATEUR ON COMMANDE.id_user = UTILISATEUR.id_user WHERE COMMANDE.id_order = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $settings = require dirname(__DIR__) . '/Settings/Settings.php';
        $dbSettings = $settings['settings']['app'];

        $url = $dbSettings['url'];

        $chemin = "/ressource/image/";

        foreach ($order as $order) {
            $item = [
                "numero" => $order['id_order'],
                "statut" => $order['status_order'],
                "date" => $order['date_order'],
                "payement" => $order['payement_order'],
                "id_product" => $order['id_product'],
                "name_product" => $order['name_product'],
                "desc_product" => $order['desc_product'],
                "type_product" => $order['type_product'],
                "price_product" => $order['price_product'],
                "unit_product" => $order['unit_product'],
                "stock_product" => $order['stock_product'],
                "image_product" => $url . $chemin . $order['image_product'],
                "id_producer" => $order['id_producer'],
                "id_user" => $order['id_user'],
                "firstName_user" => $order['firstName_user'],
                "lastName_user" => $order['lastName_user'],
                "email_user" => $order['email_user'],
                "phoneNumber_user" => $order['phoneNumber_user'],
                "password_user" => $order['password_user'],
                "createdAt_user" => $order['createdAt_user'],
                "role_user" => $order['role_user'],
            ];
    
            $all[] = $item;
        }
        return $all;
    }

    public function postOrder($id_orderWanted, $status_orderWanted, $date_orderWanted, $payement_orderWanted, $id_producer_orderWanted, $id_user_orderWanted, $listProducts)
{
    try {
        $this->db->beginTransaction();

        $sql = "INSERT INTO COMMANDE (id_order, status_order, date_order, payement_order, id_producer, id_user) 
                VALUES (:id_order, :status, :date, :payement, :id_producer, :id_user);";
        
        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([
            'id_order' => $id_orderWanted,
            'status' => $status_orderWanted,
            'date' => $date_orderWanted,
            'payement' => $payement_orderWanted,
            'id_producer' => $id_producer_orderWanted,
            'id_user' => $id_user_orderWanted
        ]);

        if (!$result) {
            throw new Exception("Error inserting order", 500);
        }

        if (!is_array($listProducts)) {
            $listProducts = explode(', ', $listProducts);
        }

        for ($i = 0; $i < count($listProducts); $i++) {
            $sqlRequete = "INSERT INTO CONTENIR (id_product, id_order) VALUES (:id_product, :id_order);";
            $stmtRequete = $this->db->prepare($sqlRequete);
            $resultRequete = $stmtRequete->execute([
                'id_product' => $listProducts[$i],
                'id_order' => $id_orderWanted
            ]);

            if (!$resultRequete) {
                throw new Exception("Error inserting product into order", 500);
            }
        }

        $this->db->commit();

        return $this->getAnOrderById($id_orderWanted);
    } catch (PDOException $e) {

        $this->db->rollBack();

        if ($e->errorInfo[1] == 1452) {
            throw new Exception("Foreign key constraint violation: " . $e->getMessage(), 500);
        } else {
            throw $e;
        }
    }
}
    



    public function updateOrderById($id_orderWanted, $status_orderWanted)
    {
        $sql = "UPDATE COMMANDE SET status_order=:statut WHERE id_order =:id_order;";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'statut' => $status_orderWanted,
            'id_order' => $id_orderWanted
        ]);
        
        return $this->getAnOrderById($id_orderWanted);
    }

    public function deleteOrderById($id)
    {
        $sql = "DELETE FROM COMMANDE WHERE id_order = :id;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
}