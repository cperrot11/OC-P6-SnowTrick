<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use App\Services\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/trick")
 */
class TrickController extends AbstractController
{
    /**
     * @Route("/blog", name="trick_index", methods={"GET"})
     */
    public function index(TrickRepository $trickRepository): Response
    {
        $end = getenv('LIMIT');
        $tricks = $trickRepository->findAllTrick(1, $end);
        $more = $end<$tricks->count();
        return $this->render('trick/index.html.twig', [
            'title'=>'Blog snow trick',
            'tricks' => $tricks,
            'more' => $more,
        ]);
    }
    /**
     * @Route("/ajax/{click}", name="loadMore", methods={"GET"})
     */
    public function ajax(Request $request, TrickRepository $repository, $click=1)
    {
        $begin = (($click-1)*getenv('LIMIT'))+1;
        $end = getenv('LIMIT');
        $trick = $repository->findAllTrick($begin, $end);
        return $this->render('trick/displayMoreTrick.html.twig', [
            'tricks' => $trick,
        ]);
    }
    /**
     * @Route("/new", name="trick_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $trick = new Trick();
        $trick->setCreatedAt(new \DateTime());
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            {
                foreach($form['pictures'] as $pict){
                    dump($pict);
                    die();
                    /** @var UploadedFile $brochureFile */
                    $brochureFile = $form['pictures']->getData();

                    if ($brochureFile) {
                        $brochureFileName = $fileUploader->upload($brochureFile);


                        $trick->addPicture($brochureFileName);
                    }
                }
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('trick_index');
        }

        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="trick_show", methods={"GET"})
     */
    public function show(Trick $trick): Response
    {
        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="trick_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Trick $trick): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('trick_index', [
                'id' => $trick->getId(),
            ]);
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="trick_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Trick $trick): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($trick);
            $entityManager->flush();
        }

        return $this->redirectToRoute('trick_index');
    }

}