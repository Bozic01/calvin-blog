<?php

namespace App\Controller\Admin;

use App\Entity\User\User;
use App\Form\Admin\User\UserType;
use App\Traits\Admin\SearchTrait;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user")
 */
class UserController extends AbstractController
{
    use SearchTrait;

    /**
     * @Route("/", name="admin_user_index", methods={"GET"})
     */
    public function index(
        PaginatorInterface     $paginator,
        Request                $request,
        EntityManagerInterface $entityManager
    ): Response
    {

        $userRepository = $entityManager->getRepository(User::class);
        $baseQuery = $userRepository->createQueryBuilder('user');
        $baseQuery = $this->extendQueryWithSearch($request, $baseQuery, ['user.name', 'user.email']);
        $baseQuery->getQuery();

        $users = $paginator->paginate($baseQuery, $request->query->getInt('page', 1), 10);

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/new", name="admin_user_new", methods={"GET", "POST"})
     */
    public function new(
        Request                     $request,
        EntityManagerInterface      $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword($passwordHasher->hashPassword($user, $request->request->get('user')['password']));
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_user_edit", methods={"GET", "POST"})
     */
    public function edit(
        Request                     $request,
        User                        $user,
        EntityManagerInterface      $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        $oldPassword = $user->getPassword();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $request->request->get('user')['password'];

            if ($password) {
                $user->setPassword($passwordHasher->hashPassword($user, $password));
            } else {
                $user->setPassword($oldPassword);
            }

            $entityManager->flush();

            return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $checkIfUserIsActive = $entityManager->getRepository(User::class)->findOneBy(['id' => $user]);

        if (
            $this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token')) &&
            is_null($checkIfUserIsActive)
        ) {
            $entityManager->remove($user);
            $entityManager->flush();
        } else {
            $this->addFlash('error', 'This user cannot be deleted due to usage.');
        }

        return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
