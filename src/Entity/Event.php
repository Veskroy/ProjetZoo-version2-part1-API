<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $hstart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $hend = null;

    #[ORM\Column(nullable: true)]
    private ?int $maxiNumPlace = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Ticket::class, mappedBy: 'event')]
    private Collection $tickets;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $Name): static
    {
        $this->name = $Name;

        return $this;
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

    public function getHstart(): ?\DateTimeInterface
    {
        return $this->hstart;
    }

    public function setHstart(?\DateTimeInterface $Hstart): static
    {
        $this->hstart = $Hstart;

        return $this;
    }

    public function getHend(): ?\DateTimeInterface
    {
        return $this->hend;
    }

    public function setHend(?\DateTimeInterface $Hend): static
    {
        $this->hend = $Hend;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }



    public function setDescription(?string $Description): static
    {
        $this->description = $Description;

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): static
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->addEvent($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): static
    {
        if ($this->tickets->removeElement($ticket)) {
            $ticket->removeEvent($this);
        }

        return $this;
    }


    /**
     * @return int|null
     */
    public function getMaxiNumPlace(): ?int
    {
        return $this->maxiNumPlace;
    }

    /**
     * @param int|null $maxiNumPlace
     */
    public function setMaxiNumPlace(?int $maxiNumPlace): void
    {
        $this->maxiNumPlace = $maxiNumPlace;
    }
}
