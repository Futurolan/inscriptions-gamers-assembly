<?php


namespace App\Form;

use App\Entity\Player;
use Symfony\Component\Form\AbstractType;
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
        $builder
            ->add('firstname', TextType::class, [
                'label' => "Firstname",
            ])
            ->add('lastname', TextType::class, [
                'label' => "Lastname",
            ])
            ->add('pseudo', TextType::class, [
                'label' => "Pseudo",
                'required' => false,
            ])
            ->add('email', TextType::class, [
                'label' => "Email",
            ])
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