<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Borrow;
use App\Entity\Category;
use App\Entity\Editor;
use App\Form\SearchBookType;
use App\Form\SearchBorrowedBookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'home_page')]
    public function index(): Response
    {
        $repEditor=$this->getDoctrine()->getRepository(Editor::class)->findAll();
        $repCat=$this->getDoctrine()->getRepository(Category::class)->findAll();

        $repBook=$this->getDoctrine()->getRepository(Book::class)->findAll();
        return $this->render('home_page/index.html.twig', [

            'editor' =>$repEditor,
            'category' =>$repCat,
            'book' =>$repBook,
        ]);
    }

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
