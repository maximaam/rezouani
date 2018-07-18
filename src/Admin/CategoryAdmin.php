<?php
/**
 * Created by PhpStorm.
 * User: mimosa
 * Date: 13.04.18
 * Time: 11:49
 */

namespace App\Admin;

use App\Admin\AbstractAdmin as AbstractAdmin;

use Sonata\AdminBundle\Datagrid\{ListMapper, DatagridMapper};
use Sonata\AdminBundle\Form\FormMapper,
    Sonata\AdminBundle\Show\ShowMapper,
    Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\CategoryRepository;
use App\Entity\Category;

/**
 * Class CategoryAdmin
 * @package App\Admin
 */
class CategoryAdmin extends AbstractAdmin
{
    /**
     * Form configure
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('parent', EntityType::class, [
                'placeholder'   => 'Select parent category...',
                'required'      => false,
                'class'         => Category::class,
                'choice_label'  => 'NameWithSubCat',
                'label'         => 'Category and/or Sub-category',
                'query_builder' => function (CategoryRepository $category) {
                    return $category->fetchParents();
                },
            ])
            ->add('nameDe', null, [
                'label' => 'Category name german'
            ])
            ->add('nameEn', null, [
                'label' => 'Category name english'
            ])
            ->add('descriptionDe', null, [
                'label' => 'Category description german'
            ])
            ->add('descriptionEn', null, [
                'label' => 'Category description english'
            ])
        ;
    }

    /**
     * @param ErrorElement $errorElement
     * @param $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
    }

    /**
     * Filters
     *
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('nameDe')
            ->add('nameEn')
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
            //->addIdentifier('id')

            ->add('createdAt', null, [
                'format' => parent::GLOBAL_DATE_FORMAT,
                'label' => 'Created'
            ])
            ->add('nameWithSubCat')

            ->add('_action', null, [
                'actions' => [
                    //'show'      => [],
                    'edit'      => [],
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
            ])

            ->add('nameDe')
            ->add('nameEn')
        ;
    }


}