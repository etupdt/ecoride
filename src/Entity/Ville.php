<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VilleRepository::class)]
class Ville
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, Covoiturage>
     */
    #[ORM\OneToMany(targetEntity: Covoiturage::class, mappedBy: 'lieu_depart')]
    private Collection $covoiturages_depart;

    /**
     * @var Collection<int, Covoiturage>
     */
    #[ORM\OneToMany(targetEntity: Covoiturage::class, mappedBy: 'lieu_arrivee')]
    private Collection $covoiturages_arrivee;

    public function __construct()
    {
        $this->covoiturages_depart = new ArrayCollection();
        $this->covoiturages_arrivee = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function addCovoiturage_depart(Covoiturage $covoiturages_depart): static
    {
        if (!$this->covoiturages_depart->contains($covoiturages_depart)) {
            $this->covoiturages_depart->add($covoiturages_depart);
            $covoiturages_depart->setLieuDepart($this);
        }

        return $this;
    }

    public function removeCovoiturage_depart(Covoiturage $covoiturages_depart): static
    {
        if ($this->covoiturages_depart->removeElement($covoiturages_depart)) {
            // set the owning side to null (unless already changed)
            if ($covoiturages_depart->getLieuDepart() === $this) {
                $covoiturages_depart->setLieuDepart(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Covoiturage>
     */
    public function getCovoiturages_arrivee(): Collection
    {
        return $this->covoiturages_depart;
    }

    public function addCovoiturage_arrivee(Covoiturage $covoiturages_depart): static
    {
        if (!$this->covoiturages_depart->contains($covoiturages_depart)) {
            $this->covoiturages_depart->add($covoiturages_depart);
            $covoiturages_depart->setLieuDepart($this);
        }

        return $this;
    }

    public function removeCovoiturage_arrivee(Covoiturage $getCovoiturages_arrivee): static
    {
        if ($this->covoiturages_depart->removeElement($getCovoiturages_arrivee)) {
            // set the owning side to null (unless already changed)
            if ($getCovoiturages_arrivee->getLieuArrivee() === $this) {
                $getCovoiturages_arrivee->setLieuArrivee(null);
            }
        }

        return $this;
    }

}
