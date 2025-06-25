<?php
namespace App\Form;

use App\Entity\Equipment;
use App\Entity\Software;
use App\Entity\Advantage;
use App\Model\SearchData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('equipments', EntityType::class, [
                'class' => Equipment::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            ->add('capacity', IntegerType::class, [
                'label' => 'Capacité minimale',
                'required' => false,
            ])
            ->add('softwares', EntityType::class, [
                'class' => Software::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            ->add('advantages', EntityType::class, [
                'class' => Advantage::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}
