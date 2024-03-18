<?php

namespace App\Entity;

use App\Repository\GBookyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotNull;

#[ORM\Entity(repositoryClass: GBookyRepository::class)]
class GBooky
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $GDescription = null;

    #[ORM\Column]
    private ?bool $Published = null;

    #[ORM\ManyToMany(targetEntity: Books::class, inversedBy: 'gBookies')]
    private Collection $galeries;

    #[ORM\ManyToOne(inversedBy: 'creator')]
    private ?Member $member =null;

    public function __construct()
    {
        $this->galeries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGDescription(): ?string
    {
        return $this->GDescription;
    }

    public function setGDescription(string $GDescription): static
    {
        $this->GDescription = $GDescription;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->Published;
    }

    public function setPublished(bool $Published): static
    {
        $this->Published = $Published;

        return $this;
    }

    /**
     * @return Collection<int, Books>
     */
    public function getGaleries(): Collection
    {
        return $this->galeries;
    }

    public function addGalery(Books $galery): static
    {
        if (!$this->galeries->contains($galery)) {
            $this->galeries->add($galery);
        }

        return $this;
    }

    public function removeGalery(Books $galery): static
    {
        $this->galeries->removeElement($galery);

        return $this;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): static
    {
        $this->member = $member;

        return $this;
    }
    /**
     * Get the books associated with this GBooky.
     *
     * @return Collection<int, Books>
     */
    public function getBooks(): Collection
    {
        return $this->getGaleries();
    }

}
