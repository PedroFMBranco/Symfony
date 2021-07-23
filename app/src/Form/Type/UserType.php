<?php

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'First name can not be blank',
                    ]),new NotBlank([
                        'message' => 'First name can not be blank',
                    ])
                ]
            ])->add('lastName', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'Last name can not be blank',
                    ]),new NotBlank([
                        'message' => 'Last name can not be blank',
                    ]), new NotBlank()
                ]
            ])->add('email', EmailType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'First name can not be blank',
                    ]),new NotBlank([
                        'message' => 'First name can not be blank',
                    ]), new Email()
                ]
            ])->add('phoneNumber', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'Phone number can not be blank',
                    ]),new NotBlank([
                        'message' => 'Phone number can not be blank',
                    ])
                ]
            ])->add('address', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'Address can not be blank',
                    ]),new NotBlank([
                        'message' => 'Address can not be blank',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}