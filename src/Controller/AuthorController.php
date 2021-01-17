<?php


namespace App\Controller;


use App\Entity\Author;
use App\Form\AuthorFormType;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    /**
     * @var AuthorRepository
     */
    private $authorRepository;

    /**
     * AuthorController constructor.
     * @param AuthorRepository $authorRepository
     */
    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route ("/author/new", name="add_author")
     */
    public function add(Request $request): Response
    {
        $form = $this->createForm(AuthorFormType::class, new Author());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $result = $this->authorRepository->addAuthor($form->getData());
            if ($result === true) {
                $this->addFlash('notice', 'Dodano nowego autora');
                $form = $this->createForm(AuthorFormType::class,new Author());
            } else {
                $this->addFlash('notice', 'Coś poszło nie tak, nie dodano nowego autora');
                return $this->render('author/new.html.twig', ['authorForm' => $form->createView()]);
            }
        }

        return $this->render('author/new.html.twig', ['authorForm' => $form->createView()]);
    }
}