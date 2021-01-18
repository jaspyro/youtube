<?php

namespace App\Controller;

use App\Entity\Youtube;
use App\Form\YoutubeType;
use App\Repository\YoutubeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class YoutubeController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index(Request $request, EntityManagerInterface $manager, YoutubeRepository $repository): Response
    {

        $youtube = new Youtube();
        $form = $this->createForm(YoutubeType::class, $youtube);

        //tester si le formulaire a été soumis
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // enregistrer la vidéo Youtube dans la base de données
            $manager->persist($youtube);//créer l'ordre Insert
            $manager->flush(); //envoyer l'ordre insert

            return $this->redirectToRoute('app_index');
        }

        return $this->render('youtube/index.html.twig', [
            'formYou' => $form->createView(),
            'youtubes' => $repository->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="app_play")
     */
    public function video($id, YoutubeRepository $repository): Response
    {
        $video = $repository->find($id);

        return $this->render('youtube/video.html.twig',[
            'video' => $video
        ]);
    }
}
