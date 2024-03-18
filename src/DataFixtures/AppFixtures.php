<?php

namespace App\DataFixtures;

use App\DataFixtures\UserFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;
use App\Entity\Books;
use App\Entity\GBooky;
use App\Entity\User;
use App\Entity\MyBooks;
use App\Entity\Member;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class AppFixtures extends Fixture
{
    // defines reference names for instances of MyBooks
    private const BIB_PRINC = 'bibliothéque princiaple';
    private const SECT_DEVP_PER = 'section-de-developpement-personnel';
    private const SECT_SF = 'section-science-fiction';
    private const PSYCH = 'section-psychologie';
    private const POLITIQUE = 'section-politique';

    private const Member1 = 'sabrine';
    private const  Member2= 'Yakine';
    private const  Member3= 'Saif';


    /**
     * @return \Generator
     */
  
    private static function myBooksDataGenerator()
    {
        yield [self::Member1, self::BIB_PRINC, new \DateTime('2022-05-10'), "Description de l'inventaire 1"];
        yield [self::Member2, self::SECT_DEVP_PER, new \DateTime('2022-06-15'), "Description de l'inventaire 2"];
        yield [self::Member2, self::SECT_SF, new \DateTime('2022-05-10'), "Description de l'inventaire 1"];
        yield [self::Member3,self::PSYCH, new \DateTime('2022-05-10'), "Description de l'inventaire 1"];
        yield [self::Member1, self::POLITIQUE, new \DateTime('2022-05-10'), "Description de l'inventaire 1"];
    }
    

  
    private static function booksGenerator()
    {
        yield [self::BIB_PRINC, "La fougère et le Bambou", "Marie Tibi", 18, new \DateTime('2022-01-15'), "Un livre sur la nature fascinant", 'book1.jpg'];
        yield [self::SECT_DEVP_PER, "L'homme qui voulait être heureux", "Laurent Gounelle", 16, new \DateTime('2021-11-20'), "Un livre inspirant sur le bonheur",'book2.jpg'];
        yield [self::SECT_DEVP_PER, "Le jour où j'ai appris à vivre", "Laurent Gounelle", 17, new \DateTime('2022-03-10'), "Une aventure pour apprendre à vivre", 'book3.jpg'];
        yield [self::SECT_SF, "L'ultime secret", "Bernard Werber", 19, new \DateTime('2022-02-01'), "Un classique de la science-fiction", 'book4.jpg'];
        yield [self::SECT_SF, "Le mirroir de cassandre", "Bernard Werber",18, new \DateTime('2022-01-15'), "Un livre sur la nature fascinant", 'book5.jpg'];
        yield [self::SECT_SF, "1984", "George Orwell ",19, new \DateTime('2022-02-01'), "Un classique de la science-fiction", 'book6.jpg'];
        yield [self::PSYCH, "L'inconscient", "Freud",19, new \DateTime('2022-02-01'), "Un classique de la science-fiction", 'book7.jpg'];
        yield [self::POLITIQUE, "L'emprise", "Marc Dugain",19, new \DateTime('2022-02-01'), "Un classique de la science-fiction", 'book8.jpg'];
    }
    private static function gBookyGenerator()
    {
        yield [self::Member1, 'Galerie de Livres de Sabrine', true];
        yield [self::Member2, 'Galerie de Livres de Yakine', false];
        yield [self::Member3, 'Galerie de Livres de Saif', true];
    }
   
  
     /**
     * Generates initialization data for Books:
     *  [myBooksReferenec, Name, Description]
     * @return \Generator
     */
    private static function memberGenerator()
    {
        yield [self::Member1, "Ingénieure", 'sabrine@localhost'];
        yield [self::Member2, "Amateur de lecture en développement personnel et science fiction", 'yakine@localhost'];
        yield [self::Member3, "Amateur de lecture en psychologie", 'saif@localhost'];
    }
    private function getBookImages(): array
    {
        return [
            'book1.jpg' => 'La fougere et le Bambou',
            'book2.jpg' => "L'homme qui voulait etre heureux",
            'book3.jpg' => "Le jour ou j'ai appris à vivre",
            'book4.jpg' => "L'ultime secret",
            'book5.jpg' => "Le mirroir de cassandre",
            'book6.jpg' => "1984",
            'book7.jpg' => "L'inconscient",
            'book8.jpg' => "L'emprise",
        ];
    }
    private $kernel; 

    public function __construct(KernelInterface $kernel) 
    {
        $this->kernel = $kernel;
    }

    private function createBookImage(string $fileName, string $title): UploadedFile
    {
        $imagePath = $this->kernel->getProjectDir() . '/public/images/books/' . $fileName;
        return new UploadedFile($imagePath, $fileName);
    }

    public function load(ObjectManager $manager)
    {
        $inventoryRepo= $manager->getRepository(Member::class);

        foreach (self::memberGenerator() as [$nom,$description, $email ] ) {
           
            $member = new Member();
            if ($email) {
                $user = $manager->getRepository(User::class)->findOneBy(['email' => $email]);
                if ($user) {
                    $member->setMembre($user);
                }
            }
            $member->setName($nom);
            $member->setDescription($description);
            $manager->persist($member);
            $manager->flush();
            $this->addReference($nom, $member);
        }

        $inventoryRepo = $manager->getRepository(MyBooks::class);
        foreach (self::myBooksDataGenerator() as [$MemberReference, $name_INV, $created, $invDesc]) {
            $member = $this->getReference($MemberReference);
            $myBooks = new MyBooks();
            $myBooks->setNameINV($name_INV);
            $myBooks->setCreated($created);
            $myBooks->setInvDesc($invDesc);
            $member->addMember($myBooks);
            $manager->persist($myBooks);
            $manager->flush();
            $this->addReference($name_INV, $myBooks);
        }
        foreach (self::gBookyGenerator() as [$MemberReference, $description, $publiee]) {
            $member = $this->getReference($MemberReference);
            $galerie = new GBooky();
            $galerie->setGDescription($description);
            $galerie->setPublished($publiee);
            $galerie->setMember($member);

            $manager->persist($galerie);
        }
        
       
        
       

        foreach (self::booksGenerator() as [$myBooksReference, $title, $author, $note, $DateDeparution, $description, $image ]) {
            
            $myBooks = $this->getReference($myBooksReference);
            $book = new Books();
            $book->setTitle($title);
            $book->setAuthor($author);
            $book->setMyBooks($myBooks);
            $book->setNote($note);
            $book->setDateDeParution($DateDeparution);
            $book->setBookDesc($description);
            $book->setImage($image);

           
            $myBooks->addBook($book);
            $manager->persist($book);


    
        
        $manager->flush();
       
         }
    
    }
public function getDependencies()
{
            return [
                    UserFixtures::class,
            ];
}
}

