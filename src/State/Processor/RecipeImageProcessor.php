<?php

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\RecipeImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Handler\UploadHandler;

class RecipeImageProcessor implements ProcessorInterface
{
  private EntityManagerInterface $entityManager;
  private UploadHandler $uploadHandler;

  public function __construct(EntityManagerInterface $entityManager, UploadHandler $uploadHandler)
  {
    $this->entityManager = $entityManager;
    $this->uploadHandler = $uploadHandler;
  }

  public function process($data, Operation $operation, array $uriVariables = [], array $context = []): void
  {
    if (!$data instanceof RecipeImage) {
      return;
    }

    // Si un fichier est fourni, traitez-le avec VichUploader
    if ($data->file instanceof UploadedFile) {
      $this->uploadHandler->upload($data, 'file');
    }

    // Enregistrez l'entité
    $this->entityManager->persist($data);
    $this->entityManager->flush();
  }
}
