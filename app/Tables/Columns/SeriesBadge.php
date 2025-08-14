<?php

declare(strict_types=1);

namespace App\Tables\Columns;

use Filament\Tables\Columns\Column;

final class SeriesBadge extends Column
{
    protected string $view = 'tables.columns.series-badge';

    public function getState(): ?array
    {
        $record = $this->getRecord();
        $attribute = $this->getName();

        if (! str_contains($attribute, '.')) {
            return [
                'record' => $record,
                'value' => $record->{$attribute},
            ];
        }

        $parts = explode('.', $attribute);
        $relationship = $parts[0];
        $relationshipAttribute = $parts[1];

        $relatedRecord = $record->{$relationship};

        if (! $relatedRecord) {
            return null;
        }

        return [
            'record' => $relatedRecord,
            'value' => $relatedRecord->{$relationshipAttribute},
        ];
    }
}
