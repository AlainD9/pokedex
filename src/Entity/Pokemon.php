<?php

namespace App\Entity;

use ApiPlatform\Metadata as Api;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Repository\PokemonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PokemonRepository::class)]
#[Api\ApiResource(normalizationContext:['groups' => ['read_pokemon']])]
class Pokemon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Api\ApiProperty(identifier: false)]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['read_pokemon'])]
    private ?string $name = null;

    #[ORM\Column(unique: true)]
    #[Groups(['read_pokemon'])]
    private ?int $number = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read_pokemon'])]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'pokemon')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Type $type = null;

    #[ORM\ManyToOne(inversedBy: 'pokemon')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Region $region = null;

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

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): static
    {
        $this->region = $region;

        return $this;
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
