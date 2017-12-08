<?php
/**
 * Date: 24.1.2017
 * Time: 13:43
 * Alexey Zhilin
 */

use Illuminate\Foundation\Inspiring;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');