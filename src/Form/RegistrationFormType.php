<?php

namespace App\Form;

use App\Entity\Persona;
use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('nombres', TextType::class,[
                'label'=>'Nombres',
                'required'=>true,
                'attr'=>[
                    'placeholder'=>'Ingresa tus nombres'
                ],
            ])
            ->add('apellidos', TextType::class,[
                'label'=>'Apellidos',
                'required'=>true,
                'attr'=>[
                    'placeholder'=>'Ingresa tus apellidos'
                ],
            ])
            ->add('edad', TextType::class,[
                'label'=>'Edad',
                'required'=>true,
                'attr'=>[
                    'placeholder'=>'Ingresa tu edad'
                ],
            ])
            ->add('sexo', ChoiceType::class,[
                'required'=>true,
                
                'choices'=>[
                    'Hombre'=>'Hombre',
                    'Mujer'=>'Mujer'
                ],
            ])
            ->add('email', EmailType::class,[
                'mapped'=> false
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Contraseña',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ingresa una contraseña',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Persona::class,
        ]);
    }
}
