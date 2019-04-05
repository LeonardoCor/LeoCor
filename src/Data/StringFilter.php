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
 * String filtering and validation.
 */
namespace LeoCor\Data;

/**
 * Set of string filters.
 * 
 * Default options:
 * * _No\_Encode\_Quotes_: __true__
 * * _Encode\_Amp_: __false__
 * * _Strip\_Backtick_: __false__
 *
 * @author Leonardo Corazzi <leonardo.corazzi@outlook.it>
 */
class StringFilter extends Filter
{
    private $flags = FILTER_FLAG_NO_ENCODE_QUOTES;

    public function reset(): void
    {
        $this->flags = FILTER_FLAG_NO_ENCODE_QUOTES;
    }
    
    public function getOptions(): array
    {
        $options = ['flags' => $this->flags];
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
     * 
     * Default: __true__.
     * @param bool $flag
     * @return void
     */
    public function setFlagNoEncodeQuotes(bool $flag): void
    {
        if ($flag) {
            $this->flags |= FILTER_FLAG_NO_ENCODE_QUOTES;
        } else {
            $this->flags &= !FILTER_FLAG_NO_ENCODE_QUOTES;
        }
    }
    
    /**
     * Ampersands encoding.
     * 
     * Encodes ampersands (&).
     * 
     * Default: __false__.
     * @param bool $flag
     * @return void
     */
    public function setFlagEncodeAmp(bool $flag): void
    {
        if ($flag) {
            $this->flags |= FILTER_FLAG_ENCODE_AMP;
        } else {
            $this->flags &= !FILTER_FLAG_ENCODE_AMP;
        }
    }
    
    /**
     * Strips backticks if enabled.
     * 
     * Default: __false__.
     * @param bool $flag
     * @return void
     */
    public function setFlagStripBacktick(bool $flag): void
    {
        if ($flag) {
            $this->flags |= FILTER_FLAG_STRIP_BACKTICK;
        } else {
            $this->flags &= !FILTER_FLAG_STRIP_BACKTICK;
        }
    }
    
    /**
     * String sanitization.
     * 
     * Encodes in HTML entities, or strips, special chars, depending on option
     * flags setting: _'_, _"_, _&_ and _`_.
     * @return string
     */
    public function sanitize(): string
    {
        if (!is_string($this->data)) {
            return "";
        }
        
        $options = $this->getOptions();
        $sanitized = filter_var($this->data, FILTER_SANITIZE_STRING, $options);
        return $sanitized;
    }
    
    /**
     * String sanitization.
     * 
     * Sanitizes string like in ::sanitize() plus _<_ and _>_, except _`_.
     * If __$full__ is passed, ...
     * @param bool $full
     */
    public function sanitizeSpecialCharsToHtml(bool $full = false)
    {
        $options = $this->getOptions();
        $filterType = 0;
        if ($full) {
            $filterType = FILTER_SANITIZE_FULL_SPECIAL_CHARS;
        } else {
            $filterType = FILTER_SANITIZE_SPECIAL_CHARS;
        }
        $sanitized = filter_var($this->data, $filterType, $options);
        return $sanitized;
    }
}
