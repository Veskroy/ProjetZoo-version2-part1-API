<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Repository\UserRepository;
use App\State\MeProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: 'email', message: 'Il y a déjà un compte associé à cette adresse e-mail.')]
#[ORM\Table(name: '`user`')]
#[ApiResource(operations: [
    new Get(
        uriTemplate: '/me',
        openapiContext: [
            'summary' => 'Récupère l\'utilisateur connecté',
            'description' => 'Récupère l\'utilisateur connecté',
        ],
        provider: MeProvider::class,
    ),
])]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read', 'user:read-list'])]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    #[Assert\NotBlank()]
    #[Assert\Length(['max' => 100])]
    #[Assert\Email(['message' => "'{{ value }}' n'est pas une adresse mail valide."])]
    #[Groups(['user:read', 'user:read-list'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['user:read', 'user:read-list'])]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    #[Groups(['user:read', 'user:read-list'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    #[Groups(['user:read', 'user:read-list'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Regex(pattern: '/^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4})$/', message: 'Format de téléphone invalide')]
    #[Groups(['user:read', 'user:read-list'])]
    private ?string $phone = null;

    #[ORM\Column(length: 5, nullable: true)]
    #[Groups(['user:read', 'user:read-list'])]
    private ?string $pc = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:read', 'user:read-list'])]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:read', 'user:read-list'])]
    private ?string $address = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    #[Groups(['user:read', 'user:read-list'])]
    private $avatar;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Question::class, orphanRemoval: true)]
    private Collection $questions;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Answer::class, orphanRemoval: true)]
    private Collection $answers;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Ticket::class)]
    private Collection $tickets;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->answers = new ArrayCollection();
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPc(): ?string
    {
        return $this->pc;
    }

    public function setPc(?string $pc): static
    {
        $this->pc = $pc;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getRoles(): array
    {
        // guarantee every user at least has ROLE_USER
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return $this->roles;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        // TODO: Implement getUserIdentifier() method.

        return $this->email;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setAuthor($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getAuthor() === $this) {
                $question->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): static
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setAuthor($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): static
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getAuthor() === $this) {
                $answer->setAuthor(null);
            }
        }

        return $this;
    }

    public function isAdmin(): bool
    {
        return in_array('ROLE_ADMIN', $this->getRoles());
    }

    public function isEmployee(): bool
    {
        return in_array('ROLE_EMPLOYEE', $this->getRoles());
    }

    public function toString(): string
    {
        return $this->getFirstName().' '.$this->getLastName();
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
            $ticket->setUser($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): static
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getUser() === $this) {
                $ticket->setUser(null);
            }
        }

        return $this;
    }
}
