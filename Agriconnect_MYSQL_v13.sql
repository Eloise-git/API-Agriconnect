DROP TABLE IF EXISTS CONTENIR;
DROP TABLE IF EXISTS COMMANDE;
DROP TABLE IF EXISTS MESSAGERIE;
DROP TABLE IF EXISTS PRODUIT;
DROP TABLE IF EXISTS PRODUCTEUR;
DROP TABLE IF EXISTS UTILISATEUR;

CREATE TABLE UTILISATEUR(
   id_user VARCHAR(64),
   firstName_user VARCHAR(64) NOT NULL,
   lastName_user VARCHAR(64) NOT NULL,
   email_user VARCHAR(64) NOT NULL,
   phoneNumber_user INT NOT NULL,
   password_user VARCHAR(64) NOT NULL,
   createdAt_user DATE NOT NULL,
   role_user VARCHAR(64) NOT NULL,
   PRIMARY KEY(id_user)
);

CREATE TABLE PRODUCTEUR(
   id_producer VARCHAR(64),
   desc_producer VARCHAR(256),
   payement_producer VARCHAR(64) NOT NULL,
   name_producer VARCHAR(64) NOT NULL,
   adress_producer VARCHAR(64) NOT NULL,
   latitude_producer FLOAT NOT NULL,
   longitude_producer FLOAT NOT NULL,
   phoneNumber_producer INT NOT NULL,
   category_producer VARCHAR(64) NOT NULL,
   image_producer VARCHAR(255) NOT NULL,
   id_user VARCHAR(64) NOT NULL,
   PRIMARY KEY(id_producer),
   UNIQUE(id_user),
   FOREIGN KEY(id_user) REFERENCES UTILISATEUR(id_user) ON DELETE CASCADE
);

CREATE TABLE PRODUIT(
   id_product VARCHAR(64),
   name_product VARCHAR(64) NOT NULL,
   desc_product VARCHAR(64) NOT NULL,
   type_product VARCHAR(64) NOT NULL,
   price_product DECIMAL(15,2) NOT NULL,
   unit_product VARCHAR(64) NOT NULL,
   stock_product INT NOT NULL,
   image_product VARCHAR(255) NOT NULL,
   id_producer VARCHAR(64) NOT NULL,
   PRIMARY KEY(id_product),
   FOREIGN KEY(id_producer) REFERENCES PRODUCTEUR(id_producer) ON DELETE CASCADE
);

CREATE TABLE COMMANDE(
   id_order VARCHAR(64),
   status_order VARCHAR(64) NOT NULL,
   date_order DATE NOT NULL,
   payement_order VARCHAR(64) NOT NULL,
   id_producer VARCHAR(64) NOT NULL,
   id_user VARCHAR(64) NOT NULL,
   PRIMARY KEY(id_order),
   FOREIGN KEY(id_producer) REFERENCES PRODUCTEUR(id_producer) ON DELETE CASCADE,
   FOREIGN KEY(id_user) REFERENCES UTILISATEUR(id_user) ON DELETE CASCADE
);

CREATE TABLE MESSAGERIE(
   id_message VARCHAR(64),
   date_message DATETIME NOT NULL,
   content_message VARCHAR(256) NOT NULL,
   id_user VARCHAR(64) NOT NULL,
   id_user_1 VARCHAR(64) NOT NULL,
   PRIMARY KEY(id_message),
   FOREIGN KEY(id_user) REFERENCES UTILISATEUR(id_user) ON DELETE CASCADE,
   FOREIGN KEY(id_user_1) REFERENCES UTILISATEUR(id_user) ON DELETE CASCADE
);

CREATE TABLE CONTENIR(
   id_product VARCHAR(64),
   id_order VARCHAR(64),
   PRIMARY KEY(id_product, id_order),
   FOREIGN KEY(id_product) REFERENCES PRODUIT(id_product) ON DELETE CASCADE,
   FOREIGN KEY(id_order) REFERENCES COMMANDE(id_order) ON DELETE CASCADE
);


-- Insertion des utilisateurs
INSERT INTO UTILISATEUR (id_user, firstName_user, lastName_user, email_user, phoneNumber_user, password_user, createdAt_user, role_user) VALUES
('u1', 'Jean', 'Dupont', 'jean.dupont@example.com', 0567891234, '15cbf0d3fcb06da3bdf98d0370a38f00343d0747eecdf416d27556c0f3812fd6', '2023-01-01', 'admin'),
('u2', 'Marie', 'Durand', 'marie.durand@example.com', 0567791234, '430893cdce2e7074821444975c1b6929f88957c6aa63f9e335673b61d241d1ef', '2023-01-02', 'producer'),
('u3', 'Julien', 'Martin', 'julien.martin@example.com', 0567895234, '14c28260dd730c72801cd9eea54aeee1c7a059bc969fae03b08fe23ca9ff8ec4', '2023-01-03', 'client'),
('u4', 'Sophie', 'Petit', 'sophie.petit@example.com', 0567898234, 'c955f01321b0761f22aee14b339a477c430ccdc9513aa79943b6d49599b245e5', '2023-01-04', 'producer'),
('u5', 'Lucas', 'Bernard', 'lucas.bernard@example.com', 0567896734, 'fc2c40745e850b0bd81ea703c9320073e2b29003f63a813fbf061db1ee0ae8a0', '2023-01-05', 'client'),
('u6', 'Nicolas', 'Moreau', 'nicolas.moreau@example.com', 0567891235, '38aa6a971fa8da6a49f20a6174c31ab7fab08769732985855bb1a4d369cdfc3d', '2023-01-06', 'client'),
('u7', 'Chloé', 'Lefevre', 'chloe.lefevre@example.com', 0567791236, 'cf5df4c37002a9415b9cfc27ced4618bfbe86dd421b13623a1f7889a20aea0b0' ,'2023-01-07', 'client'),
('u8', 'Mathieu', 'Girard', 'mathieu.girard@example.com', 0567895237, 'fed0ecc5cf4c2b962ff425dd7fdda1e45b6bb815048e3c3d5f372f195c695638', '2023-01-08', 'client'),
('u9', 'Sarah', 'Roux', 'sarah.roux@example.com', 0567898238, 'e4d55cea25b94dad7f34ebd65e04f7093fff1f05375cad2898117c8ab9fd3c37', '2023-01-09', 'client'),
('u10', 'Antoine', 'Robin', 'antoine.robin@example.com', 0567896739, '85e4af664aec076d1bcf479196be9b8d9e9f45613dcb03b28a2ba55acda93ab0', '2023-01-10', 'producer'),
('u11', 'Isabelle', 'Lefebvre', 'isabelle.lefebvre@example.com', 0567891240, '60a903c464b107d481edaff453889c338701d4aaffb5051d270eb0c0da29cfa4', '2023-01-11', 'client'),
('u12', 'Laurent', 'Leroy', 'laurent.leroy@example.com', 0567891241, '2a8fb194ae178a382bbf5ea5388cb9adff5a332b9ee7e4d533e210c7b6e5d99b', '2023-01-12', 'producer'),
('u13', 'Nathalie', 'Moreau', 'nathalie.moreau@example.com', 0567891242, 'ec2745214359167806131f6e56186f266aa5514de2367b362f1f32ca6f8b9946', '2023-01-13', 'client'),
('u14', 'Pierre', 'Girard', 'pierre.girard@example.com', 0567891243, '15d00ca60f7f3bd41d86a12d2ae8cd76c59b1993a3c939c15b27deabe5c46af9', '2023-01-14', 'producer'),
('u15', 'Sylvie', 'Roux', 'sylvie.roux@example.com', 0567891244, '1c71860c507c8cc5b9bafb41eb4b71d69339f78ea443363489611a0514c87c32', '2023-01-15', 'client'),
('u16', 'Thierry', 'Dupuis', 'thierry.dupuis@example.com', 0567891245, '4dcff3617c82cacc0a8d70c25a69e9ca38a81288e8c40f1ffc4779f368a5c795', '2023-01-16', 'client'),
('u17', 'Valérie', 'Lefevre', 'valerie.lefevre@example.com', 0567891246, '365c8ddd7146890e2b182f5825898ed74e0ce294cbc6081545370fcb53130bc0', '2023-01-17', 'client'),
('u18', 'Xavier', 'Martin', 'xavier.martin@example.com', 0567891247, 'f04ca12b3d3b381c5ad935a1a6b74609d4632f47c51209406ba5afe21cc6cd71', '2023-01-18', 'client'),
('u19', 'Yasmine', 'Leroux', 'yasmine.leroux@example.com', 0567891248, '5af6a24d0104c797e653df16dbdcc4b4b0291a2878433ccb3d1821f7a1fe9716', '2023-01-19', 'producer'),
('u20', 'Zoé', 'Laporte', 'zoe.laporte@example.com', 0567891249, '51d9c397e8be890751e954f903b2e2e34e227a510a3b8d8266f2e0a3eff467c1', '2023-01-20', 'client'),
('u21', 'Michel', 'Leblanc', 'michel.leblanc@example.com', 0567891250, '3ac772514307b233f6763406d02e6b035cd6fdc46ea912feaeddf5d45ee01704', '2023-01-21', 'producer'),
('u22', 'Sylvain', 'Lefort', 'sylvain.lefort@example.com', 0567891251, '3f42e6c9c77aa8303063a628a5c4331494ee6b289f8931796149bdf715e93bf3', '2023-01-22', 'producer'),
('u23', 'Isabelle', 'Lemieux', 'isabelle.lemieux@example.com', 0567891252, '2ea00dd040cf31401a9f7e4a9608d980f29c8a606216033aa98ffcc94b885f00', '2023-01-23', 'producer'),
('u24', 'Sophie', 'Leclerc', 'sophie.leclerc@example.com', 0567891253, 'aa431c8609f603e11a7c80bd6c888e46e5b0016c0db7f142eef92e5880682c23', '2023-01-24', 'producer');

-- Insertion des producteurs
INSERT INTO PRODUCTEUR (id_producer, desc_producer, payement_producer, name_producer, adress_producer, latitude_producer, longitude_producer, phoneNumber_producer, category_producer, image_producer, id_user) VALUES
('p1', 'Producteur de fruits', 'Carte', 'Les fruits de marie', '123 rue de Lille, 75007 Paris', 48.8614748, 2.319559, 0567891234, 'Fruits',"producteur1.png", 'u2'),
('p2', 'Producteur de légumes', 'Espèces', 'Les petites legumes', '456 rue de Paris, 93100 Montreuil', 48.8570395, 2.4304401, 0567891234, 'Légumes',"producteur2.jpg", 'u4'),
('p3', 'Producteur de viande', 'Carte', 'La Robin des boucherie', '78 rue de Strasbourg, 53000 Laval', 48.0715507, -0.7716139, 0567891235, 'Viandes',"producteur3.png", 'u10'),
('p4', 'Producteur de lait', 'Espèces', 'Roymactel', '10 rue de Marseille, 69007 Lyon', 45.7538252, 4.8415504, 0567891236, 'Laits',"producteur4.jpg", 'u12'),
('p5', 'Producteur de vin', 'Chèque', 'La cave a vins', '202 rue de Lyon, 01800 Bourg-Saint-Christophe', 45.8880427, 5.1545673, 0567891237, 'Vins',"producteur5.jpg", 'u14'),
('p6', 'Producteur de fromage', 'Carte', 'La roux du fromage', '30 rue de Bordeaux, 37000 Tours', 47.3897317, 0.6919313, 0567891238, 'Fromages',"producteur6.png", 'u19'),
('p7', 'Producteur de miel', 'Espèces', 'MielsMich', '40 rue de Nantes, 53000 Laval', 48.0649191, -0.7798576, 0567891239, 'Miels',"producteur7.jpg", 'u21'),
('p8', 'Producteur de légumes biologiques', 'Espèces', 'Légumes Bio', '12 Rue des Biolles, 38410 Chamrousse', 45.1238163,5.8720741, 0567891254, 'Légumes',"producteur8.jpeg", 'u22'),
('p9', 'Producteur de produits laitiers', 'Chèque', 'Laiterie Délice', '456 Rue du Lait, 56400 Auray', 47.6670271,-2.9859327, 0567891255, 'Produits laitiers',"producteur9.jpg", 'u23'),
('p10', 'Producteur de confitures artisanales', 'Carte', 'Confitures Gourmandes', '10 Rue Fernand Forest, 61000 Alençon', 48.4374583,0.0985011, 0567891256, 'Confitures',"producteur10.png", 'u24');



-- Insertion des produits
INSERT INTO PRODUIT (id_product, name_product, desc_product, type_product, price_product, unit_product, stock_product, image_product, id_producer) VALUES
('pr1', 'Pomme', 'Pommes rouges juteuses', 'Fruits', 0.50, 'Kg', 100, 'Pomme.jpg', 'p1'),
('pr2', 'Carotte', 'Carottes fraîches', 'Légumes', 0.30, 'Kg', 200, 'Carotte.jpg', 'p2'),
('pr3', 'Blé', 'Blé de haute qualité', 'Céréales', 0.25, 'Kg', 300, 'Ble.jpg', 'p1'),
('pr4', 'Boeuf', 'Viande de boeuf de qualité', 'Viandes', 15.00, 'Kg', 50, 'Boeuf.png', 'p3'),
('pr5', 'Lait', 'Lait frais', 'Laits', 1.00, 'Litre', 200, 'Lait.jpg', 'p1'),
('pr6', 'Vin rouge', 'Vin rouge vieilli', 'Vins', 10.00, 'Bouteille', 100, 'Vin_rouge.jpg', 'p5'),
('pr7', 'Fromage de chèvre', 'Fromage de chèvre artisanal', 'Fromages', 3.00, 'Pièce', 80, 'Fromage_de_chevre.jpg', 'p6'),
('pr8', 'Miel de lavande', 'Miel de lavande pur', 'Miels', 7.00, 'Pot', 100, 'Miel_de_lavande.jpg', 'p7'),
('pr9', 'Pêches', 'Pêches juteuses et sucrées', 'Fruits', 0.75, 'Kg', 150, 'Peches.png', 'p1'),
('pr10', 'Pommes de terre', 'Pommes de terre locales', 'Légumes', 0.40, 'Kg', 250, 'Pommes_de_terre.png', 'p2'),
('pr11', 'Pain aux céréales', 'Pain croustillant aux céréales', 'Pain', 1.50, 'Unité', 80, 'Pain_aux_céréales.png', 'p2'),
('pr12', 'Oeufs frais', 'Oeufs de poule élevées en plein air', 'Produits laitiers', 2.00, 'Douzaine', 120, 'Oeufs.jpg', 'p9'),
('pr13', 'Fraises', 'Fraises fraîches du jardin', 'Fruits', 1.20, 'Kg', 90, 'Fraises.png', 'p8'),
('pr14', 'Jus d orange frais', 'Jus d orange pressé à froid', 'Boissons', 2.50, 'Litre', 70, 'Jus_dorange.jpg', 'p7'),
('pr15', 'Camembert crémeux', 'Fromage français', 'Fromages', 3.80, 'Pièce', 50, 'Camembert.jpg', 'p6'),
('pr16', 'Huile d olive extra vierge', 'Huile d olive de première qualité', 'Épicerie', 5.00, 'Bouteille', 40, 'Huile_dolive.jpg', 'p9'),
('pr17', 'Poulet fermier', 'Poulet élevé en plein air', 'Viandes', 9.00, 'Kg', 60, 'Poulet.jpg', 'p3'),
('pr18', 'Miel d acacia', 'Miel d acacia doux', 'Produits sucrés', 4.50, 'Pot', 80, 'Miel_dacacia.jpg', 'p10'),
('pr19', 'Tomates cerises', 'Tomates cerises biologiques', 'Légumes', 0.90, 'Kg', 70, 'Tomates_cerises.jpg', 'p8'),
('pr20', 'Truite arc-en-ciel', 'Filets de truite arc-en-ciel', 'Poisson', 8.50, 'Kg', 45, 'Truite.jpg', 'p5'),
('pr21', 'Chocolat noir bio', 'Chocolat noir biologique', 'Chocolat', 3.20, 'Tablette', 100, 'Chocolat_noir_bio.jpg', 'p6'),
('pr22', 'Yaourt nature', 'Yaourt nature crémeux', 'Produits laitiers', 1.80, 'Pot', 65, 'Yaourt_nature.jpg', 'p9'),
('pr23', 'Steak de boeuf', 'Steak de boeuf de qualité', 'Viandes', 12.00, 'Kg', 55, 'Boeuf.png', 'p3'),
('pr24', 'Pain complet', 'Pain de blé complet', 'Pain', 1.70, 'Unité', 75, 'Pain_complet.jpg', 'p2'),
('pr25', 'Fromage de chèvre', 'Fromage de chèvre frais', 'Fromages', 3.60, 'Pièce', 60, 'Fromage_de_chevre.jpg', 'p6'),
('pr26', 'Tablette de chocolat au lait', 'Chocolat au lait suisse', 'Chocolat', 2.80, 'Tablette', 90, 'chocolat_au_lait.jpg', 'p6'),
('pr27', 'Poisson-chat frais', 'Filets de poisson-chat frais', 'Poisson', 6.90, 'Kg', 50, 'Poisson_chat.jpg', 'p5'),
('pr28', 'Vinaigre balsamique', 'Vinaigre balsamique de Modène', 'Épicerie', 4.60, 'Bouteille', 40, 'Vinaigre.jpg', 'p9'),
('pr29', 'Café moulu', 'Café moulu fraîchement torréfié', 'Boissons', 6.30, 'Paquet', 75, 'Cafe_moulu.jpg', 'p7'),
('pr30', 'Marmelade d orange', 'Marmelade maison d orange', 'Produits sucrés', 3.40, 'Pot', 85, 'Marmelade_dorange.jpg', 'p10'),
('pr31', 'Poivrons rouges', 'Poivrons rouges frais', 'Légumes', 0.80, 'Kg', 70, 'Poivrons_rouges.jpg', 'p8'),
('pr32', 'Morue salée', 'Filets de morue salée', 'Poisson', 7.60, 'Kg', 40, 'Morue_salee.png', 'p5'),
('pr33', 'Raisins', 'Raisins frais de la vigne', 'Fruits', 0.60, 'Kg', 150, 'Raisins.png', 'p1'),
('pr34', 'Poireaux', 'Poireaux biologiques', 'Légumes', 0.35, 'Kg', 200, 'Poireaux.jpg', 'p2'),
('pr35', 'Baguette tradition', 'Baguette de pain traditionnelle', 'Pain', 1.80, 'Unité', 90, 'Baguette_tradition.jpg', 'p2'),
('pr36', 'Lait de soja', 'Lait de soja sans lactose', 'Boissons', 2.20, 'Litre', 60, 'Lait_de_soja.jpg', 'p7'),
('pr37', 'Riz basmati', 'Riz basmati parfumé', 'Céréales', 1.60, 'Kg', 100, 'Riz_basmati.jpg', 'p9'),
('pr38', 'Agneau', 'Viande d agneau de qualité', 'Viandes', 11.00, 'Kg', 70, 'Agneau.jpg', 'p3'),
('pr39', 'Tarte aux pommes', 'Tarte aux pommes fraîche', 'Pâtisserie', 4.00, 'Unité', 50, 'Tarte_aux_pommes.jpg', 'p4'),
('pr40', 'Champignons', 'Champignons frais', 'Légumes', 0.90, 'Kg', 80, 'Champignons.jpg', 'p8');

-- Insertion des commandes
INSERT INTO COMMANDE (id_order, status_order, date_order, payement_order, id_producer, id_user) VALUES
('o1', 'En cours', '2023-01-01', 'Carte', 'p1', 'u3'),
('o2', 'Livré', '2023-01-02', 'Espèces', 'p2', 'u3'),
('o3', 'Annulé', '2023-01-03', 'Chèque', 'p1', 'u5'),
('o4', 'En cours', '2023-01-04', 'Carte', 'p3', 'u6'),
('o5', 'Livré', '2023-01-05', 'Espèces', 'p4', 'u7'),
('o6', 'Annulé', '2023-01-06', 'Chèque', 'p5', 'u8'),
('o7', 'En cours', '2023-01-07', 'Carte', 'p6', 'u9'),
('o8', 'Livré', '2023-01-08', 'Espèces', 'p7', 'u10'),
('o9', 'En cours', '2023-02-01', 'Carte', 'p1', 'u3'),
('o10', 'En cours', '2023-02-02', 'Espèces', 'p2', 'u4'),
('o11', 'En cours', '2023-02-03', 'Chèque', 'p3', 'u5'),
('o12', 'En cours', '2023-02-04', 'Carte', 'p4', 'u6'),
('o13', 'En cours', '2023-02-05', 'Espèces', 'p5', 'u7'),
('o14', 'En cours', '2023-02-06', 'Chèque', 'p6', 'u8'),
('o15', 'En cours', '2023-02-07', 'Carte', 'p7', 'u9'),
('o16', 'En cours', '2023-02-08', 'Espèces', 'p8', 'u10'),
('o17', 'En cours', '2023-02-09', 'Chèque', 'p9', 'u1'),
('o18', 'En cours', '2023-02-10', 'Carte', 'p10', 'u2');

-- Insertion des messages
INSERT INTO MESSAGERIE (id_message, date_message, content_message, id_user, id_user_1) VALUES
('m1', '2023-01-01 10:00:00', 'Bonjour, comment puis-je vous aider ?', 'u1', 'u3'),
('m2', '2023-01-01 10:05:00', 'Je cherche des informations sur vos produits.', 'u3', 'u1'),
('m3', '2023-01-01 10:10:00', 'Bien sûr, que souhaitez-vous savoir ?', 'u1', 'u3'),
('m4', '2023-01-01 10:15:00', 'Quels types de produits proposez-vous ?', 'u3', 'u1'),
('m5', '2023-01-01 10:20:00', 'Nous avons une large gamme de fruits et légumes frais.', 'u1', 'u3'),
('m6', '2023-01-02 11:00:00', 'Pouvez-vous recommander un bon vin?', 'u6', 'u1'),
('m7', '2023-01-02 11:05:00', 'Bien sûr, notre vin rouge vieilli est excellent.', 'u1', 'u6'),
('m8', '2023-01-02 11:10:00', 'Parfait, je vais en prendre une bouteille.', 'u6', 'u1'),
('m9', '2023-01-02 11:15:00', 'Je vous remercie pour votre achat!', 'u1', 'u6'),
('m10', '2023-01-02 11:20:00', 'Avez-vous du miel de lavande?', 'u7', 'u1'),
('m11', '2023-01-02 11:20:00', 'Avez-vous des pommes de terre ?', 'u7', 'u2');


-- Insertion des détails des commandes
INSERT INTO CONTENIR (id_product, id_order) VALUES
('pr1', 'o1'),
('pr2', 'o2'),
('pr3', 'o3'),
('pr4', 'o4'),
('pr5', 'o5'),
('pr6', 'o6'),
('pr7', 'o7'),
('pr8', 'o8'),
('pr1', 'o9'),
('pr2', 'o10'),
('pr3', 'o11'),
('pr4', 'o12'),
('pr5', 'o13'),
('pr6', 'o14'),
('pr7', 'o15'),
('pr8', 'o16'),
('pr1', 'o17'),
('pr2', 'o18'),
('pr3', 'o1'),
('pr4', 'o2'),
('pr5', 'o3'),
('pr6', 'o4'),
('pr7', 'o5'),
('pr8', 'o6'),
('pr1', 'o7'),
('pr2', 'o8'),
('pr3', 'o9'),
('pr4', 'o10'),
('pr5', 'o11'),
('pr6', 'o12'),
('pr7', 'o13'),
('pr8', 'o14');
