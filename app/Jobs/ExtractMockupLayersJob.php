<?php

namespace App\Jobs;

use Exception;
use App\Models\Mockup;
use Illuminate\Bus\Queueable;
use App\Services\MockupService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExtractMockupLayersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Mockup $mockup,
    ) {
    }

    public function handle()
    {
        if ($this->mockup->status !== 'uploaded') {
            return;
        }

        $this->mockup->update([
            'status' => 'in-progress',
        ]);

        try {
            (new MockupService())->extractLayers(
                $this->mockup,
            );
        } catch (Exception) {
            $this->markMockupAsFailed();
        }

        $this->mockup->update([
            'status' => 'done',
        ]);
    }

    public function failed()
    {
        $this->markMockupAsFailed();
    }

    private function markMockupAsFailed()
    {
        $this->mockup->update([
            'status' => 'failed',
        ]);
    }
}
