<?php

namespace App\Entity;

use App\Repository\MyBooksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MyBooksRepository::class)]
class MyBooks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $NameINV = null;

    #[ORM\OneToMany(mappedBy: 'myBooks', targetEntity: Books::class, orphanRemoval: true, cascade: ["persist"])]
    private Collection $books;

    #[ORM\ManyToOne(inversedBy: 'member')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Member $member = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $Created = null;

    #[ORM\Column(length: 255)]
    private ?string $InvDesc = null;

    #[ORM\ManyToOne(inversedBy: 'mybooks')]
    #[ORM\JoinColumn(nullable: false)]



    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameINV(): ?string
    {
        return $this->NameINV;
    }

    public function setNameINV(string $NameINV): static
    {
        $this->NameINV = $NameINV;

        return $this;
    }

    /**
     * @return Collection<int, Books>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Books $book): static
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->setMyBooks($this);
        }

        return $this;
    }

    public function removeBook(Books $book): static
    {
        if ($this->books->removeElement($book)) {
            if ($book->getMyBooks() === $this) {
                $book->setMyBooks(null);
            }
        }

        return $this;
    }

    public function __toString()
{
    return $this->getNameINV();
    
}
public function booksToString(): string
{
    $bookNames = [];

    foreach ($this->getBooks() as $book) {
        $bookNames[] = $book->getTitle();
         
    }

    return implode(', ', $bookNames);
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

public function getCreated(): ?\DateTimeInterface
{
    return $this->Created;
}

public function setCreated(\DateTimeInterface $Created): static
{
    $this->Created = $Created;

    return $this;
}

public function getInvDesc(): ?string
{
    return $this->InvDesc;
}

public function setInvDesc(string $InvDesc): static
{
    $this->InvDesc = $InvDesc;

    return $this;
}


   



}