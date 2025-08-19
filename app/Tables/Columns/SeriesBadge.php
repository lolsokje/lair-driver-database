<?php

declare(strict_types=1);

namespace App\Tables\Columns;

use App\Models\Driver;
use Filament\Tables\Columns\Column;
use Illuminate\Database\Eloquent\Collection;

final class SeriesBadge extends Column
{
    protected string $view = 'filament.tables.columns.series-badge';

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

        if ($relatedRecord instanceof Collection) {
            /** @var Driver $driver */
            $driver = $this->getRecord();

            $relatedRecord = $relatedRecord->where('id', $driver->series_id)->first();
        }

        return [
            'record' => $relatedRecord,
            'value' => $relatedRecord->{$relationshipAttribute},
        ];
    }
}
