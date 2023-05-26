<?php

namespace App\Form;

use App\Entity\PomodoroSession;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\NotBlank;

class PomodoroSessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
    ->add('sessionLength', IntegerType::class, [
        'label' => 'Session Length',
        'constraints' => [
            new Range([
                 'min' => 1,
                 'max' => 90,
                 'notInRangeMessage' => 'Session Length should be between 1 and 90.'
            ]),
            new NotBlank()
        ]       
    ])
    ->add('breakLength', IntegerType::class, [
        'label' => 'Break Length',
        'constraints' => [
            new Range([
                 'min' => 1,
                 'max' => 90,
                 'notInRangeMessage' => 'Break Length should be between 1 and 90.'
            ]),
            new NotBlank()
        ]       
    ])
    ->add('sessionCount', IntegerType::class, [
        'label' => 'Session Count',
        'constraints' => [
            new Range([
                 'min' => 1,
                 'max' => 10,
                 'notInRangeMessage' => 'Session Count should be between 1 and 10.'
            ]),
            new NotBlank()
        ]       
    ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PomodoroSession::class,
        ]);
    }
}