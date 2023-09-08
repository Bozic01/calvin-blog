<?php

namespace App\Controller\Admin;

use App\Entity\User\User;
use App\Enum\User\UserRoleEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/registration", name="registration")
     */
    public function index(): Response
    {
        return $this->render('admin/registration/index.html.twig');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(
        Request                     $request,
        EntityManagerInterface      $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        try {

            $email = $request->request->get('email');

            if (!is_null($entityManager->getRepository(User::class)->findOneBy(['email' => $email]))) {
                return new Response('User already exists');
            }

            $user = new User();
            $user->setName($request->request->get('name'));
            $user->setEmail($email);

            $passwordHashed = $passwordHasher->hashPassword($user, $request->request->get('password'));
            $user->setPassword($passwordHashed);
            $user->setRoles([UserRoleEnum::ROLE_USER]);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->render('admin/login/index.html.twig', [
                'last_username' => $request->request->get('username'),
                'error' => null
            ]);

        } catch (\Exception $exception) {
            return new Response('Something went wrong', 400);
        }

    }
}
