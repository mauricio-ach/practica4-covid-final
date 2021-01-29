<?php

namespace App\Controller;

use App\Entity\Contacto;
use App\Entity\Enlace;
use App\Entity\User;
use App\Form\ContactoType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistroController extends AbstractController
{
    /**
     * @Route("/registro", name="registro")
     */
    public function registrar(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if($em->getRepository(User::class)->findOneBy(['email' => $form['email']->getData()]) != null) {
                $this->addFlash('warning','Usuario ya registrado!');
                return $this->redirectToRoute('registro');
            } else {
                try {
                    $user->setRoles(['ROLE_USER']);
                    $user->setContacto(0);
                    $user->setPassword($passwordEncoder->encodePassword($user,$user->getPassword()));

                    $em->persist($user);
                    $em->flush();
                    $this->addFlash('success','Usuario registrado!');
                    return $this->redirectToRoute('registro-inicial', ['user' => $user->getId()]);
                } catch (\Exception $e) {
                    $this->addFlash('error','Algo salio mal!');
                    return $this->redirectToRoute('registro');
                }
            }
        }

        return $this->render('registro/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/registro/contacto-inicial/{user}", name="registro-inicial")
     */
    public function registroInicial(Request $request, $user) {

        $contacto = new Contacto();
        $form = $this->createForm(ContactoType::class,$contacto);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $contacto->setPContagio(1);
                $contacto->setPContacto(1);

                $em->persist($contacto);
                $em->flush();

                $user = $em->getRepository(User::class)->find($user);
                $user->setContacto($contacto->getId());
                $em->flush();
                $this->addFlash('success','Contacto registrado!');
                return $this->redirectToRoute('app_login');
            } catch (\Exception $exception) {
                $this->addFlash('error','Algo salio mal!');
                return $this->redirectToRoute('registro-inicial', ['user' => $user->getId()]);
            }

        }

        return $this->render('registro/registroinicial.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/dashboard/registrar-contacto/{id}", name="registro-contacto")
     */
    public function registarContacto(Request $request, $id)
    {
        $contacto = new Contacto();
        $form = $this->createForm(ContactoType::class,$contacto);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();

                $em->persist($contacto);
                $em->flush();

                $enlace = new Enlace();

                $enlace->setIdUsuario($id);
                $enlace->setIdContacto($contacto->getId());

                $em->persist($enlace);
                $em->flush();
                $this->addFlash('success','Contacto registrado');
                return $this->redirectToRoute('dashboard');
            } catch (\Exception $exception) {
                $this->addFlash('error','Algo salio mal!');
                return $this->redirectToRoute('dashboard');
            }
        }

        return $this->render('registro/registroinicial.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/dashboard/registrar-enlace/{user},{contact}", name="registro-enlace")
     */
    public function registrarEnlace(Request $request, $user, $contact) {
        try {

            $em = $this->getDoctrine()->getManager();

            if ($em->getRepository(Enlace::class)->findOneByValues($user,$contact) != null) {
                $this->addFlash('error','Ya había sido agregado!');
                return $this->redirectToRoute('dashboard');
            }

            $enlace = new Enlace();

            $enlace->setIdUsuario($user);
            $enlace->setIdContacto($contact);

            $em->persist($enlace);
            $em->flush();

        } catch (\Exception $exception) {
            $this->addFlash('error','Algo salio mal!' . $exception->getMessage());
            return $this->redirectToRoute('dashboard');
        }

        $this->addFlash('success','Contacto registrado');
        return $this->redirectToRoute("dashboard");
    }
}
