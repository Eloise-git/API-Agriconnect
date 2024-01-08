<?php

namespace App\Services;

use App\Models\Service;
use Exception;
use PDO;

class StockService extends Service
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

    public function getAllStock($id_producer)
    {
        $sql = "SELECT PRODUIT.id_product,name_product,type_product,stock_product,image_product,price_product,unit_product,COUNT(id_order)AS 'Reservés' FROM PRODUIT LEFT JOIN CONTENIR ON CONTENIR.id_product=PRODUIT.id_product WHERE id_producer = :id_producer GROUP BY PRODUIT.id_product";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_producer' => $id_producer]);
        $stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $allstocks = [];
        foreach ($stocks as $stock) {
            $item = [
                "id" => $stock['id_product'],
                "name" => $stock['name_product'],
                "category" => $stock['type_product'],
                "quantity" => $stock['stock_product'],
                "available" => $stock['stock_product'] - $stock['Reservés'],
                "reserved" => $stock['Reservés'],
                "price" => $stock['price_product'],
                "image" => $this->api_url . $this->path_image . $stock['image_product'],
                "unit" => $stock['unit_product']
            ];
            $allstocks[] = $item;
        }
        return $allstocks;
    }

    public function getAStockById($id_product, $id_producer)
    {
        $sql = "SELECT * FROM PRODUIT WHERE id_product = :id_product AND id_producer = :id_producer";
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
        $sql = "UPDATE PRODUIT SET stock_product = :stock_product  WHERE id_product = :id_product;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_product' => $id_product,
            'stock_product' => $stock_product
        ]);

        return $this->getAStockById($id_product, $id_producer);
    }
}
