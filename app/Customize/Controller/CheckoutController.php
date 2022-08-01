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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class CheckoutController extends Controller
{

    /**
     * 
     * @Route("/check", name="check")
     * 
     * @IsGranted("ROLE_USER", message="You must be logged")
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('Checkout/checkout.html.twig', [
            'controller_name' => 'CheckoutController',
            'products' => $productRepository->findAll(),
        ]);
    }

    function calculateOrderAmount(array $items): int {
        return 1400;
    }

    /**
     * 
     * @Route("/send-checkout", name="send-checkout")
     */
    public function check(Request $request)
    {
        $stripeSK = $this->getParameter('eccube_stripe_sk');

        Stripe::setApiKey($stripeSK);

        $stripe = new \Stripe\StripeClient($stripeSK);
        
        try {
            $customer = $stripe->customers->create([
                'email' => 'test@test.com',
                'description' => 'first customer',
                'source' => 'token',
            ]);

            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => '2000',
                'currency' => 'usd',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'description' => 'charge',
                'customer' => $customer,
            ]);
        
            dump($request->request->get('payment-form')['id']);

            return $this->json(['clientSecret' => $paymentIntent->client_secret]);
        } catch (Error $e) {
            http_response_code(500);
            return new Response(json_encode(['error' => $e->getMessage()]));
        }
            
        // $charge = $stripe->charges->create([
        //     'amount' => 2000,
        //     'currency' => 'usd',
        //     'description' => 'first charge',
        //     'customer' => $customer,
        // ]);

        // return $charge;
    }
}