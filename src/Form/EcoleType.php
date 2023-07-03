<?php

namespace App\Form;

use App\Entity\Ecole;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\ORM\EntityRepository;


class EcoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomEcole', TextType::class, [
                'label' => 'Nom de l\'école',
                'required' => true
            ])
            ->add('telephoneEcole', TextType::class, [
                'label' => 'Téléphone',
                'required' => true
            ])
            ->add('mailEcole', EmailType::class, [
                'label' => 'Adresse email',
                'required' => true
            ])
            ->add('adresseEcole', TextareaType::class, [
                'label' => 'Adresse',
                'required' => true
            ])
            ->add('villeEcole', TextType::class, [
                'label' => 'Ville',
                'required' => true
            ])
            ->add('users', EntityType::class, [
                'label' => 'Utilisateurs',
                'class' => User::class,
                'choice_label' => 'email',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
                'required' => false
            ]);


            //->add('dateCreation', DateTimeType::class, [
            //    'label' => 'Date de création',
            //    'disabled' => true,
            //    'data' => new \DateTimeImmutable()
            //]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ecole::class,
        ]);
    }
}

