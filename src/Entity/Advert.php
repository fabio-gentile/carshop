<?php

namespace App\Entity;

use App\Repository\AdvertRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AdvertRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Advert
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'La marque doit faire au minimum {{ limit }} caractères',
        maxMessage: 'La marque doit faire au maximum {{ limit }} caractères',
    )]
    private ?string $brand = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Le modèle doit faire au minimum {{ limit }} caractères',
        maxMessage: 'Le modèle doit faire au maximum {{ limit }} caractères',
    )]
    private ?string $model = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: 'Le titre doit faire au minimum {{ limit }} caractères',
        maxMessage: 'Le titre doit faire au maximum {{ limit }} caractères',
    )]
    private ?string $title = null;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(500, message: 'Le prix doit être supérieur à {{ compared_value }}')]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    #[Assert\Positive]
    private ?int $kilometers = null;

    #[ORM\Column(length: 255)]
    #[Assert\Url(message: 'Il faut une URL valide')]
    private ?string $coverImage = null;

    #[ORM\Column]
    #[Assert\Positive]
    private ?int $totalOwners = null;

    #[ORM\Column]
    #[Assert\Positive]
    private ?float $EngineDisplacement = null;

    #[ORM\Column]
    #[Assert\Positive]
    private ?float $power = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Le type de carburant doit faire au minimum {{ limit }} caractères',
        maxMessage: 'Le type de carburant doit faire au maximum {{ limit }} caractères',
    )]
    private ?string $fuelType = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Le type de transmission doit faire au minimum {{ limit }} caractères',
        maxMessage: 'Le type de transmission doit faire au maximum {{ limit }} caractères',
    )]
    private ?string $transmission = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(
        min: 20,
        max: 500,
        minMessage: 'Votre description doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'Votre description doit comporter au maximum {{ limit }} caractères.',
    )]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(
        max: 500,
        maxMessage: 'Votre description doit comporter au maximum {{ limit }} caractères.',
    )]
    private ?string $car_options = null;

    #[ORM\ManyToOne(inversedBy: 'adverts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $seller = null;

    #[ORM\OneToMany(mappedBy: 'advert', targetEntity: AdvertImage::class, orphanRemoval: true)]
    #[Assert\Valid]
    private Collection $advertImages;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\Date]
    private ?\DateTimeInterface $yearOfRegistration = null;

    public function __construct()
    {
        $this->advertImages = new ArrayCollection();
    }

    /**
     * Permet d'initialiser automatiquement le slug si on ne le donne pas
     * @return void
     */
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function initializeSlug(): void
    {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->model . '-' .uniqid());
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

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

    public function getKilometers(): ?int
    {
        return $this->kilometers;
    }

    public function setKilometers(int $kilometers): static
    {
        $this->kilometers = $kilometers;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(string $coverImage): static
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getTotalOwners(): ?int
    {
        return $this->totalOwners;
    }

    public function setTotalOwners(int $totalOwners): static
    {
        $this->totalOwners = $totalOwners;

        return $this;
    }

    public function getEngineDisplacement(): ?float
    {
        return $this->EngineDisplacement;
    }

    public function setEngineDisplacement(float $EngineDisplacement): static
    {
        $this->EngineDisplacement = $EngineDisplacement;

        return $this;
    }

    public function getPower(): ?float
    {
        return $this->power;
    }

    public function setPower(float $power): static
    {
        $this->power = $power;

        return $this;
    }

    public function getFuelType(): ?string
    {
        return $this->fuelType;
    }

    public function setFuelType(string $fuelType): static
    {
        $this->fuelType = $fuelType;

        return $this;
    }

    public function getTransmission(): ?string
    {
        return $this->transmission;
    }

    public function setTransmission(string $transmission): static
    {
        $this->transmission = $transmission;

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

    public function getCarOptions(): ?string
    {
        return $this->car_options;
    }

    public function setCarOptions(string $car_options): static
    {
        $this->car_options = $car_options;

        return $this;
    }

    public function getSeller(): ?user
    {
        return $this->seller;
    }

    public function setSeller(?user $seller): static
    {
        $this->seller = $seller;

        return $this;
    }

    /**
     * @return Collection<int, AdvertImage>
     */
    public function getAdvertImages(): Collection
    {
        return $this->advertImages;
    }

    public function addAdvertImage(AdvertImage $advertImage): static
    {
        if (!$this->advertImages->contains($advertImage)) {
            $this->advertImages->add($advertImage);
            $advertImage->setAdvert($this);
        }

        return $this;
    }

    public function removeAdvertImage(AdvertImage $advertImage): static
    {
        if ($this->advertImages->removeElement($advertImage)) {
            // set the owning side to null (unless already changed)
            if ($advertImage->getAdvert() === $this) {
                $advertImage->setAdvert(null);
            }
        }

        return $this;
    }

    public function getYearOfRegistration(): ?\DateTimeInterface
    {
        return $this->yearOfRegistration;
    }

    public function setYearOfRegistration(\DateTimeInterface $yearOfRegistration): static
    {
        $this->yearOfRegistration = $yearOfRegistration;

        return $this;
    }
}
