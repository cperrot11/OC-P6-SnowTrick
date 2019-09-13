<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Picture;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use App\Services\FileUploader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
        $end_comment = getenv('LIMIT_COMMENT');
        $tricks = $trickRepository->findAllTrick(0, $end);
        $more = $end<$tricks->count();
        return $this->render('trick/index.html.twig', [
            'title'=>'Blog snow trick',
            'tricks' => $tricks,
            'more' => $more,
        ]);
    }
    /**
     * @Route("/ajax/comment/{id}", name="loadMoreComment", methods={"GET"})
     */
    public function ajaxComment(Trick $trick, Request $request, CommentRepository $repository)
    {
        $click = $request->query->get('click');
        $begin = (($click-1)*getenv('LIMIT_COMMENT'));
        $end = getenv('LIMIT_COMMENT');
        $comments = $repository->findAllComment($trick, $begin, $end);

        return $this->render('trick/displayMoreComment.html.twig', [
            'comments' => $comments,
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
                foreach ($form['pictures']->getData() as $picture){
                    $pictFile = $picture->getFile();
                    $pictFileName = $fileUploader->upload($pictFile);
                    $picture->setName($pictFileName);
                    $manager->persist($picture);
                }
//                foreach ($form['videos']->getData() as $video){
//                    dump($video);
//                }
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
     * @Route("/{id}", name="trick_show", methods={"GET","POST"})
     */
    public function show(Trick $trick, Request $request, ObjectManager $manager, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $comment->setCreationDate(new \DateTime());

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment->setTrick($trick);
            $comment->setUser($this->getUser());

            $manager->persist($comment);
            $manager->flush();
            return $this->redirectToRoute("trick_show",[
                'id'=>$trick->getId()
            ]);
        }
        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'comments' => $commentRepository->findAllComment($trick,0,getenv('LIMIT_COMMENT')),
            'commentForm'=>$form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="trick_edit")
     * @IsGranted("ROLE_USER")
     */
    public function edit(Trick $trick, Request $request, FileUploader $fileUploader, ObjectManager $manager): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Ajout image
//            foreach($trick->getPictures() as $pict){
//                /** @var UploadedFile $brochureFile */
//                if (!$pict->getId()){
//                    $pict = $fileUploader->saveImage($pict);
//                    $manager->persist($pict);
//                }
//            }
            foreach ($form['pictures']->getData() as $picture){
                if(!$picture->getId()){
                    $pictFile = $picture->getFile();
                    $pictFileName = $fileUploader->upload($pictFile);
                    $picture->setName($pictFileName);
                    $manager->persist($picture);
                }
            }
            $manager->persist($trick);
            $manager->flush();

            return $this->redirectToRoute('trick_show', [
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
        //le Token Impose un appel depuis page autorisée
            if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($trick);
                $entityManager->flush();            }
            else{
                $this->addFlash("error","Erreur token....");
            }
        return $this->redirectToRoute('trick_index');
    }
}