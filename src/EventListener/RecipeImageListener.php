<?php

namespace App\EventListener;

use App\Entity\RecipeImage;
use App\Service\FileUploader;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;

class RecipeImageListener
{
  private FileUploader $uploader;

  public function __construct(FileUploader $uploader)
  {
    $this->uploader = $uploader;
  }

  // Avant de sauvegarder une entité
  public function prePersist(PrePersistEventArgs $args): void
  {
    $entity = $args->getObject();

    // Vérifiez si l'entité est de type RecipeImage
    if (!$entity instanceof RecipeImage) {
      return;
    }

    // Gérer le fichier uploadé
    if ($entity->file !== null) {
      $path = $this->uploader->upload($entity->file, 'recipe_images');
      $entity->filePath = $path; // Mettez à jour le chemin du fichier dans l'entité
    }
  }

  // Avant de supprimer une entité
  public function preRemove(LifecycleEventArgs $args): void
  {
    $entity = $args->getObject();

    if (!$entity instanceof RecipeImage) {
      return;
    }

    // Supprimez le fichier si nécessaire
    if ($entity->filePath !== null) {
      $this->uploader->delete($entity->filePath);
    }
  }
}
