<?php

namespace Customize\Controller;

use Stripe\Checkout\Session;
use Stripe\Stripe;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Eccube\Repository\OrderRepository;
use Eccube\Repository\CustomerRepository;
use Customize\Repository\UserRepository;
use Customize\Repository\CardRepository;
use Customize\Entity\User;
use Customize\Entity\Card;

class CheckoutController extends Controller
{

    /**
     * 
     * @Route("/check", name="check")
     * @Template("Checkout/checkout.html.twig")
     * @IsGranted("ROLE_USER", message="You must be logged")
     */
    public function index(OrderRepository $orderRepository,
                         CustomerRepository $customerRepository,
                         CardRepository $cardRepository)
    {
        /** @var User $user */
        $user = $this->getUser();

        $customer = $customerRepository->findOneBy(['id' => $user->getId()]);

        $order = $orderRepository->findBy(['Customer' => $customer]);

        $card = $cardRepository->findBy(['customerId' => $user->getId()]);

        return [
            'controller_name' => 'CheckoutController',
            'orders' => $order,
            'cards' => $card,
        ];
    }

    /**
     * 
     * @Route("/send-checkout", name="send-checkout")
     */
    public function check(Request $request, UserRepository $userRepository, 
                        CustomerRepository $customerRepository,
                        CardRepository $cardRepository): Response
    {
        if ($request->isMethod('POST')) {
            $token = $request->request->get('token');
            $totalPrice = $request->request->get('total_price');

            $stripeSK = $this->getParameter('eccube_stripe_sk');
            $stripe = new \Stripe\StripeClient($stripeSK);

            /** @var User $user */
            $user = $this->getUser();

            $user_checkout = $userRepository->findOneBy(['customerId' => $user->getId()]);

            $user_customer = $customerRepository->findOneBy(['id' => $user->getId()]);
            $name = $user_customer->getName01() . ' ' . $user_customer->getName02();

            if (!$user_checkout) {
                $user_checkout = new User();
                $user_checkout->setCustomerId($user->getId());
                
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user_checkout);
                $entityManager->flush();

                $customer = $stripe->customers->create([
                    'name' => $name,
                    'email' => $user->getEmail(),
                    'source' => $token,
                ]);
                
                $card = new Card();
                $card->setCustomerId($user->getId());
                $card->setTokenId($token);

                $user_checkout->setStripeCustomerId($customer->id);

                $em1 = $this->getDoctrine()->getManager();
                $em1->persist($user_checkout);
                $em1->flush();

                $em2 = $this->getDoctrine()->getManager();
                $em2->persist($card);
                $em2->flush();

                $stripe->charges->create([
                    'amount' => $totalPrice * 10,
                    'currency' => 'usd',
                    'description' => 'First Charge',
                    'customer' => $user_checkout->getStripeCustomerId(),
                ]);
            } else {
                $customer = $stripe->customers->retrieve($user_checkout->getStripeCustomerId());

                $cards = $cardRepository->findBy(['customerId' => $user->getId()]);

                $source_api = $stripe->sources->retrieve($token);

                $flug = 1;

                foreach ($cards as $card) {
                    $card_api = $stripe->sources->retrieve($card->getTokenId());
                    if ($card_api->card->fingerprint == $source_api->card->fingerprint &&
                        $card_api->card->exp_month == $source_api->card->exp_month &&
                        $card_api->card->exp_year == $source_api->card->exp_year) 
                    {
                        $card->setTokenId($token);

                        $em = $this->getDoctrine()->getManager();
                        $em->persist($card);
                        $em->flush();

                        $stripe->charges->create([
                            'amount' => $totalPrice * 10,
                            'currency' => 'usd',
                            'description' => 'Still this charge',
                            'customer' => $customer->id,
                        ]);

                        $flug = 0;
                        break;
                    }
                } 

                if ($flug) {                    
                    $customer->source = $token;
                    $customer->save();

                    $card = new Card();
                    $card->setCustomerId($user->getId());
                    $card->setTokenId($token);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($card);
                    $em->flush();

                    $stripe->charges->create([
                        'amount' => $totalPrice * 10,
                        'currency' => 'usd',
                        'description' => 'New charge',
                        'customer' => $customer->id,
                    ]);
                }
            }
            $this->addFlash('success', 'Completed Charge!');
        }
        
        return $this->redirectToRoute('check');
    }
}