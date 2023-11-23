<?php

namespace App\services;

use App\models\Service;
use Exception;
use PDO;

class ProductService extends Service
{
  public function __construct($db)
  {
    $this->db = $db;
  }

public function getAll(){
    $sql = "SELECT * FROM produit";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $all = [];

    foreach ($products as $product) {
        $item = [
            "id" => $product['id_product'],
            "name" => $product['name_product'],
            "description" => $product['desc_product'],
            "type" => $product['type_product'],
            "price" => $product['price_product'],
            "unit" => $product['unit_product'],
            "stock" => $product['stock_product'],
            "image" => $product['image_product'],
            "id_producter"=> $product['id_producer']
        ];

        $all[] = $item;
    }

    return $all;
}
public function getProductById($id){
    $sql = "SELECT * FROM produit WHERE id_product = :id";
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
        "image" => $product['image_product'],
        "id_producter"=> $product['id_producer']
    ];
}
public function addProduct($id,$name, $description, $type, $price, $unit, $stock,$image, $id_producer){
    $sql = "INSERT INTO PRODUIT (id_product,name_product, desc_product, type_product, price_product, unit_product, stock_product, image_product, id_producer) 
    VALUES (:id,:name, :description, :type, :price, :unit, :stock,:image, :id_producer)";
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
public function updateProductById($id, $name, $description, $type, $price, $unit, $stock, $image){
    $sql = "UPDATE PRODUIT
    SET name_product = :name,
        desc_product = :description,
        type_product = :type,
        price_product = :price,
        unit_product = :unit,
        stock_product = :stock,
        image_product = :image
    WHERE id_product = :id;";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        'id' => $id,
        'name' => $name,
        'description' => $description,
        'type' => $type,
        'price' => $price,
        'unit' => $unit,
        'stock' => $stock,
        'image' => $image
    ]);

    return $this->getProductById($id);
}
public function deleteProductById($id){
    $sql = "DELETE FROM PRODUIT WHERE id_product = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['id' => $id]);

    return true;
}

}