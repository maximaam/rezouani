<?php

namespace App\Utils;

use App\Entity\Product;

class ProductHelper
{
    const VAT = 19;

    public static function computeCard(array $cart)
    {
        $data = [];
        $total = 0;
        foreach ($cart  as $itemKey => $item) {
            $total +=  $item['full_price'];
            $data[$itemKey] = $item;
            $exclTax = round($total / (( self::VAT / 100) +1 ), 2);
            $result = [
                'products' => $data,
                'totals' => [
                    'excl_tax'  => $exclTax,
                    'vat'   => round($total - $exclTax, 2),
                    'total' => $total,
                ]
            ];

        }

        return $result;
    }

    /**
     * Calculate card data
     *
     * @param array $products
     * @param array $cart
     * @param $locale
     * @return array
     */
    public static function computeCard2(array $products, array $cart, $locale)
    {
        $data = [];
        $total = 0;

        /** @var Product $product */
        foreach ($products as $product) {
            // $fullPrice = $product->getPrice() * $cart[$product->getId()]['quantity'];
            
            
            foreach ($cart  as $itemKey => $item) {
                // dump($item);
                $itemCartKey = self::getItemCartKey($product->getId(), $item);
                $total +=  $item['full_price'];

                $raw = [
                    'id' => $product->getId(),
                    'title'     => $product->getTitle($locale),
                    'productNumber'     => $product->getProductNumber(),
                    //'slug'      => $product->getSlugFr(),
                    'quantity'  => $item['quantity'],
                    'size'      => $item['size'],
                    'color'      => $item['color'],
                    'price'     => $product->getPrice(),
                    'fullPrice' => $item['full_price']
                ];

                $data[$itemCartKey] = $raw;

                /*
                if (!isset($data[$itemCartKey])) {
                    $data[$itemCartKey] = $raw;
                } else {
                    $data[$itemCartKey][] = $raw;
                }
                    */
            }
        }

        $exclTax = round($total / (( self::VAT / 100) +1 ), 2);

        // dd($data);

        $result = [
            'products' => $data,
            'totals' => [
                'excl_tax'  => $exclTax,
                'vat'   => round($total - $exclTax, 2),
                'total' => $total,
            ]
        ];

        dd($result);


        return $result;
    }
    
}