<?php

namespace App\Service;

use League\Flysystem\FilesystemOperator;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Storage\StorageInterface;

class FlysystemStorageAdapter implements StorageInterface
{
  private FilesystemOperator $filesystem;

  public function __construct(FilesystemOperator $filesystem)
  {
    $this->filesystem = $filesystem;
  }

  public function upload(object $obj, PropertyMapping $mapping): void
  {
    $file = $mapping->getFile($obj);

    if ($file) {
      $path = uniqid() . '.' . $file->guessExtension();
      $stream = fopen($file->getPathname(), 'rb');
      $this->filesystem->writeStream($path, $stream);
      fclose($stream);

      $mapping->setFileName($obj, $path);
    }
  }

  public function remove(object $obj, PropertyMapping $mapping): ?bool
  {
    $fileName = $mapping->getFileName($obj);

    if ($fileName && $this->filesystem->fileExists($fileName)) {
      $this->filesystem->delete($fileName);
      return true;
    }

    return false;
  }

  public function resolvePath(object|array $obj, ?string $fieldName = null, ?string $className = null, ?bool $relative = false): ?string
  {
    $fileName = is_array($obj) ? $obj[$fieldName] ?? null : $obj->{$fieldName} ?? null;

    if (!$fileName) {
      return null;
    }

    return $relative ? $fileName : '/uploads/recipes/' . $fileName;
  }

  public function resolveUri(object|array $obj, ?string $fieldName = null, ?string $className = null): ?string
  {
    $fileName = is_array($obj) ? $obj[$fieldName] ?? null : $obj->{$fieldName} ?? null;

    if (!$fileName) {
      return null;
    }

    return '/uploads/recipes/' . $fileName;
  }

  public function resolveStream(object|array $obj, ?string $fieldName = null, ?string $className = null)
  {
    $fileName = is_array($obj) ? $obj[$fieldName] ?? null : $obj->{$fieldName} ?? null;

    if (!$fileName || !$this->filesystem->fileExists($fileName)) {
      return null;
    }

    return $this->filesystem->readStream($fileName);
  }
}
