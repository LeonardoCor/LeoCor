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
 * Flags for filter correspond to PHP built in variable filters flags, relative
 * to sanitizing and validating input. See
 * {@link https://www.php.net/manual/en/filter.filters.php PHP Filters} for
 * detailed informations.
 */
namespace LeoCor\Data\Filter;

/**
 * Filter flags can be bitwise combined, set, modified, retrieved and reset.
 *
 * @author Leonardo Corazzi <leonardo.corazzi@outlook.it>
 */
class FilterFlags
{
    /**
     * Default flags bitwise value.
     * @var int
     */
    private $defaultValue = 0;
    
    /**
     * Flags current bitwise value.
     * @var int
     */
    private $value = 0;
    
    /**
     * Bitwise flags default value.
     * 
     * @param int $defaultValue
     */
    public function __construct(int $defaultValue = 0)
    {
        $this->defaultValue = $defaultValue;
    }
    
    public function setInOptionArray(array &$filterOptions): void
    {
        $filterOptions['flags'] = $this->value;
    }
}
