<?php

use Dyrynda\Defibrillator\Defibrillator;
use Dyrynda\Defibrillator\Tests\Fixtures\Artisan;
use Dyrynda\Defibrillator\Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;

uses(TestCase::class);

it('correctly identifies the heart to defibrillate', function () {
    expect(new Artisan())->heart()->toBe('Artisan');
});

it('checks that a pulse exists', function () {
    expect($class = new Artisan())->hasPulse()->toBeFalse();

    Cache::put('Artisan', now()->addSeconds(10));

    expect($class->hasPulse())->toBeTrue();
});

it('checks that a pulse has normal rhythm', function () {
    Date::setTestNow();

    expect($class = new Artisan())->hasNormalRhythm()->toBeFalse();

    Cache::put('Artisan', now()->addSeconds(10));

    expect($class->hasNormalRhythm())->toBeTrue();

    $this->travel(10)->seconds();

    expect($class->hasNormalRhythm())->toBeFalse();
});

it('checks that a pulse has an abnormal rhythm', function () {
    Date::setTestNow();

    expect($class = new Artisan())->hasAbnormalRhythm()->toBeTrue();

    Cache::put('Artisan', now()->addSeconds(10));

    expect($class->hasAbnormalRhythm())->toBeFalse();

    $this->travel(10)->seconds();

    expect($class->hasAbnormalRhythm())->toBeTrue();
});

it('can defibrillate a heart', function () {
    Date::setTestNow(Date::now()->startOfSecond());

    expect($class = new Artisan())->hasPulse()->toBeFalse();

    $class->defibrillate();

    expect($class->hasPulse())->toBeTrue();

    $this->travel(89)->seconds();

    expect($class)
        ->hasPulse()->toBeTrue()
        ->hasNormalRhythm()->toBeTrue()
        ->hasAbnormalRhythm()->toBeFalse();

    $this->travel(1)->seconds();

    expect($class)
        ->hasPulse()->toBeTrue()
        ->hasNormalRhythm()->toBeFalse()
        ->hasAbnormalRhythm()->toBeTrue();
});

it('can set the intrabeat interval', function () {
    Date::setTestNow(Date::now()->startOfSecond());

    $class = new class() {
        use Defibrillator;

        public function interval(): int
        {
            return 30;
        }
    };

    expect($class->hasPulse())->toBeFalse();

    $class->defibrillate();

    expect($class->hasPulse())->toBeTrue();

    expect(Cache::get($class->heart()))->toEqual(Date::now()->addSeconds(30));

    $this->travel(29)->seconds();

    expect($class)
        ->hasNormalRhythm()->toBeTrue()
        ->hasAbnormalRhythm()->toBeFalse();

    $this->travel(1)->seconds();

    expect($class)
        ->hasNormalRhythm()->toBeFalse()
        ->hasAbnormalRhythm()->toBeTrue();
});

it('falls back to configured intrabeat interval', function () {
    Date::setTestNow(Date::now()->startOfSecond());

    Config::set('defibrillator.interval', 30);

    expect($class = new Artisan())->hasPulse()->toBeFalse();

    $class->defibrillate();

    expect($class)
        ->hasPulse()->toBeTrue()
        ->hasNormalRhythm()->toBeTrue()
        ->hasAbnormalRhythm()->toBeFalse();

    $this->travel(30)->seconds();

    expect($class)
        ->hasPulse()->toBeTrue()
        ->hasNormalRhythm()->toBeFalse()
        ->hasAbnormalRhythm()->toBeTrue();
});

it('can override the heart name', function () {
    Date::setTestNow(Date::now()->startOfSecond());

    $class = new class() {
        use Defibrillator;

        public function heart(): string
        {
            return 'PorcelainHeart';
        }
    };

    $class->defibrillate();

    expect(Cache::get('PorcelainHeart'))->toEqual(Date::now()->addSeconds(90));
});
