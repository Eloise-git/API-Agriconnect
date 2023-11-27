<?php
namespace App\Services;

use App\Models\Service;
use Exception;
use PDO;

class ProducerService extends Service
{
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllProducer()
    {
        $sql = "SELECT * FROM PRODUCTEUR";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $producers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $producers;
    }

    public function getProducerById($id)
    {
        $sql = "SELECT * FROM PRODUCTEUR WHERE id_producer = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $aProducer = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$aProducer) {
            throw new Exception("Le producteur n'existe pas", 404);
        }
        return $aProducer;
    }
    public function getProducerByUserId($id)
    {
        $sql = "SELECT producteur.id_producer FROM `utilisateur` JOIN producteur ON producteur.id_user=utilisateur.id_user WHERE utilisateur.id_user = :id AND role_user='producer' ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $aProducer = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$aProducer) {
            throw new Exception("Vous n'êtes pas producteur", 404);
        }
        
        return $aProducer;
    }

    public function getProducerByName($name)
    {
        $sql = "SELECT * FROM PRODUCTEUR WHERE name_producer = :name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $name]);
        $aProducer = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $id = $aProducer[0]['id_user'];
        $sql = "SELECT createdAt_user FROM `utilisateur` WHERE id_user = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $createdAt_user = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$aProducer) {
            throw new Exception("Le producteur n'existe pas", 404);
        }
        $result = [];
        foreach ($aProducer as $producer) {
            $item = [
                "id" => $producer['id_producer'],
                "name" => $producer['name_producer'],
                "description" => $producer['desc_producer'],
                "paymentMethod" => $producer['payement_producer'],
                "address" => $producer['adress_producer'],
                "phone" => $producer['phoneNumber_producer'],
                "category" => $producer['category_producer'],
                "createdAt" => $createdAt_user[0]['createdAt_user']
            ];
            $result[] = $item;
        }
        return $result;
    }
    public function searchByNameLocationTypeDistance($nom,$location,$type,$distance){
        $sql = "SELECT * FROM PRODUCTEUR WHERE name_producer LIKE :nom OR adress_producer LIKE :location OR category_producer LIKE :type";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['nom' => '%'.$nom.'%','location' => '%'.$location.'%','type' => '%'.$type.'%']);
        $aProducer = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$aProducer) {
            throw new Exception("Le producteur n'existe pas", 404);
        }
        $result = [];
        foreach ($aProducer as $producer) {
            $item = [
                "id" => $producer['id_producer'],
                "name" => $producer['name_producer'],
                "description" => $producer['desc_producer'],
                "payementMethod" => $producer['payement_producer'],
                "adress" => $producer['adress_producer'],
                "phoneNumber" => $producer['phoneNumber_producer'],
                "category" => $producer['category_producer'],

            ];
            $result[] = $item;
        }
        return $result;
    }

    function calculer_distance($adresse1,$adresse2) {
        $adresse1 = str_replace(" ", "+", $adresse1); //adresse de départ
        $adresse2 = str_replace(" ", "+", $adresse2); //adresse d'arrivée
        $url = 'http://maps.google.com/maps/api/directions/xml?language=fr&origin='.$adresse1.'&destination='.$adresse2.'&sensor=false'; //on créé l'url
        
        //on lance une requete aupres de google map avec l'url créée
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        $xml = curl_exec($ch);
        
        //on réccupère les infos
        $charger_googlemap = simplexml_load_string($xml);
        $distance = $charger_googlemap->route->leg->distance->value;
        
        //si l'info est récupérée, on calcule la distance
        if ($charger_googlemap->status == "OK") {
        $distance = $distance/1000;
        $distance = number_format($distance, 2, ',', ' ');
        
        return $distance;
        }
        else {
        //si l'info n'est pas récupérée, on lui attribu 0
        return "0";
        }
        
        //si le bouton calculer est lancé, on récupère les informations du formulaire et on lance la fonction
        if (isset($_POST['calculer'])) {
        $dep = $_POST['dep'];
        $ari = $_POST['ari'];
        $nbr = $_POST['nbr'];
        $pkm = $_POST['pkm'];
        $prix = $pkmcalculer_distance($dep,$ari);
        $type = $_POST['type'];
        
        if ($type == 'a') {
          $prix = $prix * $nbr;
        }
        elseif ($type == 'ar') {
        $prix = $prix*$nbr;
        $prix = $prix+$prix;
        }
        echo '<p>' . $dep . ' -> ' . $ari . '<b> ' . calculer_distance($dep,$ari) . ' KM</b></p>';
        echo '<p><b>PRIX : ' . $prix . ' EUROS</b></p>';
        }
      }
    

    public function postProducer($producerId, $desc, $payement, $name, $adress,
        $phoneNumber, $category, $producerId_user)
    {
        $sql = "INSERT INTO PRODUCTEUR (id_producer, desc_producer, payement_producer, name_producer, 
                adress_producer, phoneNumber_producer, category_producer, id_user) 
                VALUES (:id,:desc,:payement, :name,:adress,:phone,:category,:id_user);";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $producerId,
            'desc' => $desc,
            'payement' => $payement,
            'name' => $name,
            'adress' => $adress,
            'phone' => $phoneNumber,
            'category' => $category,
            'id_user' => $producerId_user
            ]);
        $producer = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($producer) {
            throw new Exception("Le producteur existe déjà", 409);
        }
        $producer = $this->getProducerById($producerId);
        return $producer;
    }

    public function updateProducerById($id_producer, $desc_producerWanted, $payement_producerWanted, $name_producerWanted, 
        $adress_producerWanted, $phoneNumber_producerWanted, $category_producerWanted)
    {
        $sql = "UPDATE PRODUCTEUR SET desc_producer= :desc, payement_producer= :payement, 
                name_producer= :name, adress_producer= :adress, phoneNumber_producer= :phone, 
                category_producer= :category WHERE id_producer = :id_producer;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'desc' => $desc_producerWanted,
            'payement' => $payement_producerWanted,
            'name' => $name_producerWanted,
            'adress' => $adress_producerWanted,
            'phone' => $phoneNumber_producerWanted,
            'category' => $category_producerWanted,
            'id_producer' => $id_producer
        ]);

        $producer = $this->getProducerById($id_producer);
        if (!$producer) {
        throw new Exception("Erreur lors de la mise à jour du producteur : " . implode(", ", $stmt->errorInfo()));
        }
        return $producer;
    }

    public function deleteProducerById($id)
    {
        $sql = "DELETE FROM MESSAGERIE WHERE id_user = :id OR id_user_1 = :id;
        DELETE FROM PRODUCTEUR WHERE id_user = :id;
        DELETE FROM CONTENIR WHERE id_order IN (SELECT id_order FROM COMMANDE WHERE id_user = :id);
        DELETE FROM COMMANDE WHERE id_user = :id;
        DELETE FROM UTILISATEUR WHERE id_user = :id;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
}