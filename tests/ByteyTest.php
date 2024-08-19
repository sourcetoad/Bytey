<?php
declare(strict_types = 1);

namespace Sourcetoad\Bytey\Tests;


use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sourcetoad\Bytey\Bytey;

class ByteyTest extends TestCase
{
    #[DataProvider('googleDataProvider')]
    public function testGoogleEncode(array $coordinates, string $expected): void
    {
        $this->assertEquals($expected, Bytey::googleEncode($coordinates));
    }

    public static function googleDataProvider(): array
    {
        return [
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
