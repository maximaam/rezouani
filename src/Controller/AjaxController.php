<?php
/**
 * Created by PhpStorm.
 * User: mimosa
 * Date: 23.05.18
 * Time: 13:47
 */

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FileUploader;
use App\Entity\Product;


/**
 * Class AjaxController
 * @package App\Controller
 */
class AjaxController extends AbstractController
{
    /**
     * @Route("/ajx/upload-image", name="ajx_upload_image")
     *
     * @param Request $request
     * @param FileUploader $uploader
     * @return Response
     */
    public function uploadImage(Request $request, FileUploader $uploader): Response
    {
        if (count($request->files)) {
            foreach ($request->files as $file) {
                $filename = $uploader->upload($file);

                return new Response($filename);
            }
        }
    }

    /**
     * @Route("/ajx/delete-image", name="ajx_delete_image")
     *
     * @param Request $request
     * @param FileUploader $uploader
     * @return Response
     */
    public function deleteImage(Request $request, FileUploader $uploader): Response
    {
        $filename = $request->query->get('filename');
        $productId = (int)$request->query->get('pk');
        $response = 'error';

        if ($filename && $productId > 0) { //Update form - product exists

            $image = $uploader->getTargetDirectory() . DIRECTORY_SEPARATOR . $filename;

            if (\file_exists($image)) {

                $em = $this->getDoctrine()->getManager();

                /** @var Product $product */
                $product = $em->getRepository(Product::class)->find($productId);
                $images = \str_replace($filename . Product::IMG_SEPARATOR, '', $product->getImages());
                $product->setImages($images);

                $em->flush();

                \unlink($uploader->getTargetDirectory() . DIRECTORY_SEPARATOR . $filename);

                $response = 'success';
            }
        }

        return new Response($response);
    }

}