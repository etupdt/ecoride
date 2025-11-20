<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use App\Repository\AvisRepository;
use App\Entity\User;

#[MongoDB\Document(repositoryClass: AvisRepository::class)]
class Avis
{
    #[MongoDB\Id]
    protected string $id;

    #[MongoDB\Field(type: 'int')]
    protected int $chauffeur;

    #[MongoDB\Field(type: 'string')]
    protected string $avis;

    #[MongoDB\Field(type: 'string')]
    protected string $modere;

    #[MongoDB\Field(type: 'string')]
    protected string $valide;

    #[MongoDB\Field(type: 'int')]
    protected int $note;

    #[MongoDB\Field(type: 'string')]
    protected string $pseudo;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getChauffeur(): ?int
    {
        return $this->chauffeur;
    }

    public function setChauffeur(int $chauffeur): static
    {
        $this->chauffeur = $chauffeur;

        return $this;
    }

    public function getAvis(): ?string
    {
        return $this->avis;
    }

    public function setAvis(string $avis): static
    {
        $this->avis = $avis;

        return $this;
    }

    public function getModere(): ?bool
    {
        return $this->modere;
    }

    public function setModere(bool $modere): static
    {
        $this->modere = $modere;

        return $this;
    }

    public function getValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(bool $valide): static
    {
        $this->valide = $valide;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

}
