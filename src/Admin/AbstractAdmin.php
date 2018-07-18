<?php
/**
 * Created by PhpStorm.
 * User: mimosa
 * Date: 29.03.18
 * Time: 18:03
 */

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin as SonataAbstractAdmin;

/**
 * Class AbstractAdmin
 * Alpaka Abstract admin referencing Sonata
 *
 * @package App\Admin
 */
abstract class AbstractAdmin extends SonataAbstractAdmin
{

    const GLOBAL_DATETIME_FORMAT    = 'Y-m-d H:i';
    const GLOBAL_DATE_FORMAT        = 'd M, Y';
    const GLOBAL_TIME_FORMAT        = 'H:i';

    /**
     * Data grid configurator for all
     * @var array
     */
    protected $datagridValues = [
        '_page'         => 1, // display the first page (default = 1)
        '_sort_order'   => 'DESC', // reverse order (default = 'ASC')
        '_sort_by'      => 'createdAt', // name of the ordered field (default = the model's id field, if any)
    ];

    /**
     * Flash message helper
     *
     * @param mixed $object
     * @return string
     */
    public function toString($object)
    {
        return method_exists($object, 'getTitle') ? $object->getTitle($this->request->getLocale()) : get_class($object);
    }

    /**
     * @param string $parameter
     * @return mixed
     */
    protected function getServiceParameter(string $parameter): string
    {
        return $this->getConfigurationPool()->getContainer()->getParameter($parameter);
    }

    /**
     * Global deactivation of batch deletion
     *
     * {@inheritdoc}
     */
    protected function configureBatchActions($actions): array
    {
        if (isset($actions['delete'])) {
            unset($actions['delete']);
        }

        return $actions;

    }

    /**
     * @return mixed
     */
    protected function getEntityManager()
    {
        return $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
    }

}