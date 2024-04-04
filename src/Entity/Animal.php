<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\AnimalRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/animals/all',
            openapiContext: [
                'summary' => 'Récupère la liste de tous les animaux',
            ],
            paginationItemsPerPage: 10,
            normalizationContext: [
                'groups' => ['animal:read-list'],
            ],
        )],
    order: ['name' => 'ASC'])]
#[ApiFilter(OrderFilter::class, properties: ['id', 'name', 'weight', 'size', 'birthDate', 'species', 'pen'], arguments: ['orderParameterName' => 'order'])]
#[ApiFilter(DateFilter::class, properties: ['birthDate'])]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'name' => 'partial', 'description' => 'partial', 'gender' => 'exact', 'species' => 'exact', 'pen' => 'exact'])]
#[ApiFilter(RangeFilter::class, properties: ['weight', 'size'])]

class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Groups(['animal:read-list'])]
    private ?string $name = null;

    #[ORM\Column(length: 1)]
    #[Groups(['animal:read-list'])]
    private ?string $gender = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['animal:read-list'])]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['animal:read-list'])]
    private ?float $weight = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['animal:read-list'])]
    private ?float $size = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\ManyToOne(inversedBy: 'animal')]
    private ?Species $species = null;

    #[ORM\ManyToOne(inversedBy: 'animal')]
    private ?Pen $pen = null;

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

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getSize(): ?float
    {
        return $this->size;
    }

    public function setSize(?float $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getSpecies(): ?Species
    {
        return $this->species;
    }

    public function setSpecies(?Species $species): static
    {
        $this->species = $species;

        return $this;
    }

    public function getPen(): ?Pen
    {
        return $this->pen;
    }

    public function setPen(?Pen $pen): static
    {
        $this->pen = $pen;

        return $this;
    }
}
