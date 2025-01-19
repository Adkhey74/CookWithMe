<?php

namespace App\Service;

use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\File;

class FileUploader
{
  private FilesystemOperator $recipeImagesStorage;

  public function __construct(FilesystemOperator $recipeImagesStorage)
  {
    $this->recipeImagesStorage = $recipeImagesStorage;
  }

  public function upload(File $file): string
  {
    $stream = fopen($file->getPathname(), 'r');
    $path = uniqid() . '.' . $file->guessExtension();

    $this->recipeImagesStorage->writeStream($path, $stream);

    if (is_resource($stream)) {
      fclose($stream);
    }

    return $path; // Retourne le chemin où le fichier est stocké
  }

  public function delete(string $path): void
  {
    $this->recipeImagesStorage->delete($path);
  }
}
