<?php
/**
 * Created by PhpStorm.
 * User: mimosa
 * Date: 09.04.18
 * Time: 11:17
 */

namespace App\Controller;

use Sonata\AdminBundle\Controller\CRUDController as SonataCRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Entity\Product;


/**
 * Class AdminController
 * Extending the Sonata CRUD controller
 *
 * @package App\Controller\Admin
 */
class AdminController extends SonataCRUDController
{

    /**
     * @param int|null|string $id
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($id)
    {
        /** @var Product $product */
        if (false === $product = $this->admin->getSubject()) {
            throw new NotFoundHttpException(sprintf('Unable to find coupon with id: %s', $id));
        }

        //Delete images when product is deleted
        if ('DELETE' == $this->getRestMethod()) {
            $images = explode(Product::IMG_SEPARATOR, trim($product->getImages(), Product::IMG_SEPARATOR));

            if (!empty($images)) {
                foreach ($images as $image) {
                    $file = $this->getParameter('product_images_dir') . DIRECTORY_SEPARATOR . $image;
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
            }
        }

        return parent::deleteAction($id);
    }

}