<?php

declare(strict_types=1);

namespace Sourcetoad\Bytey;

use Illuminate\Support\Arr;

class Bytey
{
    const PRECISION = 5;

    /** to ensure proper display, encoded values are summed with 63 (the ASCII character '?')  */
    const ASCII_OFFSET = 63;

    /**
     * Encodes an array of coordinates into a Google Polyline string.
     * Expects an array of <lat, lng> tuples.
     *
     * @link https://developers.google.com/maps/documentation/utilities/polylinealgorithm
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

        foreach ($coordinates as $coordinate) {
            // Record the current index and offset for delta encoding.
            $offset = $index % $tupleSize;

            // Take the signed value and multiply it by 1e5, round the result.
            $coordinate = (int) round((float) $coordinate * pow(10, self::PRECISION));

            // Only include the offset from the previous (except the first)
            $value = $coordinate - ($previous[$offset] ?? 0);
            $previous[$offset] = $coordinate;

            // Handle negative values (bitwise NOT, then left shift)
            $value = $value < 0 ? ~($value << 1) : ($value << 1);

            // Break the value into 5-bit chunks ensuring we skip the last chunk to work manually.
            $chunk = '';
            while ($value >= 0x20) {
                // OR each chunk with 0x20 and add 63 to ensure proper display.
                $chunk .= chr((0x20 | ($value & 0x1F)) + self::ASCII_OFFSET);
                $value >>= 5;
            }

            // Add the final chunk and append it to the encoded string.
            $chunk .= chr($value + self::ASCII_OFFSET);
            $encodedString .= $chunk;

            $index++;
        }

        return $encodedString;
    }
}
