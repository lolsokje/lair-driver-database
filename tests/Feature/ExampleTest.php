<?php

declare(strict_types=1);

it('returns a successful response', function () {
    $this->get(route('index'))->assertOk();
});
