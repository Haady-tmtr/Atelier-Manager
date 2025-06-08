<?php

namespace App\Form;

use App\Entity\Atelier;
use App\Entity\Satisfaction;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SatisfactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note', IntegerType::class, [
                'label' => 'Note (0-5)',
                'attr' => ['min' => 0, 'max' => 5]
            ])

            /*->add('apprenti', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('atelier', EntityType::class, [
                'class' => Atelier::class,
                'choice_label' => 'id',
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Satisfaction::class,
        ]);
    }
}
