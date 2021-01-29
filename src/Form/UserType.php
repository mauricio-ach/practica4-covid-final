<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('password')
            ->add('telefono')
            ->add('codigoPostal')
            ->add('infectado', ChoiceType::class, [
                'choices' => [
                    'Si' => 1,
                    'No' => 0,
                ]
            ])
            ->add('Registrar', SubmitType::class, [
                'attr' => ['class' => 'btn btn-sm btn-primary pull-right']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
