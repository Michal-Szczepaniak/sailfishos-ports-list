<?php

namespace App\Entity;

use App\Grid\DeviceGrid;
use App\Repository\DeviceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Index;

#[ORM\Entity(repositoryClass: DeviceRepository::class)]
#[AsResource(
    operations: [
        new Index(
            path: '/',
            grid: DeviceGrid::class,
        ),
    ]
)]
class Device implements ResourceInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $codename = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $vendor = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $authors = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $author_emails = null;

    #[ORM\Column(length: 255)]
    private ?string $version = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $broken_list = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $features = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private bool $blacklisted = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodename(): ?string
    {
        return $this->codename;
    }

    public function setCodename(string $codename): static
    {
        $this->codename = $codename;

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

    public function getVendor(): ?string
    {
        return $this->vendor;
    }

    public function setVendor(string $vendor): static
    {
        $this->vendor = $vendor;

        return $this;
    }

    public function getAuthors(): ?array
    {
        return $this->authors;
    }

    public function setAuthors(array $authors): static
    {
        $this->authors = $authors;

        return $this;
    }

    public function getAuthorEmails(): ?array
    {
        return $this->author_emails;
    }

    public function setAuthorEmails(array $author_emails): static
    {
        $this->author_emails = $author_emails;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): static
    {
        $this->version = $version;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getBrokenList(): ?array
    {
        return $this->broken_list;
    }

    public function setBrokenList(array $broken_list): static
    {
        $this->broken_list = $broken_list;

        return $this;
    }

    public function getFeatures(): ?array
    {
        return $this->features;
    }

    public function setFeatures(array $features): static
    {
        $this->features = $features;

        return $this;
    }

    public function isBlacklisted(): bool
    {
        return $this->blacklisted;
    }

    public function setBlacklisted(bool $blacklisted): static
    {
        $this->blacklisted = $blacklisted;

        return $this;
    }
}
