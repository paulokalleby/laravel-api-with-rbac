<?php

namespace App\Traits;

trait Deviceable
{
    protected function resolveDevice(?string $userAgent): string
    {
        if (! $userAgent) {
            return 'Unknown device';
        }

        return match (true) {
            str_contains($userAgent, 'iPhone')     => 'iPhone',
            str_contains($userAgent, 'Android')    => 'Android',
            str_contains($userAgent, 'Macintosh')  => 'Mac',
            str_contains($userAgent, 'Windows')    => 'Windows',
            default                                => 'Unknown device',
        };
    }
}
