<?php

namespace App\services;

use App\models\Service;
use Exception;
use PDO;

class StockService extends Service
{
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllStock($id_producer)
    {
        $sql = "SELECT contenir.id_product,name_product,type_product,stock_product,COUNT(id_order)AS 'Reservés' FROM `produit` JOIN contenir ON contenir.id_product=produit.id_product WHERE id_producer = :id_producer GROUP BY contenir.id_product";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_producer' => $id_producer]);
        $stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return [
            "id" => $stocks[1]['id_product'],
            "name" => $stocks[1]['name_product'],
            "catégorie" => $stocks[1]['type_product'],
            "quantité" => $stocks[1]['stock_product'],
            "disponible"=> $stocks[1]['stock_product']-$stocks[1]['Reservés'],
            "réservés"=> $stocks[1]['Reservés']
            ];;
    }

    public function getAStockById($id_product, $id_producer)
    {
        $sql = "SELECT * FROM `produit` WHERE id_product = ':id_product'
                AND id_producer = :id_producer";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_product' => $id_product,
            'id_producer' => $id_producer
        ]);
        $stock = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $stock;
    }

    public function updateStockById($id_product)
    {
        $sql = "UPDATE produit SET ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
        ]);

        $stock = $this->getStockById($id_product, $id_producer);
        if (!$stock) {
        throw new Exception("Erreur lors de la mise à jour du stock : " . implode(", ", $stmt->errorInfo()));
        }
        return $stock;
    }
}