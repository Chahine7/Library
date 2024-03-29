<?php

namespace App\Controller\Admin;

use App\Entity\Editor;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;



class EditorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Editor::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
