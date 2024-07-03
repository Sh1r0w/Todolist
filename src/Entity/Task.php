<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

     #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt;


     #[ORM\Column(type: Types::TEXT)]
     #[Assert\NotBlank(message: 'Vous devez saisir un titre.')]
    private ?string $title;


     #[ORM\Column(type: Types::TEXT)]
     #[Assert\NotBlank(message: 'Vous devez saisir du contenu.')]
    private ?string $content;


     #[ORM\Column(type: Types::STRING)]
    private ?string $isDone;

     #[ORM\ManyToOne]
     #[ORM\JoinColumn(nullable: false)]
     private ?User $User = null;

    public function __construct()
    {
        $this->isDone = false;

    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function isDone(): ?bool
    {
        return $this->isDone;
    }

    public function toggle(?Bool $flag): void
    {
        $this->isDone = $flag;
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
}