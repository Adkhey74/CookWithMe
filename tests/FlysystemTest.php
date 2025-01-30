<?php

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
