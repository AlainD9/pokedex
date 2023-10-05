<?php

namespace App\Entity;

use ApiPlatform\Metadata as Api;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RegionRepository::class)]
#[Api\ApiResource(normalizationContext:['groups' => ['read_region']] )]
class Region
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Api\ApiProperty(identifier: false)]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['read_region'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'region', targetEntity: Pokemon::class, orphanRemoval: true)]
    #[Groups(['read_type'])]
    private Collection $pokemon;

    #[ORM\Column(length: 255, unique: true)]    
    #[Api\ApiProperty(identifier: true)]
    #[Gedmo\Slug(
        fields: ['name'],
        style: 'lower',
        separator: '_',
        updatable: true,
        unique: true,
    )]
    private ?string $slug = null;

    public function __construct()
    {
        $this->pokemon = new ArrayCollection();
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
     * @return Collection<int, Pokemon>
     */
    public function getPokemon(): Collection
    {
        return $this->pokemon;
    }

    public function addPokemon(Pokemon $pokemon): static
    {
        if (!$this->pokemon->contains($pokemon)) {
            $this->pokemon->add($pokemon);
            $pokemon->setRegion($this);
        }

        return $this;
    }

    public function removePokemon(Pokemon $pokemon): static
    {
        if ($this->pokemon->removeElement($pokemon)) {
            if ($pokemon->getRegion() === $this) {
                $pokemon->setRegion(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName(); 
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

}