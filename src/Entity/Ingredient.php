<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
#[ApiResource]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['ingredient:read', 'recipe_ingredient:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['ingredient:read', 'recipe_ingredient:read'])]
    private ?string $name = null;

    /**
     * @var Collection<int, RecipeIngredient>
     */
    #[ORM\OneToMany(targetEntity: RecipeIngredient::class, mappedBy: 'ingredient', orphanRemoval: true)]
    private Collection $ingredient_recipe;

    public function __construct()
    {
        $this->ingredient_recipe = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, RecipeIngredient>
     */
    public function getIngredientRecipe(): Collection
    {
        return $this->ingredient_recipe;
    }

    public function addIngredientRecipe(RecipeIngredient $ingredientRecipe): static
    {
        if (!$this->ingredient_recipe->contains($ingredientRecipe)) {
            $this->ingredient_recipe->add($ingredientRecipe);
            $ingredientRecipe->setIngredient($this);
        }

        return $this;
    }

    public function removeIngredientRecipe(RecipeIngredient $ingredientRecipe): static
    {
        if ($this->ingredient_recipe->removeElement($ingredientRecipe)) {
            // set the owning side to null (unless already changed)
            if ($ingredientRecipe->getIngredient() === $this) {
                $ingredientRecipe->setIngredient(null);
            }
        }

        return $this;
    }
}
