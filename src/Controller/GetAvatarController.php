<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class GetAvatarController extends AbstractController
{
    public function __invoke(User $data): Response
    {
        /** @var resource $avatar */
        $avatar = $data->getAvatar();

        return new Response(
            stream_get_contents($avatar, null, 0),
            '200',
            ['content-type', 'image/png']
        );
    }
}
