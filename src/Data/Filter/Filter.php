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
 * Data filtering and validation.
 */
namespace LeoCor\Data\Filter;

/**
 * Validation and sanitization of data.
 * 
 * Data has to be validated and sanitized, coming it from outside.
 *
 * @author Leonardo Corazzi <leonardo.corazzi@outlook.it>
 */
abstract class Filter
{
    /**
     * Data to be filtered.
     * 
     * @var mixed
     */
    protected $data;
    
    /**
     * Constructor.
     * 
     * Data is passed in the filter and stays unaltered.
     * @param mixed $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
    
    /**
     * Restore all filter options to their default value.
     * 
     * @return void
     */
    abstract public function reset(): void;
    
    /**
     * Retrieves a list of all current option flags.
     * 
     * @return array
     */
    abstract public function getOptions(): array;
    
    /**
     * Asserts data being valid or not.
     * 
     * @return bool
     */
    abstract public function validate(): bool;
}
