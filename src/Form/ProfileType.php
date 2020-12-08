<?php
/**
 * Created by PhpStorm.
 * User: mimosa
 * Date: 05.05.17
 * Time: 17:02
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use FOS\UserBundle\Form\Type\ProfileFormType as ProfileFormType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;


class ProfileType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $preferred = ['CH', 'FR', 'BE', 'DE', 'GB', 'IT', 'NL', 'ES', 'CA'];

        $builder
            ->remove('username')
            ->remove('current_password')

            ->add('email', EmailType::class, [
                'label' => 'form.email',
                'translation_domain' => 'FOSUserBundle']
            )
            ->add('name')
            ->add('address')
            ->add('zip')
            ->add('city')
            ->add('country', CountryType::class, [
                'preferred_choices' => $preferred
            ])
            ->add('addressShipping')
            ->add('zipShipping')
            ->add('cityShipping')
            ->add('countryShipping', CountryType::class, [
                'preferred_choices' => $preferred
            ]);
        ;

    }





    /**
     * @return mixed
     */
    public function getParent()
    {
        return ProfileFormType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            //'data_class' => Mimo::class,
        ]);
    }

}