<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['email'], message: "Un autre utilisateur possède déjà cette adresse e-mail, merci de la modifier")]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Email(message: "Veuillez renseigner une adresse e-mail valide")]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: 'Votre mot de passe doit comporter au minimum {{ limit }} caractères',
        maxMessage: 'Votre mot de passe doit comporter au maximum {{ limit }} caractères',
    )]
    private ?string $password = null;

    #[Assert\EqualTo(propertyPath: "password", message: "Vous n'avez pas correctement confirmé votre mot de passe")]
    public ?string $passwordConfirm = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez renseigner votre prénom")]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez renseigner votre nom de famille")]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Image(mimeTypes: ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'], mimeTypesMessage: "Le type de l'image est invalide ({{ type }}). Les images acceptées sont {{ types }}.")]
    #[Assert\File(maxSize: "1024k", maxSizeMessage: "La taille du fichier est trop grande (maximum {{ limit }} {{ suffix }})")]
    private ?string $picture = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, length: 100, nullable: false)]
    #[Assert\Length(
        min: 20,
        max: 500,
        minMessage: 'Votre description doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'Votre description doit comporter au maximum {{ limit }} caractères.',
    )]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'seller', targetEntity: Advert::class, orphanRemoval: true)]
    private Collection $adverts;


    public function __construct()
    {
        $this->adverts = new ArrayCollection();
    }

    /**
     * Permet de créer un slug automatiquement avec le nom et prénom de l'utilisateur
     *
     * @return void
     */
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function initializeSlug(): void
    {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->firstName . ' ' . $this->lastName . ' ' . uniqid());
        }
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

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
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Advert>
     */
    public function getAdverts(): Collection
    {
        return $this->adverts;
    }

    public function addAdvert(Advert $advert): static
    {
        if (!$this->adverts->contains($advert)) {
            $this->adverts->add($advert);
            $advert->setSeller($this);
        }

        return $this;
    }

    public function removeAdvert(Advert $advert): static
    {
        if ($this->adverts->removeElement($advert)) {
            // set the owning side to null (unless already changed)
            if ($advert->getSeller() === $this) {
                $advert->setSeller(null);
            }
        }

        return $this;
    }

    public function getFullName() : string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
