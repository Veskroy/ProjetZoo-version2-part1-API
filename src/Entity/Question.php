<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\PublishQuestionController;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial', 'description' => 'partial', 'author' => 'exact'])]
#[ApiFilter(BooleanFilter::class, properties: ['isResolved'])]
#[UniqueEntity(fields: ['title'], message: 'Il existe déjà un sujet avec ce même titre.')]
#[UniqueEntity(fields: ['description'], message: 'Il existe déjà un sujet avec cette même description.')]
#[ApiResource(
    operations: [
        new GetCollection(
            openapiContext: [
                'summary' => 'Récupère la liste des questions',
            ],
            paginationItemsPerPage: 10,
            normalizationContext: [
                'groups' => ['question:read-list', 'user:read', 'user:read-list'],
            ],
        ),
        new Get(
            openapiContext: [
                'summary' => 'Récupère une seule question',
            ],
            normalizationContext: [
                'groups' => ['question:read-list', 'user:read', 'user:read-list'],
            ],
        ),
        new Patch(
            openapiContext: [
                'summary' => 'Modifie une question',
            ],
            normalizationContext: [
                'groups' => ['question:read'],
            ],
            denormalizationContext: [
                'groups' => ['question:write'],
            ],
            security: "is_granted('ROLE_USER') and object.getAuthor() === user or is_granted('ROLE_ADMIN') or is_granted('ROLE_EMPLOYEE')",
        ),
        new Post(
            controller: PublishQuestionController::class,
            openapiContext: [
                'summary' => 'Ajoute une question',
            ],
            normalizationContext: [
                'groups' => ['question:read'],
            ],
            denormalizationContext: [
                'groups' => ['question:write'],
            ],
            security: "is_granted('ROLE_USER')",
        ),
        new Delete(
            openapiContext: [
                'summary' => 'Supprime une question',
            ],
            security: "is_granted('ROLE_USER') and object.getAuthor() === user or is_granted('ROLE_ADMIN') or is_granted('ROLE_EMPLOYEE')",
        ),
    ],
    order: ['createdAt' => 'DESC'],
)]
class Question extends AbstractController
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['question:read', 'question:read-list'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['question:read', 'question:read-list', 'question:write'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['question:read', 'question:read-list', 'question:write'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['question:read', 'question:read-list'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['question:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['question:read', 'question:read-list', 'question:write'])]
    private ?User $author = null;

    #[ORM\Column]
    #[Groups(['question:read', 'question:read-list', 'question:write'])]
    private ?bool $isResolved = null;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Answer::class, cascade: ['remove'])]
    #[Groups(['question:read'])]
    private Collection $answers;

    #[ORM\ManyToMany(targetEntity: User::class)]
    #[ORM\JoinTable('user_question_like')]
    #[Groups(['question:read'])]
    private Collection $likes;

    public function __construct(/* User $author */)
    {
        $this->answers = new ArrayCollection();
        $this->likes = new ArrayCollection();

        /*$this->author = $author;
        $author->addQuestion($this);*/
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
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

    public function isIsResolved(): ?bool
    {
        return $this->isResolved;
    }

    public function setIsResolved(bool $isResolved): static
    {
        $this->isResolved = $isResolved;

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function countAnswers(): int
    {
        return $this->answers->count();
    }

    public function addAnswer(Answer $answer): static
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): static
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function countLikes(): int
    {
        return $this->likes->count();
    }

    public function addLike(User $user): static
    {
        if (!$this->likes->contains($user)) {
            $this->likes->add($user);
        }

        return $this;
    }

    public function removeLike(User $user): static
    {
        $this->likes->removeElement($user);

        return $this;
    }

    public function isLikedByUser(User $user): bool
    {
        return $this->likes->contains($user);
    }
}
