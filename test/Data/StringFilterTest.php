<?php
namespace LeoCor\Data;

use PHPUnit\Framework\TestCase;

class StringFilterTest extends TestCase {
    /**
     * @var StringFilter
     */
    protected $object;
    /**
     *
     * @var StringFilter
     */
    protected $objectHtml;

    protected function setUp(): void
    {
        $this->object       = new StringFilter("'Prova\"&`");
        $this->objectHtml   = new StringFilter("<html>\"&`'");
    }
    
    public function stringProvider()
    {
        $strings = [
            [
                [
                    "'Prova\"&`", "&#39;Prova&#34;&`", "'Prova\"&#38;`",
                    "'Prova\"&"
                ]
            ]
        ];
        return $strings;
    }
    
    public function stringHtmlProvider()
    {
        $strings = [
            [
                [
                    "'Prova\"&`", "&#39;Prova&#34;&`", "'Prova\"&#38;`",
                    "'Prova\"&"
                ]
            ]
        ];
        return $strings;
    }
    
    public function testGetOptions()
    {
        $flags = ['flags' => FILTER_FLAG_NO_ENCODE_QUOTES];
        $this->assertSame($this->object->getOptions(), $flags);
    }
    
    /**
     * @depends testGetOptions
     */
    public function testSetFlagNoEncodeQuotes()
    {
        $this->object->setFlagNoEncodeQuotes(false);
        $flags = ['flags' => 0];
        $this->assertSame($this->object->getOptions(), $flags);
    }
    
    /**
     * @depends testGetOptions
     */
    public function testSetFlagEncodeAmp()
    {
        $flags = ['flags' => FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_ENCODE_AMP];
        $this->object->setFlagEncodeAmp(true);
        $this->assertSame($this->object->getOptions(), $flags);
    }
    
    /**
     * @depends testGetOptions
     */
    public function testSetFlagStripBacktick()
    {
        $flags = ['flags' => FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_BACKTICK];
        $this->object->setFlagStripBacktick(true);
        $this->assertSame($this->object->getOptions(), $flags);
    }
    
    /**
     * @depends testSetFlagEncodeAmp
     * @depends testSetFlagNoEncodeQuotes
     * @depends testSetFlagStripBacktick
     */
    public function testReset()
    {
        $expectedOpts = ['flags' => FILTER_FLAG_NO_ENCODE_QUOTES];
        
        $this->object->setFlagEncodeAmp(true);
        $this->object->setFlagNoEncodeQuotes(false);
        $this->object->setFlagStripBacktick(true);
        $this->assertNotSame($this->object->getOptions(), $expectedOpts);
        
        $this->object->reset();
        $this->assertSame($this->object->getOptions(), $expectedOpts);
    }
    
    /**
     * @depends testReset
     * @dataProvider stringProvider
     */
    public function testSanitize($expectedStrings)
    {
        $this->assertSame(
            $this->object->getOptions(),
            ['flags' => FILTER_FLAG_NO_ENCODE_QUOTES]
        );
        $this->assertSame($expectedStrings[0], $this->object->sanitize());
        
        $this->object->setFlagNoEncodeQuotes(false);
        $this->assertSame($expectedStrings[1], $this->object->sanitize());
        
        $this->object->reset();
        $this->object->setFlagEncodeAmp(true);
        $this->assertSame($expectedStrings[2], $this->object->sanitize());
        
        $this->object->reset();
        $this->object->setFlagStripBacktick(true);
        $this->assertSame($expectedStrings[3], $this->object->sanitize());
    }

    public function testValidate()
    {
        $this->assertTrue($this->object->validate());
    }
    
    public function testSanitizeSpecialCharsToHtml()
    {
        $sanitized = $this->objectHtml->sanitizeSpecialCharsToHtml();
        $this->assertSame($expected, $sanitized);
    }
}
