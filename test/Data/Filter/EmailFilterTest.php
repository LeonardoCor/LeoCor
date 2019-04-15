<?php
/*
 * Copyright (C) 2019 Leonardo Corazzi <leonardo.corazzi@outlook.it>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace LeoCor\Data\Filter;

use PHPUnit\Framework\TestCase;
/**
 * Description of EmailFilterTest
 *
 * @author Leonardo Corazzi <leonardo.corazzi@outlook.it>
 */
class EmailFilterTest extends TestCase
{
    public function testSanitize()
    {
        $flt = new EmailFilter("abc123DEF!#$%&'*+-=?^_`{|}~@.[]\"<>\\/:;,");
        $expectedStr = "abc123DEF!#$%&'*+-=?^_`{|}~@.[]";
        $this->assertSame($expectedStr, $flt->sanitize());
    }
    
    public function testValidate()
    {
        $fltTrue = new EmailFilter('myemail@mydomain.tst');
        $this->assertTrue($fltTrue->validate());
        $fltFalse = new EmailFilter('wrong#email@.klert');
        $this->assertFalse($fltFalse->validate());
    }
}
