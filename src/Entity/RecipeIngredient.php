<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\RecipeIngredientRepository;
use Symfony\Component\Serializer\Annotation\Groups;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeIngredientRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['recipe_ingredient:read']],
    denormalizationContext: ['groups' => ['recipe_ingredient:write']],
)]
#[ApiFilter(SearchFilter::class, properties: [
    'recipe' => 'exact'
])]
class RecipeIngredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['recipe_ingredient:read', 'recipe_ingredient:write', 'recipe:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['recipe_ingredient:read', 'recipe_ingredient:write', 'recipe:read'])]
    private ?int $quantity = null;

    #[ORM\Column(length: 255)]
    #[Groups(['recipe_ingredient:read', 'recipe_ingredient:write', 'recipe:read'])]
    private ?string $unit = null;

    #[ORM\ManyToOne(inversedBy: 'recipe_ingredient')]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiProperty(readableLink: true, writableLink: true)]
    #[Groups(['recipe_ingredient:read', 'recipe_ingredient:write'])]
    private ?Recipe $recipe = null;

    #[ORM\ManyToOne(inversedBy: 'ingredient_recipe')]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiProperty(readableLink: true, writableLink: true)]
    #[Groups(['recipe_ingredient:read', 'recipe_ingredient:write', 'recipe:read'])]
    private ?Ingredient $ingredient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): static
    {
        $this->unit = $unit;

        return $this;
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

    public function getIngredient(): ?Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(?Ingredient $ingredient): static
    {
        $this->ingredient = $ingredient;

        return $this;
    }
}
