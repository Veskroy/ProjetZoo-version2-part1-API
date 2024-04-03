<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\TicketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'date', 'price', 'type', 'user', 'event'], arguments: ['orderParameterName' => 'order'])]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'type' => 'partial', 'user' => 'exact', 'event' => 'exact'])]
#[ApiFilter(RangeFilter::class, properties: ['price'])]
#[ApiFilter(DateFilter::class, properties: ['date'])]
#[ApiResource]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(nullable: true)]
    private ?int $price = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Event::class, inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: true)]
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


        $this->setUser($user);
        $user->addTicket($this);
    }

}
