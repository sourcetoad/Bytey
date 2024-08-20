<?php

declare(strict_types=1);

namespace Sourcetoad\Bytey\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sourcetoad\Bytey\Bytey;

class ByteyTest extends TestCase
{
    #[DataProvider('googleDataProvider')]
    public function testGooglePolylineEncode(array $coordinates, string $expected): void
    {
        $this->assertEquals($expected, Bytey::googlePolylineEncode($coordinates));
    }

    public static function googleDataProvider(): array
    {
        return [
            'simple example' => [
                'coordinates' => [
                    [-179.9832104],
                ],
                'expected' => '`~oia@',
            ],
            'rounding example' => [
                'coordinates' => [
                    [48.000006, 2.000004],
                    [48.00001, 2.00000],
                ],
                'expected' => 'a_~cH_seK??',
            ],
            'google example' => [
                'coordinates' => [
                    [38.5, -120.2],
                    [40.7, -120.95],
                    [43.252, -126.453],
                ],
                'expected' => '_p~iF~ps|U_ulLnnqC_mqNvxq`@',
            ],
        ];
    }
}
