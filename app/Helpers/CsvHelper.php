<?php

namespace App\Helpers;

class CsvHelper
{
    public static function read(mixed $csvFile, string $separator): array
    {
        $array = [];
        if (($handle = fopen($csvFile, "r")) !== FALSE)
        {
            while (($data = fgetcsv($handle, 1000, $separator)) !== FALSE)
            {
                $num = count($data);
                $item = [];
                for ($index = 0; $index < $num; $index++) {
                    $item[] = $data[$index];
                }
                $array[] = $item;
            }
            fclose($handle);
        }

        return $array;
    }

}
