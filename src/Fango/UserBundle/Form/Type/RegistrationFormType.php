<?php

namespace Fango\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class RegistrationFormType
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\UserBundle\Form\Type
 */
class RegistrationFormType extends AbstractType
{
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', ['label' => 'Email', 'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Email'
            ]])
            ->add('username', null, ['label' => 'Username', 'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Username'
            ]])
            ->add('fullname', null, ['label' => 'Fullname', 'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Full name'
            ]])
            ->add('plainPassword', 'password', ['label' => 'Password', 'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Password'
            ]])
//            ->add('plainPassword', 'repeated', [
//                'type' => 'password',
//                'first_options' => ['label' => 'Password'],
//                'second_options' => ['label' => 'Repeat password'],
//                'invalid_message' => 'The entered passwords don\'t match',
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'registration',
        ));
    }

    // BC for SF < 2.7
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    public function getName()
    {
        return 'fango_user_registration';
    }
}
