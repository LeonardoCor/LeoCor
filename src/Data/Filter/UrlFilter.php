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
 * Url filtering and validation.
 */
namespace LeoCor\Data\Filter;

/**
 * This class provides URL validation functionalities.
 *
 * @author Leonardo Corazzi <leonardo.corazzi@outlook.it>
 */
class UrlFilter extends Filter
{
    /**
     * Bitwise flags value.
     * 
     * @var int
     */
    private $flags = 0;
    /**
     * Constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        parent::__construct($url);
    }
    
    public function getOptions(): array
    {
        $opt = ['flags' => $this->flags];
        return $opt;
    }
    
    public function reset(): void
    {
        $this->flags = 0;
    }
    
    public function validate(): bool
    {
        $check = filter_var($this->data, FILTER_VALIDATE_URL, $this->getOptions());
        if ($check !== false) {
            $check = true;
        }
        return $check;
    }
}
