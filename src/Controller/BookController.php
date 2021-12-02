<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Borrow;
use App\Entity\User;
use App\Form\BookType;
use App\Form\BookBackForm;
use App\Form\BorrowBookType;
use App\Form\SearchBookType;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @IsGranted("ROLE_USER")
 */

#[Route('/book')]
class BookController extends AbstractController
{


    #[Route('/', name: 'book_index', methods: ['GET'])]
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/new', name: 'book_new', methods: ['GET','POST'],priority: 2)]
    public function new(Request $request,string $photoDir): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($photo = $form['photo']->getData()) {

                $filename = bin2hex(random_bytes(6)).'.'.$photo->guessExtension();

try {

    $photo->move($photoDir, $filename);

} catch (FileException $e) {

// unable to upload the photo, give up

}
$book->setPhotoFilename($filename);
}

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('book/new.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'book_delete', methods: ['POST'])]
    public function delete(Request $request, Book $book): Response
    {

        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('book_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'book_show',priority: 1)]

    public function show(UserRepository $userRepo,Request $request,Book $book,NotifierInterface $notifier)

    {
        $borrow = new Borrow();
        $form = $this->createForm(BorrowBookType::class, $borrow);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
             $borrow->setUsers($this->getUser());
            $borrow->setBooks($book);
            $borrow->getBooks()->setStockQuantity($borrow->getBooks()->getStockQuantity()-1);
            $borrow->setIsBack(false);
            $entityManager = $this->getDoctrine()->getManager();
            if ($borrow->getReturnedAt()>$borrow->getBorrowedAt()) {
                $notification = (new Notification('new borrow'))
                    ->content('You just borrowed '. $borrow->getBooks($book).' Book!
                    Enjoy Your Read And Please return It before '. $borrow->getReturnedAt()->format("m/d/y") .' Otherwise you will be penalised..!')
                    ->importance(Notification::IMPORTANCE_HIGH);
                $recipent = new Recipient(
                    $borrow->getUsers()->getEmail(),
                )    ;
                $notifier->send($notification,$recipent);
                $entityManager->persist($borrow);
                $entityManager->flush();
                $this->addFlash('success','Enjoy your read');
                return $this->redirectToRoute('book_index',['id' => $book->getId()]);

        }else{
                $this->addFlash('error','Please Check The Returned At Date!');
            }
        }

        return $this->render('book/show.html.twig', [
            'book' => $book,
            'Borrow_form' => $form->createView(),
        ]);
    }
    #[Route('/{id}/edit', name: 'book_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Book $book,BookRepository $bookRepository,string $photoDir): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($photo = $form['photo']->getData()) {

                $filename = bin2hex(random_bytes(6)).'.'.$photo->guessExtension();

                try {

                    $photo->move($photoDir, $filename);

                } catch (FileException $e) {

// unable to upload the photo, give up

                }
                $book->setPhotoFilename($filename);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('book/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }



    //$auteur=$repAuteur->findOneBy(['prenon'=>'Richard','nom'=>'stallman']);
    // $livres=$auteur->getLivres();
    // $livre=$livres[0];
    // $nomediteur = $livre->getEditeur()->getNomEditeur();

    /**
     * @Route("/search", name="search")
     */
    public function searchBook(Request $request)
    {
        $books = null;
        $form = $this->createForm(SearchBookType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookPrice = $form->getData()["price"];
            $bookCategory = $form->getData()["category"];
            $bookStock = $form->getData()["StockQuantity"];
            //$books = $this->getDoctrine()->getManager()->getRepository(Book::class)->findByCategory(["category" => $bookCategory]);
            $books = $this->getDoctrine()->getManager()->getRepository(Book::class)->findByPrixPage10Trie(["price" => $bookPrice],["StockQuantity" => $bookStock],["categorie" =>$bookCategory]);
        }
        return $this->render('book/search.html.twig', [
            'books' => $books,
            'form' => $form->createView(),
        ]);
    }
}