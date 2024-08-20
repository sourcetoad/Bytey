<?php

declare(strict_types=1);

namespace Sourcetoad\Bytey;

use Illuminate\Support\Arr;

class Bytey
{
    const PRECISION = 5;

    /**
     * Encodes an array of coordinates into a Google Polyline string.
     * Expects an array of <lat, lng> tuples.
     */
    public static function googlePolylineEncode(array $coordinates): string
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
            $offset = $index % $tupleSize;

            // Take the signed value and multiply it by 1e5, round the result.
            $coordinate = (int) round((float) $coordinate * pow(10, self::PRECISION));

            // only include the offset from the previous (except the first)
            $value = $index === $offset ? $coordinate : $coordinate - $previous[$offset];
            $previous[$offset] = $coordinate;

            // Shift the value left 1 bit if it's negative.
            $value = ($value < 0) ? ~($value << 1) : ($value << 1);

            // Break the value into 5-bit chunks and encode them + add 63.
            $chunk = '';
            while ($value >= 0x20) {
                $chunk .= chr((0x20 | ($value & 0x1F)) + 63);
                $value >>= 5;
            }

            $chunk .= chr($value + 63);
            $encodedString .= $chunk;

            $index++;
        }

        return $encodedString;
    }
}
