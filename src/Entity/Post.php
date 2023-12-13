<?php

namespace App\Entity;

use App\Repository\PostsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostsRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(unique: true)]
    private ?int $PostId = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Body = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostId(): ?int
    {
        return $this->PostId;
    }

    public function setPostId(int $PostId): static
    {
        $this->PostId = $PostId;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): static
    {
        $this->Title = $Title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->Body;
    }

    public function setBody(string $Body): static
    {
        $this->Body = $Body;

        return $this;
    }
}
