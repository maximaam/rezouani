<?php
/**
 * Created by PhpStorm.
 * User: mimosa
 * Date: 21.04.17
 * Time: 12:06
 */

namespace App\Utils;

use App\Entity\Product;


/**
 * Class ProductHelper
 * @package App\Utils
 */
class ProductHelper
{

    /**
     * Tex
     */
    const VAT = 19;


    /**
     * Calculate card data
     *
     * @param array $products
     * @param array $cart
     * @param $locale
     * @return array
     */
    public static function computeCard(array $products, array $cart, $locale)
    {
        $data = [];
        $total = 0;

        /** @var Product $product */
        foreach ($products as $product) {
            $fullPrice = $product->getPrice() * $cart[$product->getId()]['quantity'];
            $total += $fullPrice;

            $raw = [
                'title'     => $product->getTitle($locale),
                'productNumber'     => $product->getProductNumber(),
                //'slug'      => $product->getSlugFr(),
                'quantity'  => $cart[$product->getId()]['quantity'],
                'size'      => $cart[$product->getId()]['size'],
                'color'      => $cart[$product->getId()]['color'],
                'price'     => $product->getPrice(),
                'fullPrice' => $fullPrice
            ];

            if (!isset($data[$product->getId()])) {
                $data[$product->getId()] = $raw;
            } else {
                $data[$product->getId()][] = $raw;
            }
        }

        $exclTax = round($total / (( self::VAT / 100) +1 ), 2);

        return [
            'products' => $data,
            'totals' => [
                'excl_tax'  => $exclTax,
                'vat'   => round($total - $exclTax, 2),
                'total' => $total,
            ]
        ];
    }
}