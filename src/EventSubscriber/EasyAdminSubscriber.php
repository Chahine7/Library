<?php

namespace App\EventSubscriber;

use App\Entity\Borrow;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class EasyAdminSubscriber implements EventSubscriberInterface
{

    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['addUser'],
            AfterEntityPersistedEvent::class => ['decreaseQuantity'],
            BeforeEntityDeletedEvent::class => ['increaseQuantity'],
            BeforeEntityUpdatedEvent::class => ['updateUser'],

        ];
    }

    public function updateUser(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }
        $this->setPassword($entity);
    }

    public function addUser(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }
        $this->setPassword($entity);
    }

    /**
     * @param User $entity
     */
    public function setPassword(User $entity): void
    {
        $pass = $entity->getPassword();

        $entity->setPassword(
            $this->passwordHasher->hashPassword(
                $entity,
                $pass
            )
        );
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
    public function decreaseQuantity(AfterEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Borrow)) {
            return;
        }
       $this->setStockQuantityMoin($entity);
    }

    /**
     * @param Borrow $entity
     */

   public function setStockQuantityMoin(Borrow $entity): void
    {
        $entity->getBooks()->setStockQuantity($entity->getBooks()->getStockQuantity()-1);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function increaseQuantity(BeforeEntityDeletedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Borrow)) {
            return;
        }
        $this->setStockQuantityPlus($entity);
    }

    /**
     * @param Borrow $entity
     */

    public function setStockQuantityPlus(Borrow $entity): void
    {
        $entity->getBooks()->setStockQuantity($entity->getBooks()->getStockQuantity()+1);
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

}