<?php

declare(strict_types=1);

namespace Sourcetoad\Bytey;

use Illuminate\Support\Arr;

class Bytey
{
    const PRECISION = 6;

    public static function googleEncode(array $coordinates): string
    {
        $tupleSize = count($coordinates[0] ?? []);
        $coordinates = Arr::flatten($coordinates);
        $previous = array_fill(0, $tupleSize, 0);

        $encodedString = '';
        $index = 0;

        if ($tupleSize === 0) {
            throw new \InvalidArgumentException('Coordinates must be items of <lat, lng> tuples.');
        }

        // A PHP implementation of the Google Polyline Algorithm
        // https://developers.google.com/maps/documentation/utilities/polylinealgorithm
        foreach ($coordinates as $coordinate) {
            // Record the current index and offset for delta encoding.
            $offset = $index++ % $tupleSize;
            $previous[$offset] = $coordinate;

            // Take the signed value and multiply it by 1e5, round the result.
            $coordinate = (float) $coordinate;
            $coordinate = (int) round($coordinate * pow(10, self::PRECISION));

            // Delta encode the value.
            $value = $coordinate - $previous[$offset];
            $value = ($value < 0) ? ~($value << 1) : ($value << 1);

            // Break the value into 5-bit chunks and encode them + add 63.
            $chunk = '';
            while ($value >= 0x20) {
                $chunk .= chr((0x20 | ($value & 0x1f)) + 63);
                $value >>= 5;
            }

            $chunk .= chr($value + 63);
            $encodedString .= $chunk;
        }

        return $encodedString;
    }
}
