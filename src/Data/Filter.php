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
namespace LeoCor\Data;
/**
 * Validation and sanitization of data.
 *
 * @author Leonardo Corazzi <leonardo.corazzi@outlook.it>
 */
abstract class Filter {
    protected $data;
    
    public function __construct($data) {
        $this->data = $data;
    }
    
    /**
     * Asserts data being valid or not.
     * 
     * @return bool
     */
    abstract public function validate(): bool;
    
    /**
     * Restore all filter options to their default value.
     * 
     * @return void
     */
    abstract public function reset(): void;
    
    abstract public function getOptions(): array;
}
