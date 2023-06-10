<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client;

class DownloadController extends AbstractController
{
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => 'http://127.0.0.1:8000/api', // URL API
            'timeout'  => 5,
        ]);
    }

    public function getData()
    {
        $response = $this->httpClient->request('GET', '/api/users');

        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents(), true);
            return $data;
        }

        return null;
    }

    /**
     * @Route("/download-data", name="download_data", methods={"GET"})
     */
    public function downloadDataAsJson()
    {
        $data = $this->getData();

        if ($data !== null) {
            $jsonData = json_encode($data);

            // Download the file locally in the Downloads folder on the desktop
            $filePath = $_SERVER['HOME'] . '/Downloads/dataDict.json';

            file_put_contents($filePath, $jsonData);

            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/dictDownload", name="downloadJson", methods={"GET"})
     */
    public function index(): Response
    {
        $data = $this->getData();
        return $this->render('download/index.html.twig', [
            'controller_name' => 'DownloadController',
        ]);
    }
}
