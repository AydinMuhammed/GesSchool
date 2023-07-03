<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\Ecole;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ClasseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_classe')
            ->add('section_classe')
            ->add('ecole', EntityType::class, [
                'class' => Ecole::class,
                'choice_label' => 'nomEcole', // Le champ de l'entité Ecole utilisé comme libellé dans le champ de formulaire
                'placeholder' => 'Sélectionner une école', // Optionnel : un libellé par défaut pour le champ de formulaire
                'attr' => [
                    'hidden' => true,
                ],
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Classe::class,
        ]);
    }
}
