<?php
/**
 * Url Entity.
 *
 * (c) Your Name / University License
 */

namespace App\Entity;

use App\Repository\UrlRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Url.
 */
#[ORM\Entity(repositoryClass: UrlRepository::class)]
#[ORM\Table(name: 'urls')]
#[ORM\UniqueConstraint(name: 'uq_urls_short_code', columns: ['short_code'])]
#[UniqueEntity(fields: ['shortCode'])]
class Url
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Short code.
     */
    #[ORM\Column(length: 20, unique: true)]
    #[Assert\Type('string')]
    #[Assert\Length(min: 3, max: 20)]
    private ?string $shortCode = null;

    /**
     * Created at.
     *
     * @var DateTimeImmutable|null
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * Updated at.
     *
     * @var DateTimeImmutable|null
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * Tags collection.
     *
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class)]
    private Collection $tags;

    /**
     * Original URL.
     */
    #[ORM\Column(length: 2048)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Url]
    #[Assert\Length(max: 2048)]
    private ?string $originalUrl = null;

    /**
     * Guest email.
     */
    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Email]
    #[Assert\Length(max: 255)]
    private ?string $guestEmail = null;

    /**
     * Click count.
     */
    #[ORM\Column]
    #[Assert\Type('integer')]
    #[Assert\PositiveOrZero]
    private int $clickCount = 0;

    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: true)]
    #[Assert\Type(User::class)]
    private ?User $user = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

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
     * Getter for short code.
     *
     * @return string|null Short Code
     */
    public function getShortCode(): ?string
    {
        return $this->shortCode;
    }

    /**
     * Setter for short code.
     *
     * @param string $shortCode Short Code
     *
     * @return $this
     */
    public function setShortCode(string $shortCode): static
    {
        $this->shortCode = $shortCode;

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
     * @param \DateTimeImmutable $createdAt Created at
     *
     * @return $this
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
     * @param \DateTimeImmutable $updatedAt Updated at
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Getter for tags collection.
     *
     * @return Collection<int, Tag> Tags
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Add tag to collection.
     *
     * @param Tag $tag Tag entity
     *
     * @return $this
     */
    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    /**
     * Remove tag from collection.
     *
     * @param Tag $tag Tag entity
     *
     * @return $this
     */
    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * Getter for original URL.
     *
     * @return string|null Original URL
     */
    public function getOriginalUrl(): ?string
    {
        return $this->originalUrl;
    }

    /**
     * Setter for original URL.
     *
     * @param string $originalUrl Original URL
     *
     * @return $this
     */
    public function setOriginalUrl(string $originalUrl): static
    {
        $this->originalUrl = $originalUrl;

        return $this;
    }

    /**
     * Getter for guest email.
     *
     * @return string|null Guest email
     */
    public function getGuestEmail(): ?string
    {
        return $this->guestEmail;
    }

    /**
     * Setter for guest email.
     *
     * @param string $guestEmail Guest email
     *
     * @return $this
     */
    public function setGuestEmail(?string $guestEmail): static
    {
        $this->guestEmail = $guestEmail;

        return $this;
    }

    /**
     * Getter for click count.
     *
     * @return int Click count
     */
    public function getClickCount(): int
    {
        return $this->clickCount;
    }

    /**
     * Setter for click count.
     *
     * @param int $clickCount Click count
     *
     * @return $this
     */
    public function setClickCount(int $clickCount): static
    {
        $this->clickCount = $clickCount;

        return $this;
    }

    /**
     * Increment click count.
     *
     * @return $this
     */
    public function incrementClickCount(): static
    {
        ++$this->clickCount;

        return $this;
    }

    /**
     * Transform object to string.
     *
     * @return string Original URL
     */
    public function __toString(): string
    {
        return (string) $this->getOriginalUrl();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
