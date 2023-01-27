<?php

namespace App\Services;

use Imagick;
use App\Models\Mockup;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Illuminate\Support\Str;
use Imagine\Imagick\Imagine;
use App\Models\MockupOutput;
use Imagine\Image\ImageInterface;
use Illuminate\Support\Facades\Storage;

class MockupService
{
    public function extractLayers(Mockup $mockup): array
    {
        $imagine = $this->read($mockup);

        $output = [];

        $mockup->update([
            'width' => $imagine->getSize()->getWidth(),
            'height' => $imagine->getSize()->getHeight(),
        ]);

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

    public function generate(Mockup $mockup, array $replacements, string $format, ?int $zoom = 0): MockupOutput
    {
        $zoom ??= 100;
        $imagine = new Imagine();
        $imageBlob = Storage::disk('s3')->readStream($mockup->file_path);

        $image = $imagine->read($imageBlob);
        $layers = $image->layers();

        foreach ($mockup->layers as $index => $layer) {
            $replacementImage = $imagine->read(fopen($replacements[$index], 'rb'));
            $replacement = $layers->get($layer->index)->paste($replacementImage->resize(new Box($layer->width, $layer->height)), new Point(0, 0));
            $layers->set($layer->index, $replacement);
        }

        $layers->remove(0);

        if ($zoom !== 100) {
            $w = $image->getSize()->getWidth();
            $h = $image->getSize()->getHeight();
            $new_h = floor($h * ($zoom / 100));
            $new_w = floor ($w * ($zoom / 100));

            if ($w > $h) {
                $resize_w = $w * $new_h / $h;
                $resize_h = $new_h;
            }
            else {
                $resize_w = $new_w;
                $resize_h = $h * $new_w / $w;
            }

            $image->resize(new Box($resize_w, $resize_h));
//                  ->crop(new Point(($resize_w - $new_w) / 2, ($resize_h - $new_h) / 2), new Box($new_w, $new_h));
        }
//
//        if ($zoom !== 0) {
//            $image->getImagick()->scaleImage(floor($image->getSize()->getWidth() * ($zoom / 100)), 0);
//        }

        $output = Str::ulid() . '.' . $format;
        Storage::disk('s3-public')->put($output, $image->get($format, [
            'jpeg_quality' => 80,
            'png_compression_level' => 3,
            'webp_quality' => 90
        ]));

        return $mockup->outputs()->create([
            'team_uuid' => $mockup->team_uuid,
            'file_path' => $output,
        ]);
    }

    private function read(Mockup $mockup): ImageInterface
    {
        $imageBlob = Storage::disk('s3')->readStream($mockup->file_path);

        $imagine = new Imagine();

        return $imagine->read($imageBlob);
    }
}
