<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Entity\Borrow;
use App\Form\BorrowType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BorrowCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Borrow::class;

    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('ID')->hideOnForm(),
            AssociationField::new('users'),
            AssociationField::new('books'),
            DateField::new('borrowedAt'),
            DateField::new('returnedAt'),
            BooleanField::new("isBack"),
        ];
    }




    /*  public function createEntity(string $entityFqcn)
      {


          $borrow = new Borrow();
          $borrow->setBooks($borrow->getBooks())->getBooks()->setStockQuantity($borrow->getBooks()->getStockQuantity()-1);

          return $borrow;
      }
    */

/*public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
{

    $borrow = new Borrow();
    $borrow = $entityManager->createQuery("
    UPDATE  App\Entity\Book b set b.StockQuantity = b.StockQuantity - 1 where b.id = id
");
     $entityManager->persist($borrow);
     $entityManager->flush();
}
*/

}