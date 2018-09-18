<?php

namespace App\Controller;

use App\Entity\Buyers;
use App\Entity\OrderDetails;
use App\Entity\CommandOrder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;



class CommandController extends AbstractController
{
    /**
     * @Route("/command", name="command")
     * @Method({"GET"})
     */
    public function index(Request $request)
    {
        $maxResults = !empty($request->query->get("maxResults")) ? $request->query->get("maxResults") : "20";
        $commands = $this->getDoctrine()
            ->getRepository(CommandOrder::class)
            ->findByMaxResults($maxResults);

        return JsonResponse::create($commands, 200);
    }

    /**
     * @Route("/addCommand", name="add_command")
     * @Method({"POST"})
     */
    public function addCommand(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Rbuyers = $this->getDoctrine()->getRepository(Buyers::class);
        $jCommand = $request->request->get('Command');
        $oCommand =  json_decode($jCommand);
        $buyers = new Buyers();
        $order = new CommandOrder();
        if (!$client = $Rbuyers ->findOneByMail($oCommand->Acheteur->Email)) {
            $buyers->setCivility($oCommand->Acheteur->Civilite);
            $buyers->setLastName($oCommand->Acheteur->Nom);
            $buyers->setFirstName($oCommand->Acheteur->Prenom);
            $buyers->setAge($oCommand->Acheteur->Age);
            $buyers->setMail($oCommand->Acheteur->Email);
            $entityManager->persist($buyers);
            $entityManager->flush();
        }
        else {
            $buyers = $client;
        }

        $cash = $oCommand->Infos_commande->Paiement_espece == "Oui" ? true:false;
        $order->setDate(new \DateTime($oCommand->Infos_commande->Jour));
        $order->setDelivery(new \DateTime($oCommand->Infos_commande->Horaire_livraison));
        $order->setPaymentCash($cash);
        $order->setBuyers($buyers);
        $entityManager->persist($order);
        $entityManager->flush();

        foreach ($oCommand->Details_commande as $key => $command) {
            foreach ($command as $key => $value) {
                $order_details = new OrderDetails();
                $order_details->setMeal($value->Repas);
                $order_details->setCivility($value->Civilite);
                $order_details->setLastName($value->Nom);
                $order_details->setFirstName($value->Prenom);
                $order_details->setAge($value->Age);
                $order_details->setRate($value->Tarif);
                $order_details->setIdOrder($order);
                $entityManager->persist($order_details);
                $entityManager->flush();
            }
        }

        return JsonResponse::create("ok", 200);
    }
}
