<?php

namespace App\Controller;

use App\Repository\CraiglistRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'home_')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, CraiglistRepository $craiglistRepository, PaginatorInterface $paginator): Response
    {
        $datas = $craiglistRepository->findAll();
        $items = $paginator->paginate($datas,
            $request->query->getInt('page', 1));

        return $this->render('home/index.html.twig', [
            'items' => $items,
        ]);
    }

}
