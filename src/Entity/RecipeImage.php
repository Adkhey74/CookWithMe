<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use App\Repository\RecipeImageRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecipeImageRepository::class)]
#[Vich\Uploadable]
#[ApiResource(
    normalizationContext: ['groups' => ['recipe_image:read']],
    denormalizationContext: ['groups' => ['recipe_image:write']],
    operations: [
        new Post(
            outputFormats: ['jsonld' => ['application/ld+json']],
            inputFormats: ['multipart' => ['multipart/form-data']],
        ),
        new Get()
    ],
)]
class RecipeImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['recipe_image:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'recipeImages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['recipe_image:write', 'recipe_image:read'])]
    private ?Recipe $recipe = null;

    #[Groups(['recipe_image:read'])]
    public ?string $url = null;

    #[Vich\UploadableField(mapping: 'recipe_images', fileNameProperty: 'filePath')]
    #[Assert\NotNull, Assert\Image]
    #[Groups(['recipe_image:write', 'recipe:read'])]
    public ?File $file = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['recipe:read'])]
    public ?string $filePath = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): static
    {
        $this->recipe = $recipe;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
