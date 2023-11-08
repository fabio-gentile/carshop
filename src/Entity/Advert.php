<?php

namespace App\Entity;

use App\Repository\AdvertRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdvertRepository::class)]
class Advert
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $brand = null;

    #[ORM\Column(length: 255)]
    private ?string $model = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?int $kilometers = null;

    #[ORM\Column(length: 255)]
    private ?string $coverImage = null;

    #[ORM\Column]
    private ?int $totalOwners = null;

    #[ORM\Column]
    private ?float $EngineDisplacement = null;

    #[ORM\Column]
    private ?float $power = null;

    #[ORM\Column(length: 255)]
    private ?string $fuelType = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $yearRegistration = null;

    #[ORM\Column(length: 50)]
    private ?string $transmission = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $car_options = null;

    #[ORM\ManyToOne(inversedBy: 'adverts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $seller = null;

    #[ORM\OneToMany(mappedBy: 'advert', targetEntity: AdvertImage::class, orphanRemoval: true)]
    private Collection $advertImages;

    public function __construct()
    {
        $this->advertImages = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getYearRegistration(): ?\DateTimeInterface
    {
        return $this->yearRegistration;
    }

    public function setYearRegistration(\DateTimeInterface $yearRegistration): static
    {
        $this->yearRegistration = $yearRegistration;

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
}
