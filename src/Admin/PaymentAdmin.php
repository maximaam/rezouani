<?php
/**
 * Created by PhpStorm.
 * User: mimosa
 * Date: 01.06.18
 * Time: 11:26
 */

namespace App\Admin;

use App\Admin\AbstractAdmin as AbstractAdmin;

use Sonata\AdminBundle\Datagrid\{ListMapper, DatagridMapper};
use Sonata\AdminBundle\Form\FormMapper,
    Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

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
        $formMapper->add('delivered');
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
            ->add('buyerName', null, [
                'label' => 'Käufer Name'
            ])
            ->add('buyerEmail', null, [
                'label' => 'Käufer Email'
            ]);
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
                'label' => 'Erstellt am',
            ])
            //->add('paymentId')
            ->add('status', 'choice', [
                'editable'  => false,
                'choices' => [
                    1 => 'Fehler',
                    2 => 'Bezahlt',
                ],
            ])
            ->add('buyerName', null, [
                'label' => 'Käufer Name'
            ])
            ->add('buyerEmail', null, [
                'label' => 'Käufer Email'
            ])
            ->add('amount', null, [
                'label' => 'Summe (EUR)'
            ])
            ->add('delivered', null, [
                'editable'  => true,
                'label' => 'Verschickt',
            ])
            ->add('_action', null, [
                'label' => false,
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
                'label' => 'Erstellt am',
            ])
            ->add('paymentId', null, [
                'label' => 'PayPal Zahlungs-ID',
            ])
            //->add('status')
            //->add('productsIds')
            ->add('productsContent', null, [
                'safe'  => true,
                'label' => 'Gekaufte Artikel',
            ])
            ->add('buyerName', null, [
                'label' => 'Käufer Name',
            ])
            ->add('buyerEmail', null, [
                'label' => 'Käufer Email',
            ])
            ->add('buyerAddress', null, [
                'safe'  => true,
                'label' => 'Käufer Adresse',
            ])
            ->add('amount', null, [
                'label' => 'Summe (EUR)'
            ])
            ->add('delivered', null, [
                'editable'  => true,
                'label' => 'Verschickt',
            ])
        ;
    }

    /**
     * @return string[]
     */
    public function getExportFormats(): array
    {
        return ['xls'];
    }
}