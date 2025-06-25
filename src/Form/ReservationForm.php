<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\Room;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;


class ReservationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rentedRoom', EntityType::class, [
                'class' => Room::class,
                'choice_label' => 'title',
                'label' => 'Salle à réserver',
                'placeholder' => 'Choisissez une salle',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->where('r.is_available = true')
                        ->orderBy('r.title', 'ASC');
                },
                'required' => true,
            ])
            ->add('reservationStart', DateTimeType::class, [
                'label' => 'Début de la réservation',
                'widget' => 'single_text',
            ])
            ->add('reservationEnd', DateTimeType::class, [
                'label' => 'Fin de la réservation',
                'widget' => 'single_text',
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
