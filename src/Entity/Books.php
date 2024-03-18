<?php

namespace App\Entity;

use App\Repository\BooksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @Vich\Uploadable
 */


#[ORM\Entity(repositoryClass: BooksRepository::class)]
class Books
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $author = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MyBooks $myBooks = null;

    #[ORM\Column( length:255, nullable:true)]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Range(
        min: 0,
        max: 20,
        notInRangeMessage: 'Note doit être entre {{ min }} et {{ max }}.',
    )]
    private ?int $note = null;
    

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateDeparution = null;

    #[ORM\Column(length: 255)]
    private ?string $bookDesc = null;

    #[ORM\ManyToMany(targetEntity: GBooky::class, mappedBy: 'galeries')]
    private Collection $gBookies;

    

    public function __construct()
    {
        $this->galeries = new ArrayCollection();
        $this->gBookies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getMyBooks(): ?MyBooks
    {
        return $this->myBooks;
    }

    public function setMyBooks(?MyBooks $myBooks): static
    {
        $this->myBooks = $myBooks;

        return $this;
    }
    public function __toString() {
        return $this->getTitle() . ' (' . $this->getAuthor() . ') ' . $this->getMyBooks()->getId();
    }


    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getDateDeParution(): ?\DateTimeInterface
    {
        return $this->dateDeparution;
    }

    public function setDateDeParution(?\DateTimeInterface $dateDeparution): static
    {
        $this->dateDeparution = $dateDeparution;

        return $this;
    }

    public function getBookDesc(): ?string
    {
        return $this->bookDesc;
    }

    public function setBookDesc(string $bookDesc): static
    {
        $this->bookDesc = $bookDesc;

        return $this;
    }

    /**
     * @return Collection<int, GBooky>
     */
    public function getGBookies(): Collection
    {
        return $this->gBookies;
    }

    public function addGBooky(GBooky $gBooky): static
    {
        if (!$this->gBookies->contains($gBooky)) {
            $this->gBookies->add($gBooky);
            $gBooky->addGalery($this);
        }

        return $this;
    }

    public function removeGBooky(GBooky $gBooky): static
    {
        if ($this->gBookies->removeElement($gBooky)) {
            $gBooky->removeGalery($this);
        }

        return $this;
    }

    
      
    
}
