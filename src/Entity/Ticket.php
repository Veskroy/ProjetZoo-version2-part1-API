<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
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
use App\Repository\TicketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'date', 'price', 'type', 'user', 'event'], arguments: ['orderParameterName' => 'order'])]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'type' => 'partial', 'user' => 'exact', 'event' => 'exact'])]
#[ApiFilter(RangeFilter::class, properties: ['price'])]
#[ApiFilter(DateFilter::class, properties: ['date'])]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/tickets',
            openapiContext: [
                'summary' => 'Récupère la liste des tickets',
            ],
            paginationItemsPerPage: 10,
            normalizationContext: [
                'groups' => ['ticket:read-list', 'event:read', 'user:read', 'user:read-list'],
            ],
        ),

        new Get(
            uriTemplate: '/tickets/{id}',
            openapiContext: [
                'summary' => 'Récupère un seul ticket',
            ],
            normalizationContext: [
                'groups' => ['ticket:read-list', 'event:read', 'user:read', 'user:read-list'],
            ],
        ),

        new Patch(
            uriTemplate: '/tickets/{id}',
            openapiContext: [
                'summary' => 'Modifie un ticket',
            ],
            normalizationContext: [
                'groups' => ['ticket:read'],
            ],
            denormalizationContext: [
                'groups' => ['ticket:write'],
            ],
            security: "is_granted('ROLE_USER') and object.getUser() === user or is_granted('ROLE_ADMIN') or is_granted('ROLE_EMPLOYEE')",
        ),

        new Post(
            uriTemplate: '/tickets/new',
            openapiContext: [
                'summary' => 'Ajoute un ticket',
            ],
            normalizationContext: [
                'groups' => ['ticket:read'],
            ],
            denormalizationContext: [
                'groups' => ['ticket:write'],
            ],
            security: "is_granted('ROLE_USER') or is_granted('ROLE_ADMIN') or is_granted('ROLE_EMPLOYEE')",
        ),
        new Delete(
            uriTemplate: '/tickets/{id}',
            openapiContext: [
                'summary' => 'Supprime un ticket',
            ],
            security: "is_granted('ROLE_USER') and object.getUser() === user or is_granted('ROLE_ADMIN') or is_granted('ROLE_EMPLOYEE')",
        ),
    ],


)] class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['ticket:read-list'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['ticket:read', 'ticket:write','ticket:read-list'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['ticket:read', 'ticket:write','ticket:read-list'])]
    private ?int $price = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['ticket:read', 'ticket:write','ticket:read-list'])]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['ticket:read', 'ticket:write'])]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Event::class, inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['ticket:read'])]
    private Collection $event;

    public function __construct()
    {
        $this->event = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $Date): static
    {
        $this->date = $Date;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $Price): static
    {
        // les prix possible
        $all_price = [20, 12, 15, 16, 0, 14];

        if (in_array($Price, $all_price)) {
            $this->price = $Price;
        } else {
            $this->price = null;
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $Type): static
    {
        // les Types de ticket possible
        $all_type = ['ENFANT', 'ETUDIANT', 'SENIOR', 'JUNIOR', 'HANDICAPE', null, 'CLASSIC'];
        if (in_array($Type, $all_type)) {
            $this->type = $Type;
        } else {
            $this->type = null;
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvent(): Collection
    {
        return $this->event;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->event->contains($event)) {
            $this->event->add($event);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        $this->event->removeElement($event);

        return $this;
    }

    public function reserve(User $user): void
    {
        if ($user === $this->getUser()) {
            throw new \LogicException("Le ticket est déjà réservé par l'utilisateur actuel.");
        }

        if ($this->getDate() < new \DateTimeImmutable()) {
            throw new \LogicException('La date du ticket est dans le passé, il est donc impossible de le réserver.');
        }

        if ($this->isReserved()) {
            throw new \LogicException('Le ticket est déjà réservé.');
        }

        $this->setUser($user);
        $user->addTicket($this);
    }

    private function isReserved(): bool
    {
        return null !== $this->getUser();
    }
}
