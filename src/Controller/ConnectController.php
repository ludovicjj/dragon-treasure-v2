<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ConnectController extends AbstractController
{
    #[Route('/', name: 'app_connect')]
    public function connect(NormalizerInterface $normalizer, #[CurrentUser] User $user = null): Response
    {
        return $this->render('ux/connect.html.twig', [
            'userData' => $normalizer->normalize($user, 'jsonld', [
                'groups' => ['user:read']
            ])
        ]);
    }
}