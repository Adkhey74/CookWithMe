<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\UserLikeRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use App\Controller\UserLikeController;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserLikeRepository::class)]
#[ApiResource(
    paginationItemsPerPage: 6,
    operations: [
        new Post(
            uriTemplate: '/user_likes/toggle',
            controller: UserLikeController::class . '::toggleLike',
            denormalizationContext: ['groups' => ['like:write']],
            normalizationContext: ['groups' => ['like:read']],
            name: 'toggle_like'
        ),
        new Get(
            uriTemplate: '/user_likes',
            controller: UserLikeController::class . '::getLikedRecipes',
            normalizationContext: ['groups' => ['like:read']],
            name: 'get_user_likes'
        )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'user' => 'exact',
])]
class UserLike
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['like:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userLikes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['like:write', 'like:read'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userLikes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['like:write', 'like:read'])]
    private ?Recipe $recipe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

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
}
