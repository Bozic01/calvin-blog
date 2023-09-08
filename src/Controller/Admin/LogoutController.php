<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{
    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout()
    {
        return $this->redirectToRoute('login');
    }

}
