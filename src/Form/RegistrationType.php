<?php
/**
 * Created by PhpStorm.
 * User: mimosa
 * Date: 05.05.17
 * Time: 11:00
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use FOS\UserBundle\Form\Type\RegistrationFormType as RegistrationFormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RegistrationType
 * @package App\Form
 */
class RegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $preferred = ['CH', 'FR', 'BE', 'DE', 'GB', 'IT', 'NL', 'ES', 'CA'];

        $builder
            ->remove('username')

            ->add('ip', HiddenType::class)
            ->add('name', null, [
                'label' => 'user.name'
            ])
            ->add('address', null, [
                'label' => 'user.address_register'
            ])
            ->add('zip', null, [
                'label' => 'user.zip'
            ])
            ->add('city', null, [
                'label' => 'user.city'
            ])
            ->add('country', CountryType::class, [
                'preferred_choices' => $preferred,
                'label' => 'user.country'
            ])
            ->add('addressShipping', null, [
                'label' => 'user.address_register'
            ])
            ->add('zipShipping', null, [
                'label' => 'user.zip'
            ])
            ->add('cityShipping', null, [
                'label' => 'user.city'
            ])
            ->add('countryShipping', CountryType::class, [
                'preferred_choices' => $preferred,
                'label' => 'user.country'
            ]);
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return RegistrationFormType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            //'data_class' => Mimo::class,
        ]);
    }

}