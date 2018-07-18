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

/**
 * Class PageAdmin
 * @package App\Admin
 */
class PageAdmin extends AbstractAdmin
{
    /**
     * Form configure
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('titleDe')
            ->add('titleEn')
            ->add('descriptionDe')
            ->add('descriptionEn');
    }

    /**
     * Filters
     *
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('titleDe')
            ->add('titleEn');
    }

    /**
     * View list
     *
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            //->addIdentifier('id')

            ->add('createdAt', null, [
                'format' => parent::GLOBAL_DATE_FORMAT,
                'label' => 'Created'
            ])
            ->add('titleDe')
            ->add('titleEn')
            ->add('descriptionDe')
            ->add('descriptionEn')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ]
            ]);
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('createdAt', null, [
                'format' => parent::GLOBAL_DATETIME_FORMAT,
            ])
            ->add('titleDe')
            ->add('titleEn')
            ->add('descriptionDe', null, [
                'safe' => true
            ])
            ->add('descriptionEn', null, [
                'safe' => true
            ]);
    }
}