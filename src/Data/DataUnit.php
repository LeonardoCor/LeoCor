<?php
/*
 * Copyright (C) 2019 Leonardo Corazzi
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
namespace LeoCor\Data;

/**
 * Data Unit represents a unique piece of data incoming from outside.
 * 
 * This class is meant to be used to sanitize and validate input data so that
 * they can safely be used inside the system.
 * 
 * 
 * More than the class itself, its descendants are useful for the purpose, given
 * their specializations based on type of data and category.
 *
 * @author Leonardo Corazzi <leonardo.corazzi@outlook.it>
 */
class DataUnit
{
    /**
     * @var string
     */
    private $name;
    
    /**
     * @var mixed
     */
    private $value;
    
    /**
     * Constructor.
     * 
     * @param string $name
     * @param mixed $value
     */
    public function __construct(string $name, $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }
    
    public function __toString()
    {
        $strVal = (string) $this->value;
        return $strVal;
    }
    
    public function getValue()
    {
        return $this->value;
    }
}
