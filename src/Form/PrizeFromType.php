<?php

namespace App\Form;

use App\Entity\Prize;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrizeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Prize Name',
                'attr' => [
                    'class' => 'form-input',
                ],
            ])
            ->add('items', TextType::class, [
                'label' => 'Items',
                'attr' => [
                    'class' => 'form-input',
                ],
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prize::class,
        ]);
    }
}
