<?php

namespace Ensi\LaravelElasticQuery\Search\Highlight;

use Ensi\LaravelElasticQuery\Contracts\DSLAware;
use Ensi\LaravelElasticQuery\Filtering\BoolQueryBuilder;
use Ensi\LaravelElasticQuery\Search\Enums\BoundaryScanner;
use Ensi\LaravelElasticQuery\Search\Enums\Encoder;
use Ensi\LaravelElasticQuery\Search\Enums\Fragmenter;
use Ensi\LaravelElasticQuery\Search\Enums\HighlightOrder;
use Ensi\LaravelElasticQuery\Search\Enums\HighlightTagsSchema;
use Ensi\LaravelElasticQuery\Search\Enums\HighlightType;
use stdClass;
use Webmozart\Assert\Assert;

class Highlight implements DSLAware
{
    private array $fields = [];

    public function __construct(
        array $fields = [],
        private ?string $boundaryChars = null,
        private ?int $boundaryMaxScan = null,
        private ?string $boundaryScanner = null,
        private ?string $boundaryScannerLocale = null,
        private ?string $encoder = null,
        private ?bool $forceSource = null,
        private ?string $fragmenter = null,
        private ?int $fragmentOffset = null,
        private ?int $fragmentSize = null,
        private ?BoolQueryBuilder $highlightQuery = null,
        private ?array $matchedFields = null,
        private ?int $noMatchSize = null,
        private ?int $numberOfFragments = null,
        private ?string $order = null,
        private ?int $phraseLimit = null,
        private ?string $preTags = null,
        private ?string $postTags = null,
        private ?bool $requireFieldMatch = null,
        private ?int $maxAnalyzedOffset = null,
        private ?string $tagsSchema = null,
        private ?string $type = null,
    ) {
        Assert::nullOrInArray($this->boundaryScanner, BoundaryScanner::cases());
        Assert::nullOrInArray($this->encoder, Encoder::cases());
        Assert::nullOrInArray($this->fragmenter, Fragmenter::cases());
        Assert::nullOrInArray($this->order, HighlightOrder::cases());
        Assert::nullOrInArray($this->tagsSchema, HighlightTagsSchema::cases());
        Assert::nullOrInArray($this->type, HighlightType::cases());

        foreach ($fields as $fieldKey => $fieldValue) {
            if (is_string($fieldValue)) {
                $this->fields[$fieldValue] = new static();
            } else {
                $this->fields[$fieldKey] = $fieldValue;
            }
        }
        Assert::allIsInstanceOf($this->fields, static::class);
    }

    public function toDSL(): array|stdClass
    {
        $dsl = array_filter([
            'boundary_chars' => $this->boundaryChars,
            'boundary_max_scan' => $this->boundaryMaxScan,
            'boundary_scanner' => $this->boundaryScanner,
            'boundary_scanner_locale' => $this->boundaryScannerLocale,

            'encoder' => $this->encoder,

            'fields' => array_map(fn (Highlight $field) => $field->toDSL(), $this->fields) ?: null,

            'force_source' => $this->forceSource,

            'fragmenter' => $this->fragmenter,
            'fragment_offset' => $this->fragmentOffset,
            'fragment_size' => $this->fragmentSize,

            'highlight_query' => $this->highlightQuery?->toDSL(),
            'matched_fields' => $this->matchedFields,
            'no_match_size' => $this->noMatchSize,
            'number_of_fragments' => $this->numberOfFragments,
            'order' => $this->order,
            'phrase_limit' => $this->phraseLimit,

            'pre_tags' => $this->preTags,
            'post_tags' => $this->postTags,

            'require_field_match' => $this->requireFieldMatch,
            'max_analyzed_offset' => $this->maxAnalyzedOffset,
            'tags_schema' => $this->tagsSchema,
            'type' => $this->type,
        ], fn (mixed $item) => !is_null($item));

        if (!$dsl) {
            return new stdClass();
        }

        return $dsl;
    }
}
