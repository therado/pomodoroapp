<?php

namespace App\Entity;

use App\Repository\PomodoroSessionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PomodoroSessionRepository::class)]
class PomodoroSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $sessionLength = null;

    #[ORM\Column]
    private ?int $breakLength = null;

    #[ORM\Column]
    private ?int $sessionCount = null;

    #[ORM\ManyToOne(inversedBy: 'pomodoroSessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSessionLength(): ?int
    {
        return $this->sessionLength;
    }

    public function setSessionLength(int $sessionLength): self
    {
        $this->sessionLength = $sessionLength;

        return $this;
    }

    public function getBreakLength(): ?int
    {
        return $this->breakLength;
    }

    public function setBreakLength(int $breakLength): self
    {
        $this->breakLength = $breakLength;

        return $this;
    }

    public function getSessionCount(): ?int
    {
        return $this->sessionCount;
    }

    public function setSessionCount(int $sessionCount): self
    {
        $this->sessionCount = $sessionCount;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }
}
