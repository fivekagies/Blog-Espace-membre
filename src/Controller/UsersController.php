<?php
namespace App\Controller ;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersController extends Controller
{
    /**
     * @Route("/register",name="register_user")
     */
    public function register(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        $user= new User();
        $form=$this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $password=$passwordEncoder->encodePassword($user,$user->getPlainPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);
            $entityManager=$this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
           $this->addFlash('notice','compte crée avec succés! ');//message lors de la cretion du compte
            return $this->redirectToRoute('blog');
        }
        return $this->render('users/register.html.twig',['form'=>$form->createView()]);
    }



}
