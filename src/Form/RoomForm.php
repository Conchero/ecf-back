<?php
namespace App\Form;

use App\Entity\Room;
use App\Entity\Equipment;
use App\Entity\Advantage;
use App\Entity\Software;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\UX\Dropzone\Form\DropzoneType;

class RoomForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre de la salle",
                'attr' => ['placeholder' => "Titre de la salle"],
            ])
            ->add('image', DropzoneType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Sélectionnez une image'],
            ])
            ->add('localisation', TextType::class, [
                'label' => 'Ville',
                'attr' => ['placeholder' => 'Ex. : Paris, Lyon...'],
            ])
            ->add('capacity', TextType::class, [
                'label' => "Capacité",
                'attr' => ['placeholder' => "Capacité d'accueil"],
            ])
            ->add('keywords', TextType::class, [
                'label' => "Mots-clés",
                'required' => false,
                'attr' => ['placeholder' => "Mots-clés (optionnel)"],
            ])
            ->add('description', TextareaType::class, [
                'label' => "Description",
                'attr' => ['placeholder' => "Description de la salle"],
            ])
            ->add('equipments', EntityType::class, [
                'class' => Equipment::class,
                'choice_label' => 'title',
                'label' => "Équipements disponibles",
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ])
            ->add('advantages', EntityType::class, [
                'class' => Advantage::class,
                'choice_label' => 'title',
                'label' => "Avantages de la salle",
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ])
            ->add('softwares', EntityType::class, [
                'class' => Software::class,
                'choice_label' => 'title',
                'label' => "Logiciels disponibles",
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
