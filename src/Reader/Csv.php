<?php

namespace App\Reader;

use Symfony\Component\HttpFoundation\File\UploadedFile;

final class Csv
{
    public const SEPARATOR = ',';

    public function read(UploadedFile $file): \Generator
    {
        if (($handle = fopen($file, "r")) !== false) {
            while (($data = fgetcsv($handle, 0, self::SEPARATOR)) !== false) {
                yield $data;
            }
            fclose($handle);
        }
    }
}