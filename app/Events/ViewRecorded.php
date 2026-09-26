<?php

namespace App\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ViewRecorded
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param Model $viewable The model being viewed (Mahasiswa or Project)
     * @param string|null $ip The visitor's IP address
     * @param string|null $userAgent The visitor's User-Agent string
     * @param string $source The traffic source (direct, qr, internal, external)
     */
    public function __construct(
        public Model $viewable,
        public ?string $ip,
        public ?string $userAgent,
        public string $source = 'direct'
    ) {}
}
