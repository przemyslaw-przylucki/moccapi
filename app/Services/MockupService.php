<?php

namespace App\Services;

use App\Models\Mockup;
use Imagine\Imagick\Imagine;
use Imagine\Image\ImageInterface;
use Illuminate\Support\Facades\Storage;

class MockupService
{
    public function extractLayers(Mockup $mockup): array
    {
        $imagine = $this->read($mockup);

        $output = [];
        foreach ($imagine->layers() as $index => $layer) {
            if (! $index) {
                continue;
            }

            $label = $layer->getImagick()->getImageProperty('label');

            if (str($label)->lower()->startsWith(['#'])) {
                $size = $layer->getSize();

                $output[] = [
                    'label' => $label,
                    'index' => $index,
                    'width' => $size->getWidth(),
                    'height' => $size->getHeight(),
                ];
            }
        }

        foreach ($output as $layerData) {
            $mockup->layers()->create([
               'label' => $layerData['label'],
               'index' => $layerData['index'],
               'width' => $layerData['width'],
               'height' => $layerData['height'],
            ]);
        }

        return $output;
    }

    private function read(Mockup $mockup): ImageInterface
    {
        $imageBlob = Storage::disk('s3')->readStream($mockup->file_path);

        $imagine = new Imagine();

        return $imagine->read($imageBlob);
    }
}
