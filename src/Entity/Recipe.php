<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Controller\RecipeController;
use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\TopLikedRecipesController;
use App\Controller\UserLikeController;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['recipe:read']],
    denormalizationContext: ['groups' => ['recipe:write']],
    paginationItemsPerPage: 6,
    operations: [
        new GetCollection(),
        new Get(
            uriTemplate: '/recipes/{id}',
            controller: UserLikeController::class . '::getRecipeWithLikeInfo',
        ),
        new Post(),
        new Patch(),
        new Get(
            uriTemplate: '/top-liked-recipes',
            controller: TopLikedRecipesController::class,
            outputFormats: ['json' => ['application/json']],
        ),
    ],
)]
#[ApiFilter(SearchFilter::class, properties: [
    'name' => 'partial',
    'category' => 'exact',
    'author' => 'exact'
])]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['recipe:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['recipe:read', 'recipe:write'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['recipe:read', 'recipe:write'])]
    private ?int $nbLikes = null;

    #[ORM\Column]
    #[Groups(['recipe:read', 'recipe:write'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['recipe:read', 'recipe:write'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[Groups(['recipe:read'])]
    private ?bool $isLikedByCurrentUser = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiProperty(readableLink: true, writableLink: true)]
    #[Groups(['recipe:read', 'recipe:write'])]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiProperty(readableLink: false, writableLink: true)]
    #[Groups(['recipe:write'])]
    private ?User $author = null;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'recipe', orphanRemoval: true)]
    private Collection $reviews;

    /**
     * @var Collection<int, RecipeIngredient>
     */
    #[ORM\OneToMany(targetEntity: RecipeIngredient::class, mappedBy: 'recipe', orphanRemoval: true)]
    #[Groups(['recipe:read'])]
    private Collection $recipe_ingredient;

    /**
     * @var Collection<int, Step>
     */
    #[ORM\OneToMany(targetEntity: Step::class, mappedBy: 'recipeId', cascade: ['persist'], orphanRemoval: true)]
    #[Groups(['recipe:read', 'recipe:write'])]
    private Collection $steps;

    /**
     * @var Collection<int, UserLike>
     */
    #[ORM\OneToMany(targetEntity: UserLike::class, mappedBy: 'recipe')]
    private Collection $userLikes;

    /**
     * @var Collection<int, RecipeImage>
     */
    #[ORM\OneToMany(targetEntity: RecipeImage::class, mappedBy: 'recipe', orphanRemoval: true)]
    #[Groups(['recipe:read', 'recipe:write'])]
    private Collection $recipeImages;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->recipe_ingredient = new ArrayCollection();
        $this->steps = new ArrayCollection();
        $this->userLikes = new ArrayCollection();
        $this->recipeImages = new ArrayCollection();
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

    public function getNbLikes(): ?int
    {
        return $this->nbLikes;
    }

    public function setNbLikes(int $nbLikes): static
    {
        $this->nbLikes = $nbLikes;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

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


    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setRecipe($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getRecipe() === $this) {
                $review->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RecipeIngredient>
     */
    public function getRecipeIngredient(): Collection
    {
        return $this->recipe_ingredient;
    }

    public function addRecipeIngredient(RecipeIngredient $recipeIngredient): static
    {
        if (!$this->recipe_ingredient->contains($recipeIngredient)) {
            $this->recipe_ingredient->add($recipeIngredient);
            $recipeIngredient->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): static
    {
        if ($this->recipe_ingredient->removeElement($recipeIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeIngredient->getRecipe() === $this) {
                $recipeIngredient->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Step>
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(Step $step): static
    {
        if (!$this->steps->contains($step)) {
            $this->steps->add($step);
            $step->setRecipeId($this);
        }

        return $this;
    }

    public function removeStep(Step $step): static
    {
        if ($this->steps->removeElement($step)) {
            // set the owning side to null (unless already changed)
            if ($step->getRecipeId() === $this) {
                $step->setRecipeId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserLike>
     */
    public function getUserLikes(): Collection
    {
        return $this->userLikes;
    }

    public function addUserLike(UserLike $userLike): static
    {
        if (!$this->userLikes->contains($userLike)) {
            $this->userLikes->add($userLike);
            $userLike->setRecipe($this);
        }

        return $this;
    }

    public function removeUserLike(UserLike $userLike): static
    {
        if ($this->userLikes->removeElement($userLike)) {
            // set the owning side to null (unless already changed)
            if ($userLike->getRecipe() === $this) {
                $userLike->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RecipeImage>
     */
    public function getRecipeImages(): Collection
    {
        return $this->recipeImages;
    }

    public function addRecipeImage(RecipeImage $recipeImage): static
    {
        if (!$this->recipeImages->contains($recipeImage)) {
            $this->recipeImages->add($recipeImage);
            $recipeImage->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeImage(RecipeImage $recipeImage): static
    {
        if ($this->recipeImages->removeElement($recipeImage)) {
            // set the owning side to null (unless already changed)
            if ($recipeImage->getRecipe() === $this) {
                $recipeImage->setRecipe(null);
            }
        }

        return $this;
    }

    public function getIsLikedByCurrentUser(): ?bool
    {
        return $this->isLikedByCurrentUser;
    }

    public function setIsLikedByCurrentUser(?bool $isLikedByCurrentUser): static
    {
        $this->isLikedByCurrentUser = $isLikedByCurrentUser;

        return $this;
    }
}
