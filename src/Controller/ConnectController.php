<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConnectController extends AbstractController
{
    #[Route('/', name: 'app_connect')]
    public function connect(): Response
    {
        return $this->render('ux/connect.html.twig');
    }
}