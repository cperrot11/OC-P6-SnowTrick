<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthentificatorAuthenticator;
use App\Services\FileUploader;
use App\Services\Mailer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request,FileUploader $fileUploader , UserPasswordEncoderInterface $passwordEncoder, Mailer $mailer, TokenGeneratorInterface $tokenGenerator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            // création du token
            $user->setToken($tokenGenerator->generateToken());
            // enregistrement de la date de création du token
            $user->setPasswordRequestedAt(new \Datetime());
            $user->setActiv(0);
            if (!is_null($form['picture']->getData())){
                $pictFile = $form['picture']->getData();
                $pictFileName = $fileUploader->upload($pictFile);
                $user->setPicture($pictFileName);
            }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $bodyMail = $mailer->createBodyMail('registration/mail.html.twig', [
                'user' => $user
            ]);
            $mailer->sendMessage('contact@perrotin.eu', $user->getEmail(), 'Accès Snow trick', $bodyMail);
            $this->addFlash(
                'registration',
                'Un mail a été envoyé afin de valider votre accès. Le lien que vous recevrez sera valide 10h.'
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("register/{id}/{token}", name="register")
     */
    public function activating(User $user, $token, Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthentificatorAuthenticator $authenticator, ObjectManager $manager)
    {
        // interdit l'accès à la page si:
        // le token associé au membre est null
        // le token enregistré en base et le token présent dans l'url ne sont pas égaux
        // le token date de plus de 10 minutes
        if ($user->getToken() === null || $token !== $user->getToken() || !$this->isRequestInTime($user->getPasswordRequestedAt()))
        {
            throw new AccessDeniedHttpException();
        }

        $this->addFlash(
            'registration',
            'Votre compte est à présent activé, bienvenue sur notre blog.'
        );
        $user->setActiv(1);
        $manager->persist($user);
        $manager->flush();

        return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
    }
    private function isRequestInTime(\Datetime $passwordRequestedAt = null)
    {
        if ($passwordRequestedAt === null)
        {
            return false;
        }

        $now = new \DateTime();
        $interval = $now->getTimestamp() - $passwordRequestedAt->getTimestamp();

        $daySeconds = 60 * 10;
        $response = $interval > $daySeconds ? false : $reponse = true;
        return $response;
    }
}
