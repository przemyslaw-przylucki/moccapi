<?php

namespace App\Http\Controllers;

use App\Models\Mockup;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Illuminate\Support\Str;
use Imagine\Imagick\Imagine;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\GenerateMockupRequest;

class GenerateMockupController
{

    public function __invoke(GenerateMockupRequest $request, Mockup $mockup)
    {
        $imageBlob = Storage::disk('s3')->readStream($mockup->file_path);

        $format = $request->validated('format');
        $output = Str::ulid() . '.' . $format;

        $imagine = new Imagine();

        $image = $imagine->read($imageBlob);
        $layers = $image->layers();

        foreach ($mockup->layers as $layer) {
            $resource = $request->validated('replacement');
            $replacementImage = $imagine->read(fopen($resource, 'rb'));
            $replacement = $layers->get($layer->index)->paste($replacementImage->resize(new Box($layer->width, $layer->height)), new Point(0, 0));
            $layers->set($layer->index, $replacement);
        }

        $layers->remove(0);

        Storage::disk('s3-public')->put($output, $image->get($format));

        return view('test', [
            'src' => Storage::disk('s3-public')->url($output),
        ]);
    }

}
