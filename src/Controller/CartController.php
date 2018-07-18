<?php

namespace App\Controller;


use App\Entity\Payment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\{Request, Response};
use App\Utils\ProductHelper;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Entity\Product;

/**
 * Class CartController
 * @package AppBundle\Controller
 *
 * @Route("{_locale}/cart")
 */
class CartController extends Controller
{
    /**
     * @Route("/add/{id}")
     * @Method("POST")
     *
     * @param $id
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function add($id, Request $request, SessionInterface $session, TranslatorInterface $translator)
    {
        if (!$session->has('cart')) {
            $session->set('cart', []);
        }

        $cart = $session->get('cart');

        $quantity = (int) $request->request->get('quantity');
        $color = $request->request->get('color');
        $size = $request->request->get('size');

        if ($quantity <= 0 || !in_array($color, Product::getAvailableColors())) {

            /** @var Product $product */
            $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

            $this->addFlash(
                'error',
                $translator->trans('msg.error-to-cart-params')
            );

            return $this->redirectToRoute('app_index_catalogue', [
                'catAlias'  => $product->getCategory()->getParent(),
                'subCatAlias'   => $product->getCategory(),
                'itemId'    => $product->getId()
            ]);
        }

        $cart[$id] = [
            'quantity'  => $quantity,
            'color'     => $color,
            'size'      => $size
        ];

        $session->set('cart', $cart);
        return $this->redirectToRoute('app_cart_index');
    }

    /**
     * @Route("/remove/{id}")
     * @Method("GET")
     *
     * @param $id
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function remove($id, SessionInterface $session)
    {
        $cart = $session->get('cart');
        unset($cart[$id]);

        $session->set('cart', $cart);
        return $this->redirectToRoute('app_cart_index');
    }

    /**
     * @Route("/", name="app_cart_index")
     * @Method("GET")
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        if (!$session->has('cart') || empty($session->get('cart'))) {
            return $this->render('app/cart/index.html.twig', [
                'cart'  => []
            ]);
        }

        $cart = $session->get('cart');
        $products = $this->getProductsFromSession();

        //$mobileDetector = $this->get('mobile_detect.mobile_detector');
        //$view = $mobileDetector->isMobile() ? '_mobile' : '';

        return $this->render('app/cart/index.html.twig', [
            'cart'  => ProductHelper::computeCard($products, $cart, $request->getLocale())
        ]);
    }

    /**
     * @Route("/create-payment", name="app_cart_create-payment")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createPayment(Request $request): Response
    {
        $paymentDetails = $request->request->get('paymentDetails');

        if (!$paymentDetails) {
            $this->redirectToRoute('app_index_index');
        }

        $amount = 0;
        foreach ($paymentDetails['transactions'] as $transaction) {
            $amount += $transaction['amount']['total'];
        }

        $address =
            $paymentDetails['payer']['payer_info']['shipping_address']['line1'] . " <br> " .
            $paymentDetails['payer']['payer_info']['shipping_address']['postal_code'] . ' ' . $paymentDetails['payer']['payer_info']['shipping_address']['city'] . " <br> " .
            $paymentDetails['payer']['payer_info']['shipping_address']['country_code']
            ;

        $products = $this->getProductsFromSession();

        $productsContent = '<ul>';
        foreach ($products as $product) {
            $url = $this->generateUrl('app_index_catalogue', [
                'catAlias' => $product->getCategory()->getParent()->getAlias($request->getLocale()),
                'subCatAlias' => $product->getCategory()->getAlias($request->getLocale()),
                'itemId' => $product->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
            $productsContent .= '<li><a href="'.$url.'" target="_blank">'.$product->getTitle($request->getLocale()).'</a></li>';
        }

        $productsContent .= '</ul>';


        $payment = new Payment();
        $payment->setStatus(Payment::STATUS_INIT)
            ->setBuyerName($paymentDetails['payer']['payer_info']['shipping_address']['recipient_name'])
            ->setBuyerEmail($paymentDetails['payer']['payer_info']['email'])
            ->setBuyerAddress($address)
            ->setPaymentId($paymentDetails['id'])
            ->setAmount($amount)
            ->setPaypalPaymentDetails($paymentDetails)
            ->setProductsContent($productsContent)
            ->setProductsIds($this->getProductsIds());

        $em = $this->getDoctrine()->getManager();

        $em->persist($payment);
        $em->flush();

        return new Response('success');
    }

    /**
     * @Route("/confirm-payment", name="app_cart_confirm-payment")
     * @Method("GET")
     *
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @param TranslatorInterface $translator
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function confirmPayment(Request $request, \Swift_Mailer $mailer, TranslatorInterface $translator, SessionInterface $session)
    {
        $paymentId = $request->query->get('paymentId');
        $cart = $this->get('session')->get('cart');

        if (!$paymentId || empty($cart)) {
            return $this->redirectToRoute('app_index_index');
        }

        /** @var Payment $payment */
        $payment = $this->getDoctrine()->getRepository(Payment::class)
            ->findOneBy(['paymentId' => $paymentId]);
        $payment->setStatus(Payment::STATUS_CONFIRM);
        $this->getDoctrine()->getManager()->flush();

        $productsDetails = ProductHelper::computeCard(
            $this->getProductsFromSession(),
            $cart,
            $request->getLocale());

        //Create email
        $body = $this->renderView('app/partials/email-buyer.html.twig', [
            'cart' => $productsDetails,
            'payment' => $payment
            ]
        );

        $message = (new \Swift_Message($translator->trans('payment.email-subject')))
            ->setFrom($this->container->getParameter('mailer_from'), 'FABINI-REZ.com')
            ->setTo($payment->getBuyerEmail())
            ->setBody($body, 'text/html');

        $messageAdmin = (new \Swift_Message('Verkauf bei FABINI-REZ.com - Kopie von EMail'))
            ->setFrom($this->container->getParameter('mailer_from'), 'FABINI-REZ.com')
            ->setTo($this->container->getParameter('mailer_sold_item'))
            ->setBody($body, 'text/html');

        $mailer->send($message);
        $mailer->send($messageAdmin);

        $session->set('cart', []); //Empty session cart

        return $this->render('app/cart/confirmPayment.html.twig', [
            'cart'      => $productsDetails,
            'payment'   => $payment
        ]);
    }

    /**
     * @Route("/cancel-payment", name="app_cart_cancel-payment")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function cancelPayment(Request $request): Response
    {
        return $this->redirectToRoute('app_cart_index');
    }


    /**
     * @return Product[]
     */
    private function getProductsFromSession()
    {
        return $this->getDoctrine()
            ->getManager()
            ->getRepository(Product::class)
            ->findByIds($this->getProductsIds());
    }

    /**
     * @return array
     */
    private function getProductsIds()
    {
        $session = $this->get('session');

        if (!$session->has('cart') || empty($session->get('cart'))) {
            return [];
        }

        $cart = $session->get('cart');

        return array_keys($cart);
    }

}