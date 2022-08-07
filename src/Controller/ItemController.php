<?php

namespace App\Controller;

use App\Entity\Craiglist;
use App\Form\CraiglistFormType;
use App\Repository\CraiglistRepository;
use App\Service\FileUploader;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/item', name: 'item_')]
class ItemController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, CraiglistRepository $craiglistRepository, PaginatorInterface $paginator): Response
    {
        $datas = $craiglistRepository->findAll();
        $items = $paginator->paginate($datas,
        $request->query->getInt('page', 1));

        return $this->render('item/index.html.twig', [
            'items' => $items,
        ]);
    }

    #[Route('/{id}', name: 'show')]
    public function show(int $id,CraiglistRepository $craiglistRepository): Response
    {
        $item = $craiglistRepository->find($id);

        return $this->render('item/show.html.twig', [
            'item' => $item,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, CraiglistRepository $craiglistRepository, FileUploader $fileUploader): Response
    {
        $item = new Craiglist();
        $form = $this->createForm(CraiglistFormType::class, $item);
        $item->setDate(new \DateTime('now'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imgFile */
            $imgFile = $form->get('picture')->getData();
            if ($imgFile) {
                $imgFileName = $fileUploader->upload($imgFile);
                $item->setPicture($imgFileName);
            }

            $craiglistRepository->add($item, true);
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('item/new.html.twig', [
            'itemForm' => $form->createView(),
        ]);
    }
}
