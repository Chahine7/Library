<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('ID')->hideOnForm(),
            TextField::new("name"),
            TextField::new("lastname")->hideOnIndex(),
            TelephoneField::new("PhoneNumber"),
            EmailField::new('Email'),
           // TextField::new("ROLE"),
            TextField::new('password')->setFormType(passwordType::class)->hideOnIndex()->hideWhenUpdating(),
            ArrayField::new('roles')->hideOnIndex(),
            ];
    }
public function configureActions(Actions $actions): Actions
{
    return parent::configureActions($actions); // TODO: Change the autogenerated stub
}
    /*public function persistEntity(EntityManagerInterface $em, $user): void
    {   $userPasswordHasherInterface = new UserPasswordHasherInterface();

        //parent::persistEntity($entityManager, $entityInstance); // TODO: Change the autogenerated stub
        $user->setPassword(
            $userPasswordHasherInterface->hashPassword(
                $user,
                $user->getPassword()
            )
        );
    }
    */


}
