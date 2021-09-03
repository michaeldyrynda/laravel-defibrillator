<?php

namespace Dyrynda\Defibrillator;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

trait Defibrillator
{
    protected static string $heart;

    public function heart(): string
    {
        return static::$heart ??= Str::of($this::class)->classBasename();
    }

    public function hasPulse(): bool
    {
        return Cache::has($this->heart());
    }

    public function hasNormalRhythm(): bool
    {
        return Cache::get($this->heart())?->isFuture() ?? false;
    }

    public function hasAbnormalRhythm(): bool
    {
        return ! $this->hasNormalRhythm();
    }

    public function defibrillate(): void
    {
        $interval = method_exists($this, 'interval')
            ? call_user_func([$this, 'interval'])
            : Config::get('defibrillator.interval');

        Cache::put($this->heart(), Date::now()->addSeconds($interval));
    }
}
