<?php

namespace App\Listeners;

use App\Events\ViewRecorded;
use App\Services\ViewTrackingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Listener to log portfolio/project views.
 * 
 * Note: To process view tracking asynchronously in production, 
 * simply uncomment "implements ShouldQueue" below and ensure 
 * your queue worker (e.g. php artisan queue:work) is running.
 */
class LogViewRecord // implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct(protected ViewTrackingService $viewTrackingService) {}

    /**
     * Handle the event.
     */
    public function handle(ViewRecorded $event): void
    {
        $this->viewTrackingService->recordView(
            $event->viewable,
            $event->ip,
            $event->userAgent,
            $event->source
        );
    }
}
