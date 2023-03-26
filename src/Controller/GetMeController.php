<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

class GetMeController extends AbstractController
{
    public function __invoke(): UserInterface
    {
        return null !== $this->getUser() ? $this->getUser() : throw $this->createNotFoundException();
    }
}
