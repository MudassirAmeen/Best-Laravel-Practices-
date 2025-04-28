<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

/**
 * Class BaseResource
 *
 * This abstract class provides a reusable way to structure JSON resources.
 * It maps model attributes and related resources using defined static arrays.
 */
abstract class BaseResource extends JsonResource
{
    /**
     * List of model properties to include in response.
     * Format:
     *  'modelProperty' => [
     *      'key'     => 'resource_key',
     *      'default' => null|'N/A'|… (Optional)
     *  ]
     */
    protected static array $fields = [];

    /**
     * List of relationships to include.
     * Format:
     *  'relationName' => [
     *      'resource' => \App\Http\Resources\OtherResource::class,
     *      'key'      => 'relation_key_name',
     *  ]
     */
    protected static array $relations = [];

    /**
     * Define any custom/computed fields to be appended.
     *
     * @return array
     */
    protected function customFields(): array
    {
        return [];
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        Log::warning("1");
        // Check if resource is null before proceeding
        if ($this->resource === null)
        {
            return [];
        }
        // Map attributes with fallback defaults
        $attrs = collect(static::$fields)
            ->mapWithKeys(function (array $cfg, string $prop)
            {
                if (!array_key_exists($prop, $this->resource->getAttributes()))
                {
                    return [];
                }

                $val = $this->{$prop} ?? $cfg['default'] ?? null;
                return [$cfg['key'] => $val];
            })
            ->toArray();

        // Map relations only if loaded
        $rels = collect(static::$relations)
            ->filter(fn(array $cfg, string $rel) => $this->resource->relationLoaded($rel))
            ->mapWithKeys(fn(array $cfg, string $rel) => [
                $cfg['key'] => new $cfg['resource']($this->{$rel})
            ])
            ->toArray();

        // Merge everything into final resource array
        return array_merge($attrs, $rels, $this->customFields());
    }
}
