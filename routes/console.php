<?php
declare(strict_types=1);

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
	$quote = 'Keep going — inspiring quote not available.';
	if (class_exists(Inspiring::class) && method_exists(Inspiring::class, 'quote')) {
		try {
			$quote = Inspiring::quote();
		} catch (\Throwable $e) {
			// احمِ التنفيذ من أي استثناء غير متوقع
			$quote = 'Inspiring quote could not be retrieved.';
		}
	}
	$this->comment($quote);
})->purpose('Display an inspiring quote');
