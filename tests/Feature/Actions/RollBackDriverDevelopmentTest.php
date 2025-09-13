<?php

declare(strict_types=1);

use App\Actions\RollBackDriverDevelopment;
use App\Enums\DevelopmentRoundStatus;
use App\Exceptions\InvalidStateException;
use App\Jobs\RollBackDriverDevelopmentJob;
use App\Models\DevelopmentRound;

test('marks the development round as pending', function () {
    Queue::fake();

    $developmentRound = DevelopmentRound::factory()
        ->confirmed()
        ->create();

    RollBackDriverDevelopment::handle($developmentRound);

    $developmentRound->refresh();

    $this->assertEquals(DevelopmentRoundStatus::PENDING_CONFIRMATION, $developmentRound->status);

    Queue::assertPushed(RollBackDriverDevelopmentJob::class);
});

test('does not mark the round as pending for invalid states', function (
    DevelopmentRoundStatus $status,
) {
    Queue::fake();

    $developmentRound = DevelopmentRound::factory()->create([
        'status' => $status,
    ]);

    $this->expectException(InvalidStateException::class);

    RollBackDriverDevelopment::handle($developmentRound);

    $developmentRound->refresh();

    $this->assertEquals($status, $developmentRound->status);

    Queue::assertNotPushed(RollBackDriverDevelopmentJob::class);
})->with([
    [DevelopmentRoundStatus::STARTED],
    [DevelopmentRoundStatus::PENDING_CONFIRMATION],
    [DevelopmentRoundStatus::REJECTED],
    [DevelopmentRoundStatus::FAILED],
]);
