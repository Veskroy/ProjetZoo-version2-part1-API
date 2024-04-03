<?php

namespace App\Controller;

use App\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditTicketController extends AbstractController
{
    public function __invoke(Ticket $ticket): Ticket
    {
        $ticket->setDate(new \DateTimeImmutable());

        return $ticket;
    }
}