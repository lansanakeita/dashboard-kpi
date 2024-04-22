## Installation de dépendances
```bash
composer install
```

## Lancer la base de données
```bash
docker-compose up -d
```

## Générer les clés JWT

## Lancer le serveur
```bash
symfony server:start
```

## Créer la base de données
```bash
php bin/console doctrine:database:create
```


## Créer une migration
```bash
php bin/console make:migration
```

## Effectuer des migrations
```bash
php bin/console doctrine:migrations:migrate
```

## Ajouter une entité pour la page d'administration
```bash
php bin/console make:admin:crud
```

## Créer une ressource API Platform
```bash
php bin/console make:entity --api-resource
```

## Générer des clés JWT
```bash
php bin/console lexik:jwt:generate-keypair
```

> Username : prénom des utilisateurs
> Mot de passe : adminpass
> password_client : clientpass