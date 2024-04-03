<?php

namespace App\Controller;

use App\Entity\Pen;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class PublishPenController extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $entityManager): Pen|\Symfony\Component\Form\FormInterface
    {
        $pen = new Pen();

        $form = $this->createForm(Pen::class, $pen);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $entityManager->persist($pen);
            $entityManager->flush();
            return $pen;
        }
        return $form;
    }
}
