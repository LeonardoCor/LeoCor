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
 * Filter extension for email addresses input data.
 */
namespace LeoCor\Data\Filter;

/**
 * Email values sanitizing and validating.
 *
 * @author Leonardo Corazzi <leonardo.corazzi@outlook.it>
 */
class EmailFilter extends Filter
{
    /**
     * Constructor.
     * 
     * @param string $eMailAddress
     */
    public function __construct(string $eMailAddress)
    {
        parent::__construct($eMailAddress);
    }
    
    /**
     * Options for email validating.
     * 
     * _No options required_.
     * 
     * Upcoming: Unicode validating support.
     * @return array Empty array
     */
    public function getOptions(): array
    {
        $opt = [];
        return $opt;
    }
    
    public function reset(): void
    {
        return;
    }
    
    /**
     * Checks if string is a valid eMail address, like specified in RFC 822.
     * 
     * @return bool
     */
    public function validate(): bool
    {
        $validated = filter_var($this->data, FILTER_VALIDATE_EMAIL);
        if ($validated !== false) {
            $validated = true;
        }
        return $validated;
    }
    
    /**
     * Email sanitization.
     * 
     * Removes all characters except letters, digits and `!`, `#`, `$`, `%`,
     * `&`, `'`, `*`, `+`, `-`, `=`, `?`, `^`, `_`, `` ` ``,
     * `{`, `|`, `}`, `~`, `@`, `.`, `[`, `]`.
     * @return string
     */
    public function sanitize()
    {
        $sanitized = filter_var($this->data, FILTER_SANITIZE_EMAIL);
        return  $sanitized;
    }
}
