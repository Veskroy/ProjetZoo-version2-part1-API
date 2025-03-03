<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\EditAnswerController;
use App\Controller\PublishAnswerController;
use App\Repository\AnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AnswerRepository::class)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'author', 'createdAt', 'updatedAt', 'description', 'question'], arguments: ['orderParameterName' => 'order'])]
#[ApiFilter(DateFilter::class, properties: ['createdAt', 'updatedAt'])]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'author' => 'exact', 'description' => 'partial', 'question' => 'exact'])]
#[ApiResource(operations: [
    new GetCollection(
        uriTemplate: '/questions/{questionId}/answers',
        uriVariables: [
            'questionId' => new Link(fromProperty: 'id', toProperty: 'question', fromClass: Question::class),
        ],
        openapiContext: [
            'summary' => 'Récupère la liste des réponses selon une question',
        ],
        paginationItemsPerPage: 10,
        normalizationContext: [
            'groups' => ['question:read-list', 'user:read', 'user:read-list', 'answer:read-list'],
        ],
    ),
    new Get(
        uriTemplate: '/answers/{id}',
        openapiContext: [
            'summary' => 'Récupère une seule réponse',
        ],
        normalizationContext: [
            'groups' => ['answer:read', 'question:read', 'user:read', 'user:read-list'],
        ],
    ),
    new Patch(
        uriTemplate: '/answers/{id}',
        controller: EditAnswerController::class,
        openapiContext: [
            'summary' => 'Modifie une réponse',
        ],
        normalizationContext: [
            'groups' => ['answer:read'],
        ],
        denormalizationContext: [
            'groups' => ['answer:write'],
        ],
        security: "is_granted('ROLE_USER') and object.getAuthor() === user or is_granted('ROLE_ADMIN') or is_granted('ROLE_EMPLOYEE')",
    ),
    new Post(
        uriTemplate: '/answers/new',
        controller: PublishAnswerController::class,
        openapiContext: ['summary' => 'Ajouter une nouvelle réponse'],
        normalizationContext: [
            'groups' => ['answer:read'],
        ],
        denormalizationContext: [
            'groups' => ['answer:create'],
        ],
        security: "is_granted('ROLE_USER') or is_granted('ROLE_ADMIN') or is_granted('ROLE_EMPLOYEE')",
    ),
    new Delete(
        openapiContext: [
            'summary' => 'Supprime une réponse',
        ],
        security: "is_granted('ROLE_USER') and object.getAuthor() === user or is_granted('ROLE_ADMIN') or is_granted('ROLE_EMPLOYEE')",
    ),
])]
class Answer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['answer:read', 'answer:read-list'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['answer:read', 'answer:read-list', 'answer:write', 'answer:create'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['answer:read', 'answer:read-list'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['answer:read', 'answer:read-list'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['answer:read', 'answer:read-list'])]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['answer:create'])]
    private ?Question $question = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }
}
