<?php

namespace Tests\Unit\Helper;

use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    public function arrays_are_similar($a, $b)
    {
        // if the indexes don't match, return immediately
        if (count(array_diff_assoc($a, $b))) {
            return false;
        }
        // we know that the indexes, but maybe not values, match.
        // compare the values between the two arrays
        foreach ($a as $k => $v) {
            if ($v !== $b[$k]) {
                return false;
            }
        }
        // we have identical indexes, and no unequal values
        return true;
    }
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_attribute_copy_array()
    {
        $src = [
            "1" => "shallow",
            "2" => "shallow",
            "3" => [
                "1" => "notshallow",
                "2" => "notshallow",
            ],
            "4" => "shallow",
            "5" => [],
        ];
        $dist = shallow_copy_array($src);
        $expect = [
            "1" => "shallow",
            "2" => "shallow",
            "4" => "shallow"
        ];
        $this->assertTrue($this->arrays_are_similar($dist, $expect));
    }
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_attribute_copy_array_of_array()
    {
        $src = [
            [
                "1" => "shallow",
                "2" => "shallow",
                "3" => [
                    "1" => "notshallow",
                    "2" => "notshallow",
                ],
                "4" => "shallow",
                "5" => [],
            ],
            [
                "1" => "shallow",
                "2" => "shallow",
                "3" => [
                    "1" => "notshallow",
                    "2" => "notshallow",
                ],
                "4" => "shallow",
                "5" => [],
            ],
        ];
        $dist = shallow_copy_array_of_array($src);
        $expect = [
            [
                "1" => "shallow",
                "2" => "shallow",
                "4" => "shallow",
            ],
            [
                "1" => "shallow",
                "2" => "shallow",
                "4" => "shallow",
            ],
        ];
        $this->assertTrue($this->arrays_are_similar($dist, $expect));
    }
}
