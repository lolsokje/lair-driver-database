<?php

declare(strict_types=1);

use App\Actions\ConfirmDriverDevelopment;
use App\Enums\DevelopmentRoundStatus;
use App\Exceptions\InvalidStateException;
use App\Jobs\ConfirmDriverDevelopmentJob;
use App\Models\DevelopmentRound;

test('marks the development round as confirmed', function () {
    Queue::fake();

    $developmentRound = DevelopmentRound::factory()->create();

    ConfirmDriverDevelopment::handle($developmentRound);

    $developmentRound->refresh();

    $this->assertEquals($developmentRound->status, DevelopmentRoundStatus::CONFIRMED);

    Queue::assertPushed(ConfirmDriverDevelopmentJob::class);
});

test('does not mark development as confirmed for invalid state', function (
    DevelopmentRoundStatus $status,
) {
    Queue::fake();

    $developmentRound = DevelopmentRound::factory()->create([
        'status' => $status,
    ]);

    $this->expectException(InvalidStateException::class);

    ConfirmDriverDevelopment::handle($developmentRound);

    $developmentRound->refresh();

    $this->assertEquals($status, $developmentRound->status);

    Queue::assertNotPushed(ConfirmDriverDevelopmentJob::class);
})->with([
    [DevelopmentRoundStatus::STARTED],
    [DevelopmentRoundStatus::CONFIRMED],
    [DevelopmentRoundStatus::FAILED],
]);
