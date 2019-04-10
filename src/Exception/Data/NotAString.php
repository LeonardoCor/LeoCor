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
/**
 * Exception for data expected to be string.
 */
namespace LeoCor\Exception\Data;
/**
 * Exception for data expected to be a string.
 *
 * @author Leonardo Corazzi <leonardo.corazzi@outlook.it>
 */
class NotAString extends \Exception
{
    /**
     * Constructor.
     * 
     * @param string $dataName Data parameter name
     * @param \Throwable $previous
     */
    public function __construct(string $dataName = null, \Throwable $previous = null)
    {
        $msg = "";
        if (!empty($dataName)) {
            $msg .= "Data given in `$dataName` ";
        } else {
            $msg .= "Given data ";
        }
        $msg .= "is not a string.";
        parent::__construct($msg, $previous);
    }
}
