<?php
/**
 * Created by PhpStorm.
 * User: mimosa
 * Date: 13.04.18
 * Time: 11:49
 */

namespace App\Admin;


use App\Admin\AbstractAdmin as AbstractAdmin;

use App\Repository\CategoryRepository;
use Sonata\AdminBundle\Datagrid\{ListMapper, DatagridMapper};
use Sonata\AdminBundle\Form\FormMapper,
    Sonata\AdminBundle\Show\ShowMapper,
    Sonata\CoreBundle\Form\Type\DateRangeType,
    Sonata\DoctrineORMAdminBundle\Filter\DateRangeFilter,
    Sonata\DoctrineORMAdminBundle\Filter\DateFilter,
    Sonata\CoreBundle\Form\Type\DatePickerType,
    Sonata\CoreBundle\Validator\ErrorElement;

use Doctrine\ORM\EntityManager;

#use Sonata\CoreBundle\Form\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType,
    Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\{
    HiddenType, UrlType, FileType, ChoiceType, MoneyType, TextareaType, FormType, CollectionType
};

use Sonata\CoreBundle\Form\Type\CollectionType as SonataCollectionType;

use App\Entity\{
    Product, Category
};

use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class LeatherAdmin
 * @package App\Admin
 */
class ProductAdmin extends AbstractAdmin
{
    /**
     * Form configure
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var Product $product */
        $product = $this->getSubject();
        $colors = Product::getAvailableColors();
        $sizes = Product::getAvailableSizes();

        $formMapper
            ->with('Kategorie* (pflicht)', ['class' => 'col-md-6'])
            ->add('category', EntityType::class, [
                'placeholder'   => '...',
                'required'      => true,
                'label' => false,
                'class'         => Category::class,
                'choice_label'  => 'nameWithSubCat',
                'choice_translation_domain' => 'messages',
                'query_builder' => function(CategoryRepository $category) {
                    return $category->fetchChildren();
                },
                'attr'  => [
                   // 'data-sonata-select2-allow-clear' => 'true',
                   'data-sonata-select2' => 'false',
                ],
            ])
            ->end()
            ->with('Produkt Nummer (optionnal)', ['class' => 'col-md-6'])
            ->add('productNumber', null, [
                'required'  => false,
                'label' => false,
            ])
            ->end()
            /*
            ->with('Produkt Name (optionnal)', ['class' => 'col-md-3'])
            ->add('productName', null, [
                'required'  => false,
                'label' => false,
            ])
            ->end()
            */

            ->with('Produktmerkmale auf Deutsch', ['class' => 'col-md-6'])
            ->add('titleDe', null, [
                'label' => 'Titel'
            ])
            ->add('descriptionDe', null, [
                'label' => 'Beschreibung'
            ])
            ->end()

            ->with('Produktmerkmale auf Englisch', ['class' => 'col-md-6'])
            ->add('titleEn', null, [
                'label' => 'Titel'
            ])
            ->add('descriptionEn', null, [
                'label' => 'Beschreibung'
            ])
            ->end()

            ->add('colors', ChoiceType::class, [
                'choices' => array_combine($colors, $colors),
                'expanded'  => true,
                'multiple'  => true,
                'label' => 'Farben',
                'choice_translation_domain' => 'messages',
                'attr'  => [
                    'class' => 'list-inline',
                ],
                'choice_attr' => function($choiceValue, $key, $value) {
                    return [
                        'title' => strtoupper($value),
                        'data-color' => strtolower($key),
                        ];
                },
            ])

            ->add('sizes', ChoiceType::class, [
                'choices' => array_combine($sizes, $sizes),
                'required'  => false,
                'label' => 'Größen',
                'expanded'  => true,
                'multiple'  => true,
                'attr'  => [
                    'class' => 'list-inline',
                ],
            ])

            ->add('price', MoneyType::class, [
                'label' => 'Preis',
            ])
            ->add('topItem', null, [
                'label' => 'Top Produkt: Zeige dieses Produkt auf der Startseite',
            ])

            ->add('images', HiddenType::class, [
                'attr' => [
                    'class' => 'images-names-container',
                    'data-pk' => $product->getId() ?: 0,
                ]
            ])

            //Images uploaded but not persisted - delete on unload
            ->add('imagesTmp', HiddenType::class, [
                'mapped'        => false,
                'attr' => [
                    'class' => 'images-tmp-names-container',
                ]
            ])

            ->add('imagesList', FileType::class, [
                //'entry_type'    => ImagesType::class,
                //'allow_add'     => true,
                //'allow_delete'  => true,
                'label' => 'Bilder hinzufügen',
                'required'      => false,
                'mapped'        => false,
                'attr'  => [
                    'class' => 'js_upload-image',
                    'accept'    => 'image/*'
                    //'style' => 'display: none',
                ],
                'label_attr' => [
                    //'class' => 'btn btn-default'
                ],
                //'help'          => $product->getId() ? '<img src="/images/products/">' : '',
            ])
        ;

    }

    /**
     * Filters
     *
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('category', null, [], EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'getNameWithSubCat',
                'query_builder' => function(CategoryRepository $category) {
                    return $category->fetchChildren();
                },
                'attr'  => [
                    'data-sonata-select2' => 'false',
                ]
            ])
            ->add('titleDe')
            ->add('titleEn')
            ->add('productNumber', null, [
                'label' => 'Produkt Nummer'
            ])
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
                'label' => 'Erstellt am'
            ])
            ->add('category.nameWithSubCat', null, [
                'label' => 'Kategorie'
            ])
            ->add('titleDe', null, [
                'label' => 'Titel Deutsch'
            ])
            ->add('titleEn', null, [
                'label' => 'Titel Englisch'
            ])
            ->add('productNumber', null, [
                'label' => 'Produkt Nummer'
            ])
            ->add('price', null, [
                'label' => 'Preis'
            ])
            ->add('topItem', null, [
                'label' => 'Top Produkt'
            ])

            ->add('_action', null, [
                'actions' => [
                    //'show'      => [],
                    'edit'      => [],
                    'delete'    => [
                        'attr'  => [
                            'class' => 'btn-danger'
                        ]
                    ],
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

            ->add('titleDe')
            ->add('titleEn')
            ->add('descriptionDe', null, [
                'safe' => true //Allow html
            ])
            ->add('descriptionEn', null, [
                'safe' => true //Allow html
            ])
            ->add('price', MoneyType::class)
            //->add('colors')
            //->add('sizes')
            ->add('topItem')
        ;
    }


}