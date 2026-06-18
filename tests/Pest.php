<?php

declare(strict_types=1);

use Illuminate\Foundation\Auth\User;
use YezzMedia\UserSupport\Tests\TestCase;

require_once __DIR__.'/TestCase.php';

uses(TestCase::class)
    ->beforeEach(fn () => User::unguard())
    ->in(__DIR__.'/Feature', __DIR__.'/Unit');
