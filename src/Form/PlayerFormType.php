<?php


namespace App\Form;

use App\Entity\Player;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PlayerFormType
 * @package App\Form
 */
class PlayerFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $now = new DateTime();
        $builder
            ->add('firstname', TextType::class, [
                'label' => "Firstname",
            ])
            ->add('lastname', TextType::class, [
                'label' => "Lastname",
                'attr' => ['class' => 'text-uppercase'],
            ])
            ->add('pseudo', TextType::class, [
                'label' => "Pseudo",
                'required' => false,
            ])
            ->add('identifiantCompte', TextType::class, [
                'label' => "Identifiant Compte",
                'required' => false,
            ])
            ->add('email', TextType::class, [
                'label' => "Email",
            ])
            ->add('birthdate', DateType::class, [
                'widget' => 'choice',
                'years' => range(1900, $now->format('Y')),
                'label' => "Birth date",
            ]);
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
        ]);
    }
}