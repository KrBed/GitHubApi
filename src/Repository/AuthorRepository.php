<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    /**
     * @var ManagerRegistry
     */
    private $registry;
    /**
     * @var BookRepository
     */
    private $booksRepo;

    /**
     * AuthorRepository constructor.
     * @param ManagerRegistry $registry
     * @param BookRepository $booksRepo
     */
    public function __construct(ManagerRegistry $registry, BookRepository $booksRepo)
    {
        parent::__construct($registry, Author::class);
        $this->booksRepo = $booksRepo;
    }

    /**
     * @param Author $author
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addAuthor(Author $author)
    {
        try{
            $em = $this->getEntityManager();
            $authorQuery = $this->findOneBy(['firstName' => $author->getFirstName(), 'lastName' => $author->getLastName()]);
            if ($authorQuery) {
                foreach ($author->getBooks() as $book) {
                    $bookQuery = $this->booksRepo->findBookbyTitleAndAuthorId($book->getTitle(),  $authorQuery->getId());
                    if (!$bookQuery) {
                        $book->setAuthor($authorQuery);
                        $em->persist($book);
                    }
                }
            } else {
                $em->persist($author);
                foreach ($author->getBooks() as $book) {
                    $em->persist($book);
                }
            }
            $em->flush();
        }catch(\Exception $e){
            return $e->getMessage();
        }

        return true;
    }
}
