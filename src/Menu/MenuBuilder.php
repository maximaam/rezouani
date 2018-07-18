<?php
/**
 * Created by PhpStorm.
 * User: mimosa
 * Date: 15.02.18
 * Time: 16:13
 */

namespace App\Menu;

use App\Entity\Category;
use App\Entity\Page;
use App\Repository\CategoryRepository;
use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\TranslatorInterface;

class MenuBuilder
{

    private $factory;

    /**
     * @var RequestStack
     */
    private $request;

    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * MenuBuilder constructor.
     *
     * @param FactoryInterface $factory
     * @param RequestStack $request
     * @param TranslatorInterface $translator
     * @param EntityManager $em
     */
    public function __construct(
        FactoryInterface $factory,
        RequestStack $request,
        TranslatorInterface $translator,
        EntityManager $em)
    {
        $this->factory = $factory;
        $this->request = $request;
        $this->translator = $translator;
        $this->em = $em;
    }

    /**
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'navbar-nav mr-auto');

        $categories = $this->em
            ->getRepository(Category::class)
            ->fetchParents()
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

        $locale = $this->request->getCurrentRequest()->getLocale();

        /** @var Category $category */
        foreach ($categories as $category) {
            $menu->addChild($category->getName($locale), [
                'route' => 'app_index_catalogue',
                'attributes' => ['class' => 'nav-item'],
                'linkAttributes' => ['class' => 'nav-link'],
                'routeParameters' => [
                    'catAlias' => $category->getAlias($locale)
                ]
            ]);
        }

        return $menu;
    }

    /**
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function createSubCategoryMenu(array $options)
    {
        $locale = $this->request->getCurrentRequest()->getLocale();

        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'navbar-subcategory');

        /** @var Category $currentCategory */
        $currentCategory = $this->em
            ->getRepository(Category::class)
            ->findOneBy(['alias'.ucfirst($locale) => $this->request->getCurrentRequest()->get('catAlias')]);

        $subCategories = $this->em
            ->getRepository(Category::class)
            ->fetchChildren($currentCategory)
            ->getQuery()
            ->getResult();

        /** @var Category $category */
        foreach ($subCategories as $category) {
            $menu->addChild($category->getName($locale), [
                'route' => 'app_index_catalogue',
                'attributes' => ['class' => ''],
                'linkAttributes' => ['class' => ''],
                'routeParameters' => [
                    'catAlias' => $currentCategory->getAlias($locale),
                    'subCatAlias' => $category->getAlias($locale),
                ]
            ]);
        }

        return $menu;
    }

    /**
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function createFooterMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'footer-nav');

        $locale = $this->request->getCurrentRequest()->getLocale();

        $pages = $this->em->getRepository(Page::class)->findAll();

        /** @var Page $page */
        foreach ($pages as $page) {
            $menu->addChild($page->getTitle($locale), [
                'route' => 'app_index_page',
                'attributes' => ['class' => ''],
                'linkAttributes' => ['class' => ''],
                'routeParameters' => [
                    'slug' => $page->getSlug($locale),
                ]
            ]);
        }

        return $menu;
    }
}