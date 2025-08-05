<?php

namespace App\Controller;

use App\Dto\TopicCreateDto;
use App\Dto\TopicEditDto;
use App\Entity\Topic;
use App\Form\TopicCreateType;
use App\Form\TopicEditType;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/topic')]
final class TopicController extends AbstractController
{
    #[Route(name: 'app_topic_index', methods: ['GET'])]
    public function index(TopicRepository $topicRepository): Response
    {
        return $this->render('topic/index.html.twig', [
            'topics' => $topicRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_topic_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ObjectMapperInterface $objectMapper): Response
    {
        $topicDto = new TopicCreateDto();
        $form = $this->createForm(TopicCreateType::class, $topicDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $topic = $objectMapper->map($topicDto, Topic::class);

            $entityManager->persist($topic);
            $entityManager->flush();

            return $this->redirectToRoute('app_topic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('topic/new.html.twig', [
            'topic' => $topicDto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_topic_show', methods: ['GET'])]
    public function show(Topic $topic): Response
    {
        return $this->render('topic/show.html.twig', [
            'topic' => $topic,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_topic_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Topic $topic, EntityManagerInterface $entityManager, ObjectMapperInterface $objectMapper): Response
    {
        $topicDto = new TopicEditDto();
        $topicDto->id = $topic->getId();
        $topicDto->name = $topic->getName();

        $form = $this->createForm(TopicEditType::class, $topicDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $objectMapper->map($topicDto, $topic);

            $entityManager->flush();

            return $this->redirectToRoute('app_topic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('topic/edit.html.twig', [
            'topic' => $topicDto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_topic_delete', methods: ['POST'])]
    public function delete(Request $request, Topic $topic, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$topic->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($topic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_topic_index', [], Response::HTTP_SEE_OTHER);
    }
}
