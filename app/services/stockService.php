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
        $sql = "SELECT name_product, desc_product, stock_product FROM `produit` WHERE id_producer = :id_producer";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_producer' => $id_producer]);
        $stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $stocks;
    }

    public function getAStockById($id_product, $id_producer)
    {
        $sql = "SELECT name_product, desc_product, stock_product FROM `produit` WHERE id_product = ':id_product'
                AND id_producer = :id_producer";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_product' => $id_product,
            'id_producer' => $id_producer
        ]);
        $stock = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $stock;
    }

    public function updateStockById($id_product, $name_product, 
    $desc_product, $type_product, $price_product, $unit_product, $stock_product, $id_producer)
    {
        $sql = "UPDATE produit SET (name_product=':name', 
            desc_product=':desc', type_product=':type', 
            price_product=':price', unit_product=':unit', stock_product = ':stock';)
            WHERE id_product = :id_product AND id_producer = ':id_producer'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'name' => $name_product,
            'desc' => $desc_product,
            'type' => $type_product,
            'price' => $price_product,
            'unit' => $unit_product,
            'stock' => $stock_product,
            'id_product' => $id_product,
            'id_producer' => $id_producer
        ]);

        $stock = $this->getStockById($id_product, $id_producer);
        if (!$stock) {
        throw new Exception("Erreur lors de la mise à jour du stock : " . implode(", ", $stmt->errorInfo()));
        }
        return $stock;
    }
}