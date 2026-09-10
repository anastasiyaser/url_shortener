<?php

/**
 * Admin User Type.
 */

namespace App\Form\Type;

use App\Entity\Enum\UserRole;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AdminUserType.
 */
class AdminUserType extends AbstractType
{
    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder Form builder
     * @param array                $options Options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'label.email',
                'required' => true,
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'label.roles',
                'choices' => [
                    'Administrator' => UserRole::ROLE_ADMIN->value,
                    'User' => UserRole::ROLE_USER->value,
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => true,
            ]);
    }

    /**
     * Configures options.
     *
     * @param OptionsResolver $resolver Resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
