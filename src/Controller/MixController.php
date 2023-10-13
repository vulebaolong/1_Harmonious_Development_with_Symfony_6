<?php

namespace App\Controller;

use App\Entity\VinylMix;
use App\Repository\VinylMixRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MixController extends AbstractController
{
    #[Route('/mix/new')]
    public function new(EntityManagerInterface $entityManager): Response
    {
        $mix = new VinylMix();
        $mix->setTitle('ddaay laf title');
        $mix->setDescription('ddaay laf description');
        $genre = ['pop', 'rock'];
        $mix->setGenre($genre[array_rand($genre)]);
        $mix->setTrackCount(rand(5, 20));
        $mix->setVotes(rand(-50, 50));

        // tách ra 2 phương thức để khai bóa nhiều dữ liệu và save ở lần cuối
        $entityManager->persist($mix); // khai báo cho doctrine sẽ thực hiện với dữ liệu nào
        $entityManager->flush(); // save database

        return new Response(sprintf(
            'id: %d, Count: %d, , Genre: %s',
            $mix->getId(),
            $mix->getTrackCount(),
            $mix->getGenre(),
        ));

    }

    #[Route('/mix/{slug}', name: 'app_mix_show')]
    public function show(VinylMix $mix): Response
    {
        return $this->render('mix/show.html.twig', [
            'mix' => $mix
        ]);
    }

    #[Route('/mix/{id}/vote', name: 'app_mix_vote', methods: ["POST"])]
    public function vote(VinylMix $mix, Request $request, EntityManagerInterface $entityManager): Response
    {
        $direction = $request->request->get('direction', 'up');
        if ($direction === 'up') {
            $mix->upVoute();
        }

        if ($direction === 'down') {
            $mix->downVoute();
        }

        // lưu vào database
        $entityManager->flush();

        // Mở một thông báo cho client, phải thêm vòng for bên html lặp => app.flashes('success')
        $this->addFlash('success', 'Tin nhắn Vote thành công');

        // chuyển hướng
        return $this->redirectToRoute('app_mix_show', [
            'slug' => $mix->getSlug()
        ]);

    }
}