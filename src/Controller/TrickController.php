<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use App\Services\FileUploader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
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
     * @Route("/test", name="test")
     */
    public function test(ObjectManager $manager) : Response
    {
        $trick1 = new Trick();
        $trick1->setCreatedAt(new \DateTime());
        $trick1->setDescription('test');
        $trick1->setName('test');
        $img1 =new Picture();
        $img1->setFile('test.jpg');
        $img1->setTrick($trick1);
        $img2 =new Picture();
        $img2->setFile('test2.jpg');
        $img2->setTrick($trick1);

        $manager->persist($img1);
        $manager->persist($img2);
        $manager->persist($trick1);
        $manager->flush();

        return $this->redirectToRoute('trick_index');
    }
    /**
     * @Route("/blog", name="trick_index", methods={"GET"})
     */
    public function index(TrickRepository $trickRepository): Response
    {
        $end = getenv('LIMIT');
        $tricks = $trickRepository->findAllTrick(0, $end);
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
    public function new(Request $request, FileUploader $fileUploader, ObjectManager $manager): Response
    {
        $trick = new Trick();
        $trick->setCreatedAt(new \DateTime());
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
        $manager->persist($trick);

        if ($form->isSubmitted() && $form->isValid()) {
            {
                foreach($trick->getPictures() as $pict){
                    /** @var UploadedFile $brochureFile */
                    $pict = $fileUploader->saveImage($pict);
                    $manager->persist($pict);
                }
            }
            $manager->flush();

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
        dump($trick);
        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="trick_edit")
     */
    public function edit($id, Request $request, FileUploader $fileUploader, ObjectManager $manager): Response
    {
        //stock les images initiales
        $originalPicts = new ArrayCollection();
        $trick = $manager->getRepository(Trick::class)->find($id);
        foreach ($trick->getPictures() as $picture){
            $originalPicts->add($picture);
        }

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Ajout image
            foreach($trick->getPictures() as $pict){
                /** @var UploadedFile $brochureFile */
                if (!$pict->getId()){
                    $pict = $fileUploader->saveImage($pict);
                }
            }
            // suppression image
            foreach ($originalPicts as $pict){
                if (false === $trick->getPictures()->contains($pict)) {
                    $trick->removePicture($pict);
                }
            }
            $manager->persist($trick);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('trick_edit', [
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