<?php 
namespace App\Form;

use App\Entity\Persona;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombres', TextType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('apellidos', TextType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('edad', IntegerType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('sexo', ChoiceType::class, [
                'choices' => [
                    'Masculino' => 'Masculino',
                    'Femenino' => 'Femenino',
                ],
                'constraints' => [new NotBlank()],
            ])
            ->add('email', EmailType::class, [
                'mapped' => false, // No está directamente mapeado con la entidad Persona
                'constraints' => [new NotBlank()],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Persona::class,
        ]);
    }
}
