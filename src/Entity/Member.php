<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: MyBooks::class, orphanRemoval: true)]
    private Collection $member;

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: GBooky::class)]
    private Collection $creator;

    #[ORM\OneToOne(mappedBy: 'myuser', cascade: ['persist', 'remove'])]
    private ?User $membre = null;



    public function __construct()
    {
        $this->member = new ArrayCollection();
        $this->creator = new ArrayCollection();
       

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

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

    /**
     * @return Collection<int, MyBooks>
     */
    public function getMember(): Collection
    {
        return $this->member;
    }

    public function addMember(MyBooks $member): static
    {
        if (!$this->member->contains($member)) {
            $this->member->add($member);
            $member->setMember($this);
        }

        return $this;
    }

    public function removeMember(MyBooks $member): static
    {
        if ($this->member->removeElement($member)) {
            // set the owning side to null (unless already changed)
            if ($member->getMember() === $this) {
                $member->setMember(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, GBooky>
     */
    public function getCreator(): Collection
    {
        return $this->creator;
    }

    public function addCreator(GBooky $creator): static
    {
        if (!$this->creator->contains($creator)) {
            $this->creator->add($creator);
            $creator->setMember($this);
        }

        return $this;
    }

    public function removeCreator(GBooky $creator): static
    {
        if ($this->creator->removeElement($creator)) {
            // set the owning side to null (unless already changed)
            if ($creator->getMember() === $this) {
                $creator->setMember(null);
            }
        }

        return $this;
    }
    public function getMybooks(): Collection
    {
        return $this->member;
    }
    public function __toString()
{
    return $this->getName(); 
}

    public function getMembre(): ?User
    {
        return $this->membre;
    }

    public function setMembre(?User $membre): static
    {
        // unset the owning side of the relation if necessary
        if ($membre === null && $this->membre !== null) {
            $this->membre->setMyuser(null);
        }

        // set the owning side of the relation if necessary
        if ($membre !== null && $membre->getMyuser() !== $this) {
            $membre->setMyuser($this);
        }

        $this->membre = $membre;

        return $this;
    }


}
