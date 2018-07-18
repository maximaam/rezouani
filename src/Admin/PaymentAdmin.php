<?php
/**
 * Created by PhpStorm.
 * User: mimosa
 * Date: 01.06.18
 * Time: 11:26
 */

namespace App\Admin;

use App\Admin\AbstractAdmin as AbstractAdmin;

use App\Entity\Payment;
use Sonata\AdminBundle\Datagrid\{ListMapper, DatagridMapper};
use Sonata\AdminBundle\Form\FormMapper,
    Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

/**
 * Class PaymentAdmin
 * @package App\Admin
 */
class PaymentAdmin extends AbstractAdmin
{
    /**
     * Form configure
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper
            ->add('delivered')
        ;

    }

    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
            ->remove('edit');
    }

    /**
     * Filters
     *
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('status')
        ;
    }

    /**
     * View list
     *
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('createdAt', null, [
                'format' => parent::GLOBAL_DATETIME_FORMAT,
                'label' => 'Created'
            ])
            //->add('paymentId')
            ->add('status', 'choice', [
                'editable'  => false,
                'choices' => [
                    1 => 'Fehler',
                    2 => 'Bezahlt',
                ],
            ])
            ->add('buyerName')
            ->add('buyerEmail')
            ->add('amount', null, [
                'label' => 'Summe (EUR)'
            ])
            ->add('delivered', null, [
                'editable'  => true
            ])

            ->add('_action', null, [
                'actions' => [
                    'show'      => [],
                    //'edit'      => [],
                    'delete'    => [],
                ]
            ])
        ;

    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('createdAt', null, [
                'format' => parent::GLOBAL_DATETIME_FORMAT,
                'label' => 'Created'
            ])
            ->add('paymentId')
            //->add('status')
            //->add('productsIds')
            ->add('productsContent', null, [
                'safe'  => true
            ])
            ->add('buyerName')
            ->add('buyerEmail')
            ->add('buyerAddress', null, [
                'safe'  => true
            ])
            ->add('amount', null, [
                'label' => 'Summe (EUR)'
            ])
            ->add('delivered', null, [
                'editable'  => true
            ])
        ;

    }
}