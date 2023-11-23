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
        $allstocks = [];
        foreach ($stocks as $stock) {
            $item = [
                "id" => $stock['id_product'],
                "name" => $stock['name_product'],
                "catégorie" => $stock['type_product'],
                "quantité" => $stock['stock_product'],
                "disponible"=> $stock['stock_product']-$stock['Reservés'],
                "réservés"=> $stock['Reservés']
                ];
            $allstocks[] = $item;
        }
        return $allstocks;
    }

    public function getAStockById($id_product, $id_producer)
    {
        $sql = "SELECT * FROM produit WHERE id_product = :id_product
                AND id_producer = :id_producer";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_product' => $id_product,
            'id_producer' => $id_producer
        ]);
        $stock = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $stock;
    }

    public function updateStockById($id_product, $id_producer, $stock_product)
    {
        $sql = "UPDATE produit SET stock_product = :stock_product  WHERE id_product = :id_product;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_product' => $id_product,
            'stock_product' => $stock_product
        ]);

        $stock = $this->getAStockById($id_product, $id_producer);
        return $stock;
    }
}