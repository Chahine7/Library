<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Borrow;
use App\Entity\Category;
use App\Entity\Editor;
use App\Entity\User;
use App\Form\EditProfileType;
use App\Form\SearchBorrowedBookType;
use App\Repository\BookRepository;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @IsGranted("ROLE_ADMIN")
 */

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {


return  $this->render('Admin/Dashboard.html.twig');
    }
    /**
     * @Route("/admin/search", name="search_borrows")
     */
    public function search(Request $request): Response
    {
        $borrows = null;
        $form = $this->createForm(SearchBorrowedBookType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userid = $form->getData()["user"];
            //  $bookid = $form->getData()["book"];
            //$books = $this->getDoctrine()->getManager()->getRepository(Book::class)->findByCategory(["category" => $bookCategory]);
            $borrows = $this->getDoctrine()->getManager()->getRepository(Borrow::class)->findByUser(["user" => $userid]);
            //,["book" => $bookid]);
        }
        return $this->render('borrow/SearchBorrowedBook.html.twig', [
            'borrows' => $borrows,
            'form' => $form->createView(),
        ]);
    }
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Library')
            ;
    }
    public function configureMenuItems(): iterable
    {

        yield MenuItem::linkToDashboard('Dashboard','fa fa-home');
        yield MenuItem::section('All Categories','fa fa-list');
        yield MenuItem::linkToCrud('categories', 'fa fa-list-alt', Category::class);
        yield MenuItem::linkToCrud('add category', 'fa fa-plus', Category::class)->setAction('new');

        yield MenuItem::section('Entities','fa fa-address-book');
        yield MenuItem::linkToCrud('Books', 'fa fa-book', Book::class);
        yield MenuItem::linkToCrud('Authors', 'fa fa-user-circle', Author::class);
        yield MenuItem::linkToCrud('Editors', 'fa fa-user-circle', Editor::class);



        yield MenuItem::section('Operations' ,'fa fa-tasks');
        yield MenuItem::linkToCrud('borrows', 'fa fa-hand-lizard-o', Borrow::class);

        yield MenuItem::section('Users' ,'fa fa-users');
        yield MenuItem::linkToCrud('Users', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('add User', 'fa fa-plus', User::class)->setAction('new');
    }


    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)

           ->setAvatarUrl("https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.0.5/img/avatar5.png")
           ->displayUserAvatar(true)
//            ->displayUserName(false)
//
->addMenuItems([
    MenuItem::linkToRoute('Profile','fa fa-id-card','admin_profile'),
//    MenuItem::linkToRoute('Settings', 'fa fa-user-cog', '...', ['...' => '...']),
                MenuItem::section(),
            ])
            ;
    }

}