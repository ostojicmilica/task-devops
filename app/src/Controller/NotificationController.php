<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Message\ChangeMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;

class NotificationController extends AbstractController
{

    /**
     * @param MessageBusInterface $bus
     *
     *
     * @Route("/notifyChange", methods={"GET"})
     * @return Response
     */

    public function postExchangeRate(MessageBusInterface $bus)
    {


        $bus->dispatch(new ChangeMessage('Exchange Rate Changed'));

        return new Response("OK");

    }

}