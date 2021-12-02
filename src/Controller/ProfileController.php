<?php

namespace App\Controller;

use App\Controller\Admin\DashboardController;
use App\Entity\Book;
use App\Entity\User;
use App\Form\EditProfileType;
use App\Form\UserType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends DashboardController
{
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
        #[Route('/profile', name: 'profile')]
    public function editProfile(Request $request): Response
    {
        $user=$this->getUser();
        $form = $this->createForm(EditProfileType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {

                    $em = $this->getDoctrine()->getManager();
                    $em->flush();
                $this->addFlash("message", "Your Profile Is Updated!");
                return $this->redirectToRoute("profile");
            }

        return $this->render('profile/index.html.twig', [
            'form' =>$form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */

    #[Route('/admin/profile', name: 'admin_profile')]

    public function profile(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'Profile Updated Successfully!');
           // echo "<div class='alert alert-success'></div>";
            return $this->redirectToRoute('admin_profile');
        }

        return $this->render('Admin/Profile.html.twig', [
            'form' =>$form->createView(),
            ]);
    }
    #[Route('profile/passwordEdit',name: 'Edit_Password')]
    public function EditPassword(Request $request, UserPasswordHasherInterface $passwordHasher)
    {

        if ($request->isMethod('POST')) {
            $user = $this->getUser();
            if ($request->request->get('password') == $request->request->get('password2')) {
                $user->setPassword($this->passwordHasher->hashPassword($user, $request->request->get('password')));
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $this->addFlash("message", "Password updated Successfully!");
                return $this->redirectToRoute("profile");
            } else {
                $this->addFlash("error", "Password Mismatch!");
            }
        }
        return $this->render('profile/EditPassword.html.twig');
    }

    #[Route('admin/profile/passwordEdit',name: 'Edit_Admin_Password')]
    public function EditAdminPassword(Request $request, UserPasswordHasherInterface $passwordHasher)
    {

        if ($request->isMethod('POST')) {
            $user = $this->getUser();
            if ($request->request->get('password') == $request->request->get('password2')) {
                $user->setPassword($this->passwordHasher->hashPassword($user, $request->request->get('password')));
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $this->addFlash("success","Password Updated Successfully!");
                return $this->redirectToRoute("admin_profile");
            }
            else{
                $this->addFlash("error","Password Mismatch!");
            }
        }

        return $this->render('Admin/EditPassword.html.twig');
    }
}
