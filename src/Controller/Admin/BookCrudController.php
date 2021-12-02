<?php

namespace App\Controller\Admin;
use App\Entity\Book;
use App\Entity\Category;
use App\Form\BorrowBookType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use phpDocumentor\Reflection\Types\Collection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image;

class BookCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Book::class;

    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('ID')->hideOnForm(),
            TextField::new('title'),
            AssociationField::new('authors')
                ->onlyOnForms(),
            CollectionField::new('authors')
                ->hideOnForm(),
            IntegerField::new('isbn')->onlyWhenCreating(),
            MoneyField::new('price')->setCurrency('EUR')->onlyWhenCreating(),
            IntegerField::new('StockQuantity')
            ->formatValue(function ($StockQuantity){
                return $StockQuantity < 10 ? sprintf('%d **LOW STOCK**', $StockQuantity) : $StockQuantity;
            }),
            BooleanField::new("isAvailable"),
          //  AssociationField::new('borrows')->hideOnForm(),
           // CollectionField::new('borrows')->setEntryType(BorrowBookType::class),
            ImageField::new("photoFilename")->setUploadDir("/public/uploads/photos")->setLabel('photo')->onlyWhenCreating(),
            ImageField::new("photoFilename")->setBasePath("/uploads/photos")->setLabel('photo')->onlyOnIndex(),
            AssociationField::new('categorie'),
            AssociationField::new('editor','Editor')->onlyWhenCreating(),
            DateField::new('editedAt'),
        ];
    }
    public function configureActions(Actions $actions): Actions
    {

        $viewInvoice = Action::new('viewInvoice', 'Invoice', 'fa fa-file-invoice')
            // renders the action as a <a> HTML element
            ->displayAsLink()
            // renders the action as a <button> HTML element
            ->displayAsButton()
            // a key-value array of attributes to add to the HTML element
            ->setHtmlAttributes(['data-foo' => 'bar', 'target' => '_blank'])
            // removes all existing CSS classes of the action and sets
            // the given value as the CSS class of the HTML element
            ->setCssClass('btn btn-primary action-foo')
            // adds the given value to the existing CSS classes of the action (this is
            // useful when customizing a built-in action, which already has CSS classes)
            ->addCssClass('some-custom-css-class text-danger');



            return $actions
            // ...
            // you can reorder built-in actions..

            ->reorder(Crud::PAGE_INDEX, [Action::EDIT, Action::NEW, Action::DELETE])

           // ->disable(Crud::PAGE_NEW,Action::SAVE_AND_ADD_ANOTHER)

            ->add(Crud::PAGE_INDEX, Action::INDEX)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
          //  ->add(Crud::PAGE_INDEX, $viewInvoice->linkToUrl("https://en.wikipedia.org/wiki/URL"))
        //  ->setPermission(Action::NEW, 'ROLE_ADMIN')
          ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
            return $action->setIcon('fa fa-plus')->setLabel('Add Book');




        });


}
public function configureFilters(Filters $filters): Filters
{
return  $filters
    ->add(EntityFilter::new('categorie'));
}

    public function configureCrud(Crud $crud): Crud
{
 return $crud
     ->setEntityLabelInPlural('Books')
     ->setDefaultSort(['id' => 'ASC', 'title' => 'DESC'])
;
}
}
