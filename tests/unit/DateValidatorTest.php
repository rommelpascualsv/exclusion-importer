<?php

namespace Test\Unit;

use App\Services\DateValidator;
use CDM\Test\TestCase;

class DateValidatorTest extends TestCase
{

    public function test_valid_date_string_returns_true()
    {
        $ret = DateValidator::validateString('10/12/1991');
        $this->assertTrue($ret);
    }

    public function test_invalid_date_string_returns_false()
    {
        $ret = DateValidator::validateString('notADate');
        $this->assertFalse($ret);
    }
}
