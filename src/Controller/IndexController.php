<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Page;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class IndexController
 * @package App\Controller
 */
class IndexController extends Controller
{
    /**
     * @Route("/", name="app_index_index")
     *
     * @return Response
     */
    public function index()
    {
        $leather = $this->getDoctrine()->getRepository(Product::class)->findBy(['topItem' => true]);

        return $this->render('app/index.html.twig', [
            'leather' => $leather
        ]);
    }

    /**
     * @Route("/{_locale}/page/{slug}", name="app_index_page")
     * @Method({"GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function page(Request $request)
    {
        $slugKey = 'slug' . ucfirst($request->getLocale());
        $slug = $request->get('slug');

        $page = $this->getDoctrine()
            ->getRepository(Page::class)
            ->findOneBy([$slugKey => $slug]);

        if (null === $page) {
            throw $this->createNotFoundException(
                'Page ' . $slug . ' does not exist'
            );
        }

        return $this->render('app/page.html.twig', [
            'page'  => $page
        ]);
    }

    /**
     * @Route("/{_locale}/search", name="app_index_search")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function search(Request $request)
    {

        die('ok');

        $slugKey = 'slug' . ucfirst($request->getLocale());
        $slug = $request->get('slug');

        $page = $this->getDoctrine()
            ->getRepository(Page::class)
            ->findOneBy([$slugKey => $slug]);

        if (null === $page) {
            throw $this->createNotFoundException(
                'Page ' . $slug . ' does not exist'
            );
        }

        return $this->render('app/page.html.twig', [
            'page'  => $page
        ]);
    }

    /**
     * Get products for categories, sub-categories and items
     *
     * @Route("/{_locale}/catalogue/{catAlias}/{subCatAlias}/{itemId}",
     *     name="app_index_catalogue",
     *     defaults={"subCatAlias" = null, "itemId" = null}
     *     )
     *
     * @param Request $request
     * @return Response
     */
    public function catalogue(Request $request)
    {
        $catRepo = $this->getDoctrine()->getRepository(Category::class);
        $productRepo = $this->getDoctrine()->getRepository(Product::class);

        if (null === $itemId = $request->get('itemId')) {

            $alias = 'alias' . ucfirst($request->getLocale());

            if (null === $subCatAlias = $request->get('subCatAlias')) { //Main categories

                /** @var Category $category */
                $category = $catRepo->findOneBy([$alias => $request->get('catAlias')]);
                $catIds = CategoryRepository::getChildrenIds($category);
                $products = $productRepo->fetchByCategories($catIds);
            } else {

                $category = $catRepo->findOneBy([$alias => $subCatAlias]);
                $products = $productRepo->findBy(['category' => $category]);
            }

            return $this->render('app/products.html.twig', [
                'category'  => $category,
                'products'  => $products
            ]);
        } else {

            return $this->render('app/product.html.twig', [
                'product'  => $productRepo->find($itemId)
            ]);
        }


    }

}
