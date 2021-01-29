<?php

namespace App\Controller;

use App\Entity\Contacto;
use App\Entity\Enlace;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUsername()]);
        $contacto = $em->getRepository(Contacto::class)->find($user->getContacto());

        $contactos = $em->getRepository(Enlace::class)->findBy(['idUsuario' => $user->getId()]);
        $contactosFinal = null;

        $usuarios = $em->getRepository(User::class)->findAll();
        $usuariosFinal = null;

        if (count($contactos) != 0) {
            $contactosFinal = array();
            foreach ($contactos as $item) {
                array_push($contactosFinal,$em->getRepository(Contacto::class)->find($item->getIdContacto()));
            }

            $factor = 0;
            foreach ($contactosFinal as $contactoAux) {
                $factor = $factor + ($contactoAux->getPContagio() * $contactoAux->getPContacto());
                $factor = $factor / (count($contactosFinal));
            }

            $contacto->setPContagio($factor);
            $contacto->setPContacto($factor);
            $em->flush();
        }

        if (count($usuarios) != 0) {
            $usuariosFinal = array();
            foreach ($usuarios as $usuario) {
                $aux = $em->getRepository(Contacto::class)->find($usuario->getContacto());
                if ($aux->getId() != $user->getId()){
                    array_push($usuariosFinal,$aux);
                }
            }

            foreach ($usuarios as $item) {
                if ($item->getInfectado() == 1 && ($em->getRepository(Enlace::class)->findOneByValues($user->getId(),$item->getContacto()) != null)) {
                    $name = ($em->getRepository(Contacto::class)->find($item->getContacto()))->getNombre();
                    $this->addFlash('error','EL usuario ' . $name . " se ha contagiado!");
                }
            }
        }

        return $this->render('dashboard/index.html.twig', [
            'contacto' => $contacto,
            'user' => $user,
            'contactos' => $contactosFinal,
            'usuarios' => $usuariosFinal
        ]);
    }

    /**
     * @Route("/dashboard/actualizar-infeccion/{id}", name="actualizar-infeccion")
     */
    public function actualizarInfeccion(Request $request, $id)
    {
        $default = ['message' => 'mensaje'];
        $form = $this->createFormBuilder($default)
            ->add('Infectado', ChoiceType::class, [
                'choices' => [
                    'Si' => 1,
                    'No' => 0,
                ]
            ])
            ->add('Actualizar', SubmitType::class, [
                'attr' => ['class' => 'btn btn-sm btn-primary pull-right']
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $user =  $em->getRepository(User::class)->find($id);
                $user->setInfectado($form['Infectado']->getData());
                $contacto = $em->getRepository(Contacto::class)->find($user->getContacto());
                $contacto->setPContagio(1);
                $contacto->setPContacto(1);
                $em->flush();
                $this->addFlash('success','Infección actualizada!');
                return $this->redirectToRoute('dashboard');
            } catch (\Exception $exception) {
                $this->addFlash('error','Algo salio mal!');
                return $this->redirectToRoute('dashboard');
            }
        }

        return $this->render('dashboard/actualizar-infeccion.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/dashboard/actualizar-contacto/{id}",name="actualizar-contacto")
     */
    public function actualizarContacto(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        if ($em->getRepository(User::class)->findOneBy(['contacto' => $id]) != null) {
            $this->addFlash('error','No se pueden editar usuarios!');
            return $this->redirectToRoute('dashboard');
        } else {
            $contacto = $em->getRepository(Contacto::class)->find($id);

            $default = ['message' => 'mensaje'];
            $form = $this->createFormBuilder($default)
                ->add('nombre', null, [
                    'attr' => ['value' => $contacto->getNombre()]
                ])
                ->add('edad', null, [
                    'attr' => ['value' => $contacto->getEdad()]
                ])
                ->add('sexo', ChoiceType::class,[
                    'choices' => [
                        'Femenino' => 'Femenino',
                        'Masculino' => 'Masculino',
                    ],
                    'attr' => ['value' => $contacto->getSexo()]
                ])
                ->add('p_contagio', null, ['attr' => ['value' => $contacto->getPContagio()]])
                ->add('p_contacto', null, ['attr' => ['value' => $contacto->getPContacto()]])
                ->add('Registrar', SubmitType::class, [
                    'attr' => ['class' => 'btn btn-sm btn-primary pull-right']
                ])
                ->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $contacto->setNombre($form['nombre']->getdata());
                $contacto->setEdad($form['edad']->getdata());
                $contacto->setSexo($form['sexo']->getdata());
                $contacto->setPContagio($form['p_contagio']->getdata());
                $contacto->setPContacto($form['p_contacto']->getdata());
                $em->flush();
                $this->addFlash('success','Contacto actualizado!');
                return $this->redirectToRoute('dashboard');
            }
        }

        return $this->render('registro/registroinicial.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
