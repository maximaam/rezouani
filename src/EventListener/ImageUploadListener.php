<?php
/**
 * Created by PhpStorm.
 * User: mimosa
 * Date: 16.04.18
 * Time: 13:19
 */

namespace App\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\Product;
use App\Service\FileUploader;


class ImageUploadListener
{
    private $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {

        //dump($args->getEntity()); die;

        //$this->upload($args->getEntity());
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {


        //dump($args->getEntityChangeSet());


        //$entity = $args->getEntity();

        //dump($entity);
        //die;



        //$this->upload($entity);
    }

    /**
     * @param LifecycleEventArgs $args
     */

    /*
    public function postLoad(LifecycleEventArgs $args)
    {
        //Only admin
        if (false === strpos($_SERVER['REQUEST_URI'], '/admin/')) {
            return;
        }

        $entity = $args->getEntity();

        if (!$entity instanceof Product) {
            return;
        }


        $attachments = $entity->getImages();
        if (!empty($attachments)) {
            foreach ($attachments as $attachment) {
                if ($filename = $attachment->getImage()) {
                    $attachment->setImage(new File($this->uploader->getTargetDirectory() . DIRECTORY_SEPARATOR . $filename));
                }
            }
        }

    }
    */


    /**
     * @param $entity
     */
    private function upload($entity)
    {

        //dump(get_class($entity));
        //dump($entity); die;

        if (!$entity instanceof Product) {
            return;
        }

        //die('ok');

        $attachments = $entity->getImages();

        dump($attachments); die;



        if (!empty($attachments)) {
            foreach ($attachments as $attachment) {

                /** @var UploadedFile $uploadedImage */
                if (null !== $uploadedImage = $attachment->getImage()) {
                    $filename = $this->uploader->upload($uploadedImage);


                    $image = new Image();
                    $image->setPath($filename);




                    $image->setSize($uploadedImage->getClientSize());
                    $image->setName($uploadedImage->getClientOriginalName());


                    $entity->addImage($image);


                    //dump($image); die;

                    unset($attachment);


                    //$attachment->setImage($image);
                }
            }
        }
    }
}