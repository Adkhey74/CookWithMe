<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RecipeIngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeIngredientRepository::class)]
#[ApiResource]
class RecipeIngredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Recipe>
     */
    #[ORM\OneToMany(targetEntity: Recipe::class, mappedBy: 'recipeIngredient')]
    private Collection $recipe_id;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(length: 255)]
    private ?string $unit = null;

    /**
     * @var Collection<int, Ingredient>
     */
    #[ORM\OneToMany(targetEntity: Ingredient::class, mappedBy: 'recipeIngredient')]
    private Collection $ingredient_id;

    public function __construct()
    {
        $this->recipe_id = new ArrayCollection();
        $this->ingredient_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecipeId(): Collection
    {
        return $this->recipe_id;
    }

    public function addRecipeId(Recipe $recipeId): static
    {
        if (!$this->recipe_id->contains($recipeId)) {
            $this->recipe_id->add($recipeId);
            $recipeId->setRecipeIngredient($this);
        }

        return $this;
    }

    public function removeRecipeId(Recipe $recipeId): static
    {
        if ($this->recipe_id->removeElement($recipeId)) {
            // set the owning side to null (unless already changed)
            if ($recipeId->getRecipeIngredient() === $this) {
                $recipeId->setRecipeIngredient(null);
            }
        }

        return $this;
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

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredientId(): Collection
    {
        return $this->ingredient_id;
    }

    public function addIngredientId(Ingredient $ingredientId): static
    {
        if (!$this->ingredient_id->contains($ingredientId)) {
            $this->ingredient_id->add($ingredientId);
            $ingredientId->setRecipeIngredient($this);
        }

        return $this;
    }

    public function removeIngredientId(Ingredient $ingredientId): static
    {
        if ($this->ingredient_id->removeElement($ingredientId)) {
            // set the owning side to null (unless already changed)
            if ($ingredientId->getRecipeIngredient() === $this) {
                $ingredientId->setRecipeIngredient(null);
            }
        }

        return $this;
    }
}
