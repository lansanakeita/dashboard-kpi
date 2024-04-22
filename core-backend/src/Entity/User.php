<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\UserRepository;
use App\Model\ErrorResponse;
use Doctrine\ORM\Mapping as ORM;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\DiscriminatorMap(["customer" => Customer::class, "admin" => User::class])]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: [
                'groups' => ['user:read', 'user:read:item'],
            ],
            security: "is_granted('ROLE_ADMIN') or object == user",
            exceptionToStatus: [
                404 => ['description' => 'Resource not found', 'class' => ErrorResponse::class]
            ],
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['user:read']],
            security: "is_granted('ROLE_ADMIN')",
            exceptionToStatus: [
                400 => ['description' => 'Invalid query parameters', 'class' => ErrorResponse::class]
            ],
        ),
        new Post(
            validationContext: ['groups' => ['user:create']],
            denormalizationContext: ['groups' => ['user:write']],
            exceptionToStatus: [
                400 => ['description' => 'Invalid input', 'class' => ErrorResponse::class],
                404 => ['description' => 'Resource not found', 'class' => ErrorResponse::class],
                422 => ['description' => 'Unprocessable Entity', 'class' => ErrorResponse::class],
            ],
        ),
        new Patch(
            security: "is_granted('ROLE_ADMIN') or object == user",
            denormalizationContext: ['groups' => ['user:write']],
            exceptionToStatus: [
                400 => ['description' => 'Invalid input', 'class' => ErrorResponse::class],
                404 => ['description' => 'Resource not found', 'class' => ErrorResponse::class],
                422 => ['description' => 'Unprocessable Entity', 'class' => ErrorResponse::class],
            ],
        ),
        new Delete(
            exceptionToStatus: [
                404 => ['description' => 'Resource not found', 'class' => ErrorResponse::class],
            ],
        ),
    ],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:create', 'user:update']],
    paginationItemsPerPage: 30,
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface, JWTUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email]
    #[Assert\NotBlank]
    #[Groups(['user:read', 'user:write'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['user:read', 'user:write'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(groups: ['user:create'])]
    #[Groups(['user:write'])]
    private ?string $password = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(groups: ['user:create', 'user:write'])]
    #[Groups(['user:read', 'user:write'])]
    private ?string $username = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->getEmail();
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_CLIENT
        $roles[] = 'ROLE_CLIENT';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        #$this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public static function createFromPayload($username, array $payload): self
    {
        return (new self())
            ->setId($username)
            ->setRoles($payload['roles']);
    }
}
