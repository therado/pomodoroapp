<?php

namespace App\Entity;

use App\Repository\PomodoroSessionRepository;
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
}
