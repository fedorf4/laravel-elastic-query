<?php

namespace Ensi\LaravelElasticQuery\Tests\Data\Seeds;

use Ensi\LaravelElasticQuery\Tests\Data\Models\ProductsIndex;

class ProductIndexSeeder extends IndexSeeder
{
    protected array $fixtures = ['products_default.json'];

    protected array $settings = [
        'analysis' => [
            'filter' => [
                "shingle" => [
                    "type" => "shingle",
                    "min_shingle_size" => 2,
                    "max_shingle_size" => 3,
                ],
            ],
            'analyzer' => [
                "trigram" => [
                    "type" => "custom",
                    "tokenizer" => "standard",
                    "filter" => ["lowercase", "shingle"],
                ],
            ],
        ],
    ];
    protected array $mappings = [
        'properties' => [
            'product_id' => ['type' => 'keyword'],
            'active' => ['type' => 'boolean'],

            'name' => [
                'type' => 'keyword',
                'copy_to' => 'search_name',
                'fields' => [
                    "trigram" => [
                        "type" => "text",
                        "analyzer" => "trigram",
                    ],
                ],
            ],
            'search_name' => ['type' => 'text'],
            'description' => ['type' => 'text'],

            'code' => ['type' => 'keyword'],
            'tags' => ['type' => 'keyword'],
            'rating' => ['type' => 'integer'],
            'package' => ['type' => 'keyword'],
            'vat' => ['type' => 'integer'],

            'offers' => [
                'type' => 'nested',
                'properties' => [
                    'seller_id' => ['type' => 'keyword'],
                    'active' => ['type' => 'boolean'],
                    'price' => ['type' => 'double'],
                ],
            ],
        ],
    ];

    protected function getIndexName(): string
    {
        return ProductsIndex::fullName();
    }
}
