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
namespace LeoCor\Data\Filter;

/**
 * Set of string filters.
 * 
 * Strings can be _sanitized_, that is they can be clean or char encoded depending
 * on string use needs.
 * 
 * For example, if a string has to be injected in a chunk
 * of HTML code, it can be sanitized; this also prevents HTML injection of external
 * malicious code.
 * 
 * ###Usage
 * 
 * A string has to be passed to {@see Filter::__construct() constructor}.
 * 
 * For sanitizing, same flags are provided with their default values:
 * * _Encode\_Quotes_: __false__, enables single and double quotes encoding
 * * _Encode\_Amp_: __false__, performs ampersand encoding
 * * _Strip\_Backtick_: __false__, strips backticks (`` ` ``)
 * 
 * This flags can be set through the _setFalgXXX()_ methods.
 *
 * @author Leonardo Corazzi <leonardo.corazzi@outlook.it>
 */
class StringFilter extends Filter
{
    /**
     * Bitwise value of enabled flags
     * 
     * @private
     * @var int
     */
    private $flags = FILTER_FLAG_NO_ENCODE_QUOTES;
    
    /**
     * Constructor.
     * 
     * @param string $data
     * @throws \LeoCor\Exception\Data\NotAString Thrown if __$data__ is not a string
     */
    public function __construct($data)
    {
        parent::__construct($data);
        if (!is_string($data)) {
            throw new \LeoCor\Exception\Data\NotAString;
        }
    }
    
    /**
     * Resets flags to their default values.
     * 
     * @return void
     */
    public function reset(): void
    {
        $this->flags = FILTER_FLAG_NO_ENCODE_QUOTES;
    }
    
    /**
     * Provides the options needed for filtering.
     * 
     * The riturned array is so composed:
     * - _flags_ => (__int__) bitwise flags value
     * @return array
     */
    public function getOptions(): array
    {
        $options = ['flags' => $this->flags];
        return $options;
    }
    
    /**
     * Asserts data being a string or not.
     * 
     * @return bool
     */
    public function validate(): bool
    {
        return is_string($this->data);
    }
    
    /**
     * Email validation.
     * 
     * Tests if string is a valid email address.
     * 
     * Flags are ineffective.
     * 
     * _No Unicode support_.
     * @return boolean
     */
    public function validateEmail()
    {
        $result = filter_var($this->data, FILTER_VALIDATE_EMAIL);
        if ($result !== false) {
            $result = true;
        }
        return $result;
    }
    
    /**
     * Hostname validation.
     * 
     * Tests if string is a valid hostname.
     * 
     * Flags are ineffective.
     * @return boolean
     */
    public function validateHostName()
    {
        $opt = $this->getOptions();
        $opt['flags'] |= FILTER_FLAG_HOSTNAME;
        $result = filter_var($this->data, FILTER_VALIDATE_DOMAIN, $opt);
        if ($result !== false) {
            $result = true;
        }
        return $result;
    }
    
    /**
     * Quotes encoding.
     * 
     * If this flag is enabled, single (`'`) and double (`"`) quotes will
     * be encoded.
     * 
     * Default: __false__.
     * @param bool $flag
     * @return void
     */
    public function setFlagEncodeQuotes(bool $flag): void
    {
        $this->setFlag(!$flag, FILTER_FLAG_NO_ENCODE_QUOTES);
    }
    
    /**
     * Ampersands encoding.
     * 
     * Encodes ampersands (`&`).
     * 
     * Default: __false__.
     * @param bool $flag
     * @return void
     */
    public function setFlagEncodeAmp(bool $flag): void
    {
        $this->setFlag($flag, FILTER_FLAG_ENCODE_AMP);
    }
    
    /**
     * Backticks stripping.
     * 
     * Strips backticks (`` ` ``) if enabled.
     * 
     * Default: __false__.
     * @param bool $flag
     * @return void
     */
    public function setFlagStripBacktick(bool $flag): void
    {
        $this->setFlag($flag, FILTER_FLAG_STRIP_BACKTICK);
    }
    
    /**
     * String sanitization.
     * 
     * Encodes in HTML entities, or strips, special chars, depending on option
     * flags setting:
     * - `'`, `"` will be encoded with _&#38;#39;_ and _&#38;#34;_, 
     *   if `Encode_Quotes` flag is enabled
     * - `&` will be encoded with _&#38;#38;_, if flag `Encode_Amp` is enabled
     * - `` ` `` will be _stripped_, if `Strip_Backtick` flag is enabled
     * 
     * Always strips _tags_ and `<`.
     * @return string
     */
    public function sanitize(): string
    {
        $options = $this->getOptions();
        $sanitized = filter_var($this->data, FILTER_SANITIZE_STRING, $options);
        return $sanitized;
    }
    
    /**
     * String sanitization of HTML special chars.
     * 
     * Sanitizes string like in {@see StringFilter::sanitize()}
     * plus `<` and `>`, except `` ` ``.
     * 
     * If __$htmlStandard__ is _not_ passed the expected behaviour is:
     * - `<`, `>`, `"`, `'` and `&` wil be replaced with _&#38;#60;_, _&#38;#62;_,
     *   _&#38;#34;_, _&#38;#39;_ and _&#38;#38;_, regardless of `Encode_Quotes`
     *   and `Encode_Amp` flags
     * - `` ` `` will be _stripped_ if `Strip_Backtick` flag is enabled
     * 
     * If __$htmlStandard__ is passed:
     * - `<`, `>` will be replaced with _&#38;lt;_, _&#38;gt;_
     * - `&` will be replaced with _&#38;amp;_, regardless of `Encode_Amp` flag
     * - `"` and `'` will be replaced with _&#38;quot;_ and _&#38;#039;_, if
     *   `Encode_Quotes` flag is enabled, otherwise they will _not_ be encoded
     * - `` ` `` will __not__ be _stripped_, regardless of `Strip_Backtick` flag,
     *   and anyway will _not_ be encoded
     * @param bool $htmlStandard Modifies special chars encoding style in HTML way
     */
    public function sanitizeHtmlSpecialChars(bool $htmlStandard = false)
    {
        $options = $this->getOptions();
        $filterType = 0;
        if ($htmlStandard) {
            $filterType = FILTER_SANITIZE_FULL_SPECIAL_CHARS;
        } else {
            $filterType = FILTER_SANITIZE_SPECIAL_CHARS;
        }
        $sanitized = filter_var($this->data, $filterType, $options);
        return $sanitized;
    }
    
    /**
     * Add backslashes to special chars.
     * 
     * Backslashes are added to escape some special chars: `"`, `'`, `\` and `NUL`.
     * 
     * `Quotes` will _not_ be encoded, regardless of `Encode_Quotes` flag,
     * nor `&`, regardless `Encode_Amp` flag.
     * The same way, `` ` `` will _not_ be stripped, regardless of
     * `Strip_Backtick` falg.
     * 
     * @return string
     */
    public function addSlashes()
    {
        $sanitized = filter_var($this->data, FILTER_SANITIZE_MAGIC_QUOTES);
        return $sanitized;
    }
    
    /**
     * URL sanitization.
     * 
     * Removes all characters except letters, digits and `$`, `-`, `_`, `.`, `+`,
     * `!`, `*`, `'`, `(`, `)`, `,`, `{`, `}`, `|`, `\`, `^`, `~`, `[`, `]`,
     * `` ` ``, `<`, `>`, `#`, `%`, `"`, `;`, `/`, `?`, `:`,
     * `@`, `&`, `=`.
     * 
     * Flag settings are ineffective.
     * @return string
     */
    public function sanitizeUrl()
    {
        $sanitized = filter_var($this->data, FILTER_SANITIZE_URL);
        return $sanitized;
    }
    
    /**
     * URL encoding.
     * 
     * URL-encodes string.
     * 
     * If `Strip_Backtick` flag is passed, backticks are stripped. Other flags are ineffective.
     * @return string
     */
    public function urlEncode()
    {
        $encoded = filter_var($this->data, FILTER_SANITIZE_ENCODED, $this->getOptions());
        return $encoded;
    }
    
    /**
     * Implements bitwise flag setting logic.
     * 
     * @private
     * @param bool $flag
     * @param int $value
     * @return void
     */
    private function setFlag(bool $flag, int $value): void
    {
        if ($flag) {
            $this->flags |= $value;
        } else {
            $this->flags &= ~$value;
        }
    }
}
