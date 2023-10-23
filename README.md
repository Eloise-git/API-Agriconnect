# API de AgriConnect

<!-- français -->

## Introduction

Ce document décrit l'API de AgriConnect.

## Prérequis

- Web server with URL rewriting
- PHP 7.4 or newer

## But

L'API de AgriConnect permet de gérer les données de l'application AgriConnect :

- S'inscrire en tant que client ou producteur
- Explorer les produits disponibles
- Passer des commandes
- Gérer son compte et ses produits (pour les producteurs)

## Endpoints

### Authentification

- `POST /api/auth/login` : Permet de se connecter à l'application
- `POST /api/auth/register` : Permet de s'inscrire à l'application

### Utilisateurs

- `GET /api/user/{id}` : Permet d'obtenir les informations d'un utilisateur
- `PUT /api/user/{id}` : Permet de mettre à jour les informations d'un utilisateur
- `DELETE /api/user/{id}` : Permet de supprimer un utilisateur

## Producteurs

- `GET /api/producers` : Permet d'obtenir la liste des producteurs
- `GET /api/producer/{id}` : Permet d'obtenir les informations d'un producteur
- `POST /api/producer` : Permet d'ajouter un producteur
- `PUT /api/producer/{id}` : Permet de mettre à jour les informations d'un producteur
- `DELETE /api/producer/{id}` : Permet de supprimer un producteur

### Produits

- `GET /api/products` : Permet d'obtenir la liste des produits
- `GET /api/product/{id}` : Permet d'obtenir les informations d'un produit
- `POST /api/product` : Permet d'ajouter un produit
- `PUT /api/product/{id}` : Permet de mettre à jour les informations d'un produit
- `DELETE /api/product/{id}` : Permet de supprimer un produit

### Commandes

- `GET /api/orders` : Permet d'obtenir la liste des commandes
- `GET /api/order/{id}` : Permet d'obtenir les informations d'une commande
- `POST /api/order` : Permet d'ajouter une commande
- `PUT /api/order/{id}` : Permet de mettre à jour les informations d'une commande
- `DELETE /api/order/{id}` : Permet de supprimer une commande

### Messages

- `GET /api/messages` : Permet d'obtenir la liste des messages
- `GET /api/message/{id}` : Permet d'obtenir les informations d'un message
- `POST /api/message` : Permet d'ajouter un message
- `DELETE /api/message/{id}` : Permet de supprimer un message

### Stocks

- `GET /api/stocks` : Permet d'obtenir la liste des stocks
- `PUT /api/stock/{id}` : Permet de mettre à jour les informations d'un stock

## Construit avec

- [Slim PHP](https://www.slimframework.com/) - Framework PHP
- [Composer](https://getcomposer.org/) - Gestionnaire de dépendances
