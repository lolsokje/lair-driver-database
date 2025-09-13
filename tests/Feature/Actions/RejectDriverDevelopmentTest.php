<?php

declare(strict_types=1);

use App\Actions\RejectDriverDevelopment;
use App\Enums\DevelopmentRoundStatus;
use App\Exceptions\InvalidStateException;
use App\Models\DevelopmentRound;

test('marks the development round as rejected', function () {
    $developmentRound = DevelopmentRound::factory()->create();

    RejectDriverDevelopment::handle($developmentRound);

    $developmentRound->refresh();

    $this->assertEquals(DevelopmentRoundStatus::REJECTED, $developmentRound->status);
});

test('does not mark the development round as rejected for invalid states', function (
    DevelopmentRoundStatus $status,
) {
    $developmentRound = DevelopmentRound::factory()->create([
        'status' => $status,
    ]);

    $this->expectException(InvalidStateException::class);

    RejectDriverDevelopment::handle($developmentRound);

    $developmentRound->refresh();

    $this->assertEquals($status, $developmentRound->status);
})->with([
    [DevelopmentRoundStatus::STARTED],
    [DevelopmentRoundStatus::CONFIRMED],
    [DevelopmentRoundStatus::REJECTED],
    [DevelopmentRoundStatus::FAILED],
]);
