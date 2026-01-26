<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
Artisan::command('create:json-files', function () {
    $command = new CreateJsonFiles();
    $command->handle();
})->describe('Create JSON files for the application');