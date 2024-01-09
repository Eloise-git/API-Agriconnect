<?php

namespace App\Services;

use App\Models\Service;
use Exception;
use PDO;

class ProductService extends Service
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

    public function getAllbyidproducer($id_producer)
    {
        $sql = "SELECT * FROM PRODUIT WHERE id_producer = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id_producer]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($products as $product) {
            $item = [
                "id" => $product['id_product'],
                "name" => $product['name_product'],
                "description" => $product['desc_product'],
                "type" => $product['type_product'],
                "price" => $product['price_product'],
                "unit" => $product['unit_product'],
                "stock" => $product['stock_product'],
                "image" => $this->api_url . $this->path_image . $product['image_product'],
                "id_producter" => $product['id_producer']
            ];
            $result[] = $item;
        }

        return $result;
    }

    public function getProductById($id)
    {
        $sql = "SELECT * FROM PRODUIT WHERE id_product = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            throw new Exception("Le produit n'existe pas", 404);
        }

        return [
            "id" => $product['id_product'],
            "name" => $product['name_product'],
            "description" => $product['desc_product'],
            "type" => $product['type_product'],
            "price" => $product['price_product'],
            "unit" => $product['unit_product'],
            "stock" => $product['stock_product'],
            "image" => $this->api_url . $this->path_image . $product['image_product'],
            "id_producter" => $product['id_producer']
        ];
    }

    public function addProduct($id, $name, $description, $type, $price, $unit, $stock, $image, $id_producer)
    {
        $sql = "INSERT INTO PRODUIT (id_product,name_product, desc_product, type_product, price_product, unit_product, stock_product, image_product, id_producer) VALUES (:id,:name, :description, :type, :price, :unit, :stock,:image, :id_producer)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'type' => $type,
            'price' => $price,
            'unit' => $unit,
            'stock' => $stock,
            'image' => $image,
            'id_producer' => $id_producer
        ]);

        return $this->getProductById($id);
    }

    public function updateProductById($id, $name, $type, $price, $unit, $stock)
    {
        $sql = "UPDATE PRODUIT SET name_product = :name, type_product = :type, price_product = :price, unit_product = :unit, stock_product = :stock WHERE id_product = :id;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'name' => $name,
            'type' => $type,
            'price' => $price,
            'unit' => $unit,
            'stock' => $stock
        ]);

        return $this->getProductById($id);
    }
    
    public function deleteProductById($id)
    {
        $sql = "DELETE FROM PRODUIT WHERE id_product = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
}
