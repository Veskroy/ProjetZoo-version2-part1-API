<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return new RedirectResponse('http://localhost:5173/');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        /* test de connexion pour la partie front
        $user = $this->getUser();
        return $this->json(['user' => $user, 'error' => $error]);
        */
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        if ($this->getUser()) {
            return new RedirectResponse('http://localhost:5173/');
        }
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user, [
            'attr' => ['class' => 'register-form']
        ])->add('register', SubmitType::class, ['label' => "S'inscrire", 'attr' => ['class' => 'btn button-primary full-width']]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // vérification si un utilisateur a déjà l'email saisi
            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);

            if ($existingUser) {
                $this->addFlash('error', 'Cet email est déjà associé à un compte.');
            } else {
                $password = $form->get('password')->getData();
                $user->setPassword($userPasswordHasher->hashPassword($user, $password));
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Votre compte a bien été créé!');
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
