<?php
/**
 * Tag entity.
 */
namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * Class Tag.
 */
#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ORM\Table(name: 'tags')]
class Tag
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    /**
     * Name.
     */
    #[ORM\Column(length: 20)]
    private ?string $name = null;
    /**
     * Created at.
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'create')] // Automatycznie przy tworzeniu
    private ?\DateTimeImmutable $createdAt = null;
    /**
     * Updated at.
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: true)] // Bezpieczne nullable dla starych danych
    #[Gedmo\Timestampable(on: 'update')] // Automatycznie przy każdej edycji
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * Slug.
     */
    #[ORM\Column(type: 'string', length: 20, unique: true)]
    #[Gedmo\Slug(fields: ['name'])]
    private ?string $slug = null;
    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     * Getter for name.
     *
     * @return string|null Name
     */
    public function getName(): ?string
    {
        return $this->name;
    }
    /**
     * Setter for name.
     *
     * @param string|null $name Name
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
    /**
     * Getter for created at.
     *
     * @return \DateTimeImmutable|null Created at
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
    /**
     * Setter for created at.
     *
     * @param \DateTimeImmutable|null $createdAt Created at
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    /**
     * Getter for updated at.
     *
     * @return \DateTimeImmutable|null Updated at
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
    /**
     * Setter for updated at.
     *
     * @param \DateTimeImmutable|null $updatedAt Updated at
     */
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

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
}
