# Projet de Site de Recettes

- Adil KHADICH: Adil_IUT
- Evan CHAUFFOUR: Evanchauffour

## Description du Projet
Ce projet est une application web permettant aux utilisateurs de partager et de découvrir des recettes de cuisine. Il est composé d'un backend développé avec Symfony et API Platform, et d'un frontend réalisé avec Next.js. L'objectif est de permettre aux utilisateurs de :
- Créer des recettes avec des images, des ingrédients et des étapes.
- Associer les recettes à des catégories.
- Consulter les recettes des autres utilisateurs.
- Rechercher des recettes grâce aux filtres d'API Platform.
- Liker des recettes pour les retrouver dans la page "Mes likes" (fonctionnalité disponible uniquement pour les utilisateurs connectés).

## Technologies utilisées
- **Backend :** Symfony avec API Platform
- **Frontend :** Next.js
- **Base de données :** PostgreSQL
- **Stockage des images :** Flysystem avec VichUploader
- **Authentification :** JWT avec LexikJWTAuthenticationBundle

## Installation et Lancement du Projet
### Prérequis
- PHP 8.1+
- Composer
- Node.js 16+
- Docker & Docker Compose

### Backend (Symfony + API Platform)
1. Cloner le projet :
   ```sh
   git clone https://github.com/Adkhey74/CookWithMe.git
   ```
2. Installer les dépendances :
   ```sh
   composer install
   ```
3. Configurer les variables d'environnement :
   Les fichiers `.env` nécessaires pour chaque environnement sont déjà fournis dans le projet.
4. Lancer la base de données avec Docker :
   ```sh
   docker-compose up -d
   ```
5. Créer la base de données :
   ```sh
   symfony console doctrine:database:create
   symfony console doctrine:migrations:migrate
   symfony console doctrine:fixtures:load
   ```
6. Lancer le serveur Symfony :
   ```sh
   symfony server:start
   ```

L'API sera disponible sur `http://127.0.0.1:8000/api`.

### Frontend (Next.js)
1. Cloner le projet frontend :
   ```sh
   git clone https://github.com/Evanchauffour/CookWithMeFront.git
   cd frontend
   ```
2. Installer les dépendances :
   ```sh
   npm install
   ```
3. Les fichiers `.env` nécessaires pour chaque environnement sont déjà fournis dans le projet.
4. Lancer le serveur Next.js :
   ```sh
   npm run dev
   ```

Le site sera disponible sur `http://localhost:3000`.

## Gestion du Stockage des Images
La gestion du stockage des images repose sur plusieurs solutions selon l'environnement :
- **Environnement de développement** : Les images sont stockées localement dans `public/uploads/recipes`.
- **Environnement de test** : Un test a été implémenté pour vérifier que les fichiers sont stockés en mémoire :
  ```php
  use League\Flysystem\FilesystemOperator;
  use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

  class FlysystemTest extends KernelTestCase
  {
    private FilesystemOperator $storage;

    protected function setUp(): void
    {
      self::bootKernel();
      $this->storage = self::getContainer()->get('recipe_images_storage');
    }

    public function testFileCanBeWrittenAndReadInMemory(): void
    {
      $this->storage->write('test.txt', 'Ceci est un fichier en mémoire');
      $this->assertTrue($this->storage->fileExists('test.txt'));

      $content = $this->storage->read('test.txt');
      $this->assertEquals('Ceci est un fichier en mémoire', $content);
    }
  }
  ```
- **Environnement de production** : Les images sont stockées sur un bucket Google Cloud Storage.
- **Problème identifié avec Flysystem** :
  - Les images sont bien stockées dans le bon emplacement selon l’environnement :
    - En **développement**, elles sont stockées localement dans `public/uploads/recipes`.
    - En **test**, elles sont stockées en mémoire.
    - En **production**, elles sont stockées sur Google Cloud Storage.
  - Cependant, l'URL générée pour récupérer ces images n'est pas correcte, ce qui empêche le frontend de les afficher correctement.

> **Note :** Le fichier de configuration contenant les credentials de Google Cloud Storage (`config/symfony-flysystem.json`) n'a pas été partagé dans le dépôt pour des raisons de sécurité. Cependant, l'upload fonctionne bien et peut être configuré en fournissant un fichier de configuration valide.

### Lancer l'Application en Mode Production
1. Les fichiers `.env` nécessaires pour la production sont déjà inclus dans le projet.
2. Nettoyer et optimiser le cache :
   ```sh
   php bin/console cache:clear --env=prod
   php bin/console cache:warmup --env=prod
   ```
3. Déployer et configurer le serveur (ex. via Docker ou autre infrastructure).

## Fonctionnalités Principales
- Inscription et connexion des utilisateurs avec JWT.
- Création, modification et suppression de recettes.
- Ajout d’images aux recettes via VichUploader.
- Recherche avancée avec les filtres d'API Platform.
- Affichage des détails d’une recette.
- Liker des recettes et les retrouver dans "Mes likes".

## Fonctionnalités Avancées
- **Gestion avancée de l’upload d’images** :
  - Initialement, l’upload d’images a été implémenté avec VichUploader.
  - Ensuite, Flysystem a été ajouté pour permettre une meilleure gestion du stockage en fonction de l’environnement.
  - Cependant, la relation entre les deux outils n’a pas fonctionné comme prévu, entraînant un double upload que nous n'avons pas réussi à résoudre faute d'expérience sur Symfony et du manque de documentation sur leur intégration commune.
  - Un problème identifié est l'absence de résolution dynamique de l'URL des fichiers en fonction de l’environnement, empêchant le frontend d'afficher correctement les images.

## Apprentissage et Difficultés
N’étant pas habitués à Symfony, ce projet nous a permis de découvrir une nouvelle manière de développer. Nous avons appris à manipuler les entités, les relations et la gestion des images avec Flysystem et VichUploader, tout en intégrant un frontend moderne avec Next.js.

## Lien des Repositories
- **Backend :** [Lien du repo backend](https://github.com/Adkhey74/CookWithMe.git)
- **Frontend :** [Lien du repo frontend](https://github.com/Evanchauffour/CookWithMeFront.git)

## Conclusion
Ce projet nous a permis de découvrir Symfony et API Platform. Nous avons appris à manipuler les entités, les relations et la gestion des images avec Flysystem et VichUploader, tout en intégrant un frontend moderne avec Next.js. Cette expérience nous a permis d’acquérir de nouvelles compétences et de mieux comprendre le développement avec Symfony.


