<?php

namespace App\Http\Controllers;

use App\Models\Mockup;
use App\Services\MockupService;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\GenerateMockupRequest;

class GenerateMockupController
{

    public function __construct(
        protected MockupService $service,
    ) {
    }

    public function __invoke(GenerateMockupRequest $request, Mockup $mockup)
    {
        $user = $request->user();

        if ($user->team_id !== $mockup->team_id) {
            return $this->deny("This mockup doesn't belong to your team.");
        }

        if (! $user->team->canGenerateMockup()) {
            return $this->deny("Your limit is maxed out");
        }

        $output = $this->service->generate(
            $mockup,
            $request->validated('replacements'),
            $request->validated('format'),
            $request->validated('zoom', 100),
            $request->validated('quality', 1),
        );

        $output = Storage::disk('s3-public')->url($output->file_path);

        return view('test', [
            'src' => $output,
        ]);

        return response()->json([
            'url' => $output,
            'name' => $mockup->name,
            'zoom' => $request->validated('zoom'),
        ]);
    }

    private function deny(string $message)
    {
        return response()->json([
            'message' => $message,
        ], 403);
    }

}
