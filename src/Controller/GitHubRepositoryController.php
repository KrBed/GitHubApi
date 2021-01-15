<?php


namespace App\Controller;


use App\Entity\Repository;
use App\Form\RepositoryFormType;
use App\Service\GitHubConfig;
use App\Service\GitHubRepositoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GitHubRepositoryController extends AbstractController
{
    /**
     * @var GitHubRepositoryService
     */
    private $repoService;

    /**
     * GitHubRepositoryController constructor.
     * @param GitHubRepositoryService $repoService
     */
    public function __construct(GitHubRepositoryService $repoService)
    {
        $this->repoService = $repoService;
    }

    /**
     * @Route("/repozytoria", name="github_repository_list")
     */
    public function index()
    {
        $response = $this->repoService->getLoggedUserRepos();

        if ($response->getStatusCode() !== GitHubConfig::STATUS_CODE_OK) {
            $info = $response->getInfo();

            return $this->render('exception/show.html.twig', ['error' => $info['http_code']]);
        }

        $repository = new Repository();
        $repoDto = $repository->createRepositories($response->toArray());
        return $this->render('repository/index.html.twig', ['repos' => $repoDto]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route ("/repozytoria/new", name="repository_add")
     */
    public function new(Request $request)
    {

        $form = $this->createForm(RepositoryFormType::class, new Repository());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repo = $form->getData();
            $response = $this->repoService->createRepoForLoggedUser($repo);

            if ($response->getStatusCode() !== GitHubConfig::STATUS_CODE_CREATED) {
                $info = $response->getInfo();

                return $this->render('exception/show.html.twig', ['error' => $info['http_code']]);
            }
        }

        return $this->render('repository/new.html.twig', ['repoForm' => $form->createView()]);
    }
}