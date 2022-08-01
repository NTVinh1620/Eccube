<?php

namespace Customize\Controller;

use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Eccube\Repository\ProductRepository;
use Eccube\Repository\ProductClassRepository;

class PaymentController extends Controller
{

    /**
     * 
     * @Route("/payment", name="payment")
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('Payment/index.html.twig', [
            'controller_name' => 'PaymentController',
            'products' => $productRepository->findAll(),
        ]);
    }


    /**
     * 
     * @Route("/checkout", name="checkout")
     */
    public function checkout(Request $request): Response
    {
        $stripeSK = $this->getParameter('eccube_stripe_sk');

        Stripe::setApiKey($stripeSK);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => 
            [
                [
                'price_data' => [
                    'currency' => 'vnd',
                    'product_data' => [
                        'name' => $request->request->get('names')[0],
                    ],
                    'unit_amount' => $request->request->get('prices')[0],
                ],
                'quantity' => $request->request->get('quantities')[0],
                ],
                [
                'price_data' => [
                    'currency' => 'vnd',
                    'product_data' => [
                        'name' => $request->request->get('names')[1],
                    ],
                    'unit_amount' => $request->request->get('prices')[1],
                ],
                'quantity' => $request->request->get('quantities')[1],
                ],
            ],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return $this->redirect($session->url, 303);
    }


    /**
     * 
     * @Route("/success-url", name="success_url")
     */ 
    public function successUrl(): Response
    {
        return $this->render('Payment/success.html.twig', []);
    }


    /**
     * 
     * @Route("/cancel-url", name="cancel_url")
     */
    public function cancelUrl(): Response
    {
        return $this->render('Payment/cancel.html.twig', []);
    }
}