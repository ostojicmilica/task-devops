<?php


namespace App\Controller;


use App\DocumentManager\ExchangeRate;
use App\DocumentManager\ExchangeRatesHistory;
use Doctrine\ODM\MongoDB\DocumentManager as DocumentManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Zend\XmlRpc\Server;

class ExchangeRatesXMLController
{

    /**
     * @Route("/xml/{currencyIn}/{currencyOut}", methods={"GET"})
     * @param DocumentManager $dm
     * @return Response
     */
    public function exchageRateByCurrencies(DocumentManager $dm, string $currencyIn, string $currencyOut)
    {

        $rate = $dm->getRepository(ExchangeRate::class)->findByName($currencyIn, $currencyOut);

//        $server = new Server;
//        $server->setClass($this->get('XMLRPCServer'));

        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');
        ob_start();
//        $server->handle();
        $content = "<xml><rate>" . $rate . "</rate></xml>";
        $response->setContent($content);
        return $response;
    }

    /**
     * @Route("/xml/history/{currencyIn}/{currencyOut}/{date}", methods={"GET"})
     * @param DocumentManager $dm
     * @return Response
     */
    public function exchageRateByCurrenciesByDate(DocumentManager $dm, string $currencyIn, string $currencyOut, $date)
    {

        $rate = $dm->getRepository(ExchangeRatesHistory::class)->findByName($currencyIn, $currencyOut, $date);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');
        ob_start();
        $content = "<xml><rate>" . $rate . "</rate></xml>";
        $response->setContent($content);
        return $response;
    }
}