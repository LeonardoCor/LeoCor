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
 * Set of string filters.
 * 
 * Default options:
 * * _No\_Encode\_Quotes_: __true__
 *
 * @author Leonardo Corazzi <leonardo.corazzi@outlook.it>
 */
class StringFilter extends Filter
{
    private $fltNoEncodeQuotes = true;
    
    public function reset(): void
    {
        $this->fltNoEncodeQuotes = true;
    }
    
    public function getOptions(): array
    {
        $flags = 0;
        if ($this->fltNoEncodeQuotes) {
            $flags = FILTER_FLAG_NO_ENCODE_QUOTES;
        }
        $options = [
            'flags' => $flags
        ];
        return $options;
    }
    
    /**
     * Asserts data being a String or not.
     * 
     * @return bool
     */
    public function validate(): bool
    {
        return is_string($this->data);
    }
    
    /**
     * Quotes encoding.
     * 
     * If this flag is present, single (') and double (") quotes will not
     * be encoded.
     * @param bool $flag
     * @return void
     */
    public function noEncodeQuotes(bool $flag): void
    {
        $this->fltNoEncodeQuotes = $flag;
    }
    
    public function sanitize(): string
    {
        if (!is_string($this->data)) {
            return "";
        }
        
        $options = $this->getOptions();
        $sanitized = filter_var($this->data, FILTER_SANITIZE_STRING, $options);
        return $sanitized;
    }
}
