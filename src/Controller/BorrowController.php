<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Borrow;
use App\Form\BookBackForm;
use App\Form\BorrowBookType;
use App\Form\BorrowType;
use App\Form\SearchBorrowedBookType;
use App\Repository\BorrowRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Notifier\Notification\Notification;

/**
 * @IsGranted("ROLE_USER")
 */

#[Route('/borrow')]
class BorrowController extends AbstractController
{

    #[Route('/', name: 'borrow_index', methods: ['GET'])]
    public function index(BorrowRepository $borrowRepository): Response
    {
        return $this->render('borrow/index.html.twig', [
            'borrows' => $borrowRepository->findBy(['users'=>$this->getUser()]),
        ]);
    }

    #[Route('/new', name: 'borrow_new', methods: ['GET','POST'])]
    public function new(Request $request,NotifierInterface $notifier): Response
    {
        $borrow = new Borrow();
        $form = $this->createForm(BorrowType::class, $borrow);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $borrow->getBooks()->setStockQuantity($borrow->getBooks()->getStockQuantity()-1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($borrow);
            $entityManager->flush();

            return $this->redirectToRoute('borrow_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('borrow/new.html.twig', [
            'borrow' => $borrow,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'borrow_show',methods: ['POST','GET'])]
    public function show(Request $request,Borrow $borrow,NotifierInterface $notifier)
    {
        $form = $this->createForm(BookBackForm::class, $borrow);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $borrow->setUsers($this->getUser());
            $borrow->setBooks($borrow->getBooks());
            $borrow->getBooks()->setStockQuantity($borrow->getBooks()->getStockQuantity() + 1);
            $entityManager = $this->getDoctrine()->getManager();
            if (($borrow->getIsBack() == true)&&($borrow->getReturnedAt()>$borrow->getBorrowedAt())) {
                $notification = (new Notification('Your Borrow is Ended'))
                    ->content('thank you for returning '. $borrow->getBooks().' Book!
                    We hope You enjoyed the books content..!')
                    ->importance(Notification::IMPORTANCE_HIGH);
                $recipent = new Recipient(
                    $borrow->getUsers()->getEmail(),
                )    ;
                $notifier->send($notification,$recipent);
                $entityManager->remove($borrow);
                $entityManager->flush();
                return $this->redirectToRoute('borrow_index', ['id' => $borrow->getId()]);
            }
            else{


                echo "<br><br><br><div class='alert alert-danger'>Please Click on <span style='font-size: medium' class='alert alert-primary'><b>Isback</b></span> If you want to end your borrow!</br> Or cheque the returned date..</div>";
            }
    }
            return $this->render('borrow/show.html.twig', [
                'borrow' => $borrow,
                'Book_back' => $form->createView(),
            ]);
        }


    #[Route('/{id}/edit', name: 'borrow_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Borrow $borrow): Response
    {
        $form = $this->createForm(BorrowType::class, $borrow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('borrow_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('borrow/edit.html.twig', [
            'borrow' => $borrow,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'borrow_delete', methods: ['POST'])]
    public function delete(Request $request, Borrow $borrow): Response
    {

        if ($this->isCsrfTokenValid('delete'.$borrow->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($borrow);
            $entityManager->flush();
        }

        return $this->redirectToRoute('borrow_index', [], Response::HTTP_SEE_OTHER);
    }



}
