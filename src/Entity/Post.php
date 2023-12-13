<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'post:item']),
        new GetCollection(normalizationContext: ['groups' => 'post:list'])
    ],
    order: ['postId' => 'ASC'],
    paginationEnabled: false,
    routePrefix: '',
)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: false)]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['post:list', 'post:item'])]
    #[ApiProperty(identifier: true)]
    private ?int $postId = null;

    #[ORM\Column(length: 60)]
    #[Groups(['post:list', 'post:item'])]
    private ?string $userName = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['post:list', 'post:item'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['post:list', 'post:item'])]
    private ?string $body = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostId(): ?int
    {
        return $this->postId;
    }

    public function setPostId(int $postId): static
    {
        $this->postId = $postId;

        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): static
    {
        $this->userName = $userName;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): static
    {
        $this->body = $body;

        return $this;
    }
}
