# API de réservation

Ce projet fournit une API RESTful simple pour la gestion des réservations. Construit à l'aide de Symfony, il permet aux utilisateurs de créer, mettre à jour, supprimer et afficher les réservations.

## Fonctionnalités

- Créer une nouvelle réservation
- Mettre à jour une réservation existante
- Supprimer une réservation
- Récupérer une liste de toutes les réservations

## Exigences

- PHP 8.2 ou supérieur
- Composer
- Symfony Framework
- Doctrine ORM

## Installation

1. Clonez le dépôt :
git clone https://github.com/yourusername/api-rest-reservation.git

2. Accédez au répertoire du projet :
api-rest-reservation

3. Installer les dépendances :
composer install

4. Configurez votre connexion à la base de données dans le fichier .env.

5. Exécutez les migrations de base de données :

php bin/console doctrine:migrations:migrate

## Utilisation

Vous pouvez utiliser n'importe quel client API (comme Postman) pour interagir avec l'API.

### Points de terminaison de l'API

1. Créer une réservation
- Point de terminaison : `POST api/reservation/create`
- Corps de la requête:
json
{
"during_stay": 6,
"status": "confirmed",
"r_cost": 250.5,
"check_in": "2024-10-15",
"check_out": "2024-10-22"
}

2. Mettre à jour une réservation
- Point de terminaison : PUT api/reservation/update/{id}
- Corps de la requête : identique à la requête de création.

3. Supprimer une réservation
- Point de terminaison : DELETE api/reservation/delete/{id}

4. Obtenir toutes les réservations
- Point de terminaison: GET api/reservations

## Tests

Exécutez les tests à l'aide de PHPUnit :
php vendor/bin/phpunit tests/Controller/ReservationControllerTest.php
