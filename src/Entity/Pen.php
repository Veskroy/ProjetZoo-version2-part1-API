<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\EnclosuresWithAnimalsController;
use App\Repository\PenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PenRepository::class)]
#[ApiResource(
    operations: [
        New GetCollection(
            uriTemplate: '/pen/{id}',
            controller: EnclosuresWithAnimalsController::class,
            openapiContext: [
                'summary' => 'Liste les animaux par enclos',
            ],
            normalizationContext: [
                'groups' => ['pen:read'],
            ],
            security: "is_granted('ROLE_USER') and object.getAuthor() === user or is_granted('ROLE_ADMIN') or is_granted('ROLE_EMPLOYEE')",
        ),
        new Get(
            uriTemplate: '/pen/{id}',
            openapiContext: [
                'summary' => 'Récupère un seul enclo',
            ],
            normalizationContext: [
                'groups' => ['pen:read', 'animal:read', 'spot:read'],
            ],
        ),
        new Patch(
            uriTemplate: '/pen/{id}',
            controller: EditPenController::class,
            openapiContext: [
                'summary' => 'Modifie un enclos',
            ],
            normalizationContext: [
                'groups' => ['pen:read'],
            ],
            denormalizationContext: [
                'groups' => ['pen:write'],
            ],
            security: "is_granted('ROLE_USER') or is_granted('ROLE_ADMIN') or is_granted('ROLE_EMPLOYEE')",
        ),
        new Post(
            uriTemplate: '/pen/new',
            controller: PublishPenController::class,
            openapiContext: ['summary' => 'Ajouter un nouvel enclo'],
            normalizationContext: [
                'groups' => ['pen:read'],
            ],
            denormalizationContext: [
                'groups' => ['pen:create'],
            ],
            security: "is_granted('ROLE_USER') or is_granted('ROLE_ADMIN') or is_granted('ROLE_EMPLOYEE')",
        ),
        new Delete(
            uriTemplate: '/pen/{id}',
            openapiContext: [
                'summary' => 'Supprime un enclo',
            ],
            security: "is_granted('ROLE_USER') or is_granted('ROLE_ADMIN') or is_granted('ROLE_EMPLOYEE')",
        ),
    ],
)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'type', 'capacity', 'size', 'animal', 'spot'], arguments: ['orderParameterName' => 'order'])]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'capacity' => 'exact', 'type' => 'partial', 'animal' => 'exact', 'spot' => 'exact'])]
#[ApiFilter(RangeFilter::class, properties: ['size'])]
class Pen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $capacity = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    private ?float $size = null;

    #[ORM\OneToMany(mappedBy: 'pen', targetEntity: Animal::class)]
    private Collection $animal;

    #[ORM\ManyToOne(inversedBy: 'pens')]
    private ?Spot $spot = null;

    public function __construct()
    {
        $this->animal = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(?int $capacity): static
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

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

    /**
     * @return Collection<int, Animal>
     */
    public function getAnimal(): Collection
    {
        return $this->animal;
    }

    public function addAnimal(Animal $animal): static
    {
        if (!$this->animal->contains($animal)) {
            $this->animal->add($animal);
            $animal->setPen($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): static
    {
        if ($this->animal->removeElement($animal)) {
            // set the owning side to null (unless already changed)
            if ($animal->getPen() === $this) {
                $animal->setPen(null);
            }
        }

        return $this;
    }

    public function getSpot(): ?Spot
    {
        return $this->spot;
    }

    public function setSpot(?Spot $spot): static
    {
        $this->spot = $spot;

        return $this;
    }
}
