<?php

namespace Customize\Controller;

use Eccube\Entity\SimNumber;
use Eccube\Form\Type\SimNumberType;
use Eccube\Repository\SimNumberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * 
     * @Route("/sample")
     */
    public function testMethod(): Response
    {
        return $this->json(['success' => 'ok']);
    }
}
