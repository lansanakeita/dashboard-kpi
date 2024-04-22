<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PasswordField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('username', 'Nom d\'utilisateur'),
            EmailField::new('email'),
            TextField::new('password', 'Mot de passe')
                ->onlyOnForms(),
            ChoiceField::new('roles', 'Rôles')
                ->allowMultipleChoices()
                ->setChoices([
                    'Client' => 'ROLE_CLIENT',
                    'Administrateur' => 'ROLE_ADMIN',
                ]),
        ];
    }
}
