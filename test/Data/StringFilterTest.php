<?php
namespace LeoCor\Data;

use PHPUnit\Framework\TestCase;

class StringFilterTest extends TestCase {
    /**
     * @var StringFilter
     */
    protected $object;

    protected function setUp(): void
    {
        $this->object   = new StringFilter("<html>\"&`'>prova<");
    }
    
    public function testGetOptions()
    {
        $key_flags = 'flags';
        $expectedOpt = ['flags' => FILTER_FLAG_NO_ENCODE_QUOTES];
        $this->assertSame($expectedOpt, $this->object->getOptions(), "NEQ");
        $this->object->setFlagEncodeAmp(true);
        $expectedOpt[$key_flags] |= FILTER_FLAG_ENCODE_AMP;
        $this->assertSame($expectedOpt, $this->object->getOptions(), "NEQ + AMP");
        $this->object->setFlagEncodeQuotes(true);
        $expectedOpt[$key_flags] = FILTER_FLAG_ENCODE_AMP;
        $this->assertSame($expectedOpt, $this->object->getOptions(), "AMP");
        $this->object->setFlagStripBacktick(true);
        $expectedOpt[$key_flags] |= FILTER_FLAG_STRIP_BACKTICK;
        $this->assertSame($expectedOpt, $this->object->getOptions(), "AMP + BT");
        $this->object->setFlagEncodeAmp(false);
        $expectedOpt[$key_flags] = FILTER_FLAG_STRIP_BACKTICK;
        $this->assertSame($expectedOpt, $this->object->getOptions(), "BT");
    }
    
    /**
     * @depends testGetOptions
     */
    public function testSetFlagNoEncodeQuotes()
    {
        $this->object->setFlagEncodeQuotes(true);
        $this->assertSame(
            0,
            $this->object->getOptions()['flags']
        );
        $this->object->setFlagEncodeQuotes(false);
        $this->assertSame(
            FILTER_FLAG_NO_ENCODE_QUOTES,
            $this->object->getOptions()['flags']
        );
    }
    
    /**
     * @depends testGetOptions
     */
    public function testSetFlagEncodeAmp()
    {
        $this->object->setFlagEncodeQuotes(true);
        $this->object->setFlagEncodeAmp(true);
        $this->assertSame(
            FILTER_FLAG_ENCODE_AMP,
            $this->object->getOptions()['flags']
        );
        $this->object->setFlagEncodeAmp(false);
        $this->assertSame(
            0,
            $this->object->getOptions()['flags']
        );
    }
    
    /**
     * @depends testGetOptions
     */
    public function testSetFlagStripBacktick()
    {
        $this->object->setFlagEncodeQuotes(true);
        $this->object->setFlagStripBacktick(true);
        $this->assertSame(
            FILTER_FLAG_STRIP_BACKTICK,
            $this->object->getOptions()['flags']
        );
        $this->object->setFlagStripBacktick(false);
        $this->assertSame(
            0,
            $this->object->getOptions()['flags']
        );
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
        $this->object->setFlagEncodeQuotes(true);
        $this->object->setFlagStripBacktick(true);
        $this->assertNotSame($this->object->getOptions(), $expectedOpts);
        
        $this->object->reset();
        $this->assertSame($this->object->getOptions(), $expectedOpts);
    }
    
    public function stringProvider()
    {
        $strings = [
            [
                [
                    "\"&`'>prova", "&#34;&`&#39;>prova",
                    "\"&#38;`'>prova", "\"&'>prova"
                ]
            ]
        ];
        return $strings;
    }
    
    /**
     * @depends testReset
     * @dataProvider stringProvider
     */
    public function testSanitize($expectedStrings)
    {
        $this->assertSame($expectedStrings[0], $this->object->sanitize());
        
        $this->object->setFlagEncodeQuotes(true);
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
    
    public function sanitizeHtmlProvider()
    {
        $data = [
            [
                [
                    'base'      => "&#60;html&#62;&#34;&#38;`&#39;&#62;prova&#60;",
                    'strip_bt'  => "&#60;html&#62;&#34;&#38;&#39;&#62;prova&#60;",
                    'neq'       => "&lt;html&gt;\"&amp;`'&gt;prova&lt;",
                    'full'      => "&lt;html&gt;&quot;&amp;`&#039;&gt;prova&lt;",
                ]
            ]
        ];
        return $data;
    }
    
    /**
     * @dataProvider sanitizeHtmlProvider
     */
    public function testSanitizeHtmlSpecialChars($expectedStrings)
    {
        $this->assertSame($expectedStrings['base'], $this->object->sanitizeHtmlSpecialChars());
        $this->assertSame($expectedStrings['neq'], $this->object->sanitizeHtmlSpecialChars(true));
        $this->object->setFlagEncodeQuotes(true);
        $this->assertSame($expectedStrings['base'], $this->object->sanitizeHtmlSpecialChars());
        $this->assertSame($expectedStrings['full'],  $this->object->sanitizeHtmlSpecialChars(true));
        $this->object->setFlagEncodeAmp(true);
        $this->assertSame($expectedStrings['base'], $this->object->sanitizeHtmlSpecialChars());
        $this->assertSame($expectedStrings['full'], $this->object->sanitizeHtmlSpecialChars(true));
        $this->object->setFlagStripBacktick(true);
        $this->assertSame($expectedStrings['strip_bt'], $this->object->sanitizeHtmlSpecialChars());
        $this->assertSame($expectedStrings['full'], $this->object->sanitizeHtmlSpecialChars(true));
    }
    
    public function testAddSlashes()
    {
        $this->assertSame("<html>\\\"&`\\'>prova<",  $this->object->addSlashes());
        $allChars = "\"'\\\0&`";
        $expectedStr = "\\\"\\'\\\\\\0&`";
        $strFlt = new StringFilter($allChars);
        $this->assertSame($expectedStr, $strFlt->addSlashes());
        $strFlt->setFlagEncodeQuotes(true);
        $this->assertSame($expectedStr, $strFlt->addSlashes());
        $strFlt->setFlagEncodeAmp(true);
        $this->assertSame($expectedStr, $strFlt->addSlashes());
        $strFlt->setFlagStripBacktick(true);
        $this->assertSame($expectedStr, $strFlt->addSlashes());
    }
    
    public function testSanitizeEmail()
    {
        $strFlt = new StringFilter("abc123DEF!#$%&'*+-=?^_`{|}~@.[]\"<>\\/:;,");
        $expectedStr = "abc123DEF!#$%&'*+-=?^_`{|}~@.[]";
        $this->assertSame($expectedStr, $strFlt->sanitizeEmail());
        $strFlt->setFlagEncodeQuotes(true);
        $this->assertSame($expectedStr, $strFlt->sanitizeEmail());
        $strFlt->setFlagEncodeAmp(true);
        $this->assertSame($expectedStr, $strFlt->sanitizeEmail());
        $strFlt->setFlagStripBacktick(true);
        $this->assertSame($expectedStr, $strFlt->sanitizeEmail());
    }
    
    public function testSanitizeUrl()
    {
        $strFlt = new StringFilter("abc123DEF!#$%&'*+-=?^_`{|}~@.[]\"<>\\/:;,()");
        $expectedStr = "abc123DEF!#$%&'*+-=?^_`{|}~@.[]\"<>\\/:;,()";
        $this->assertSame($expectedStr, $strFlt->sanitizeUrl());
        $strFlt->setFlagEncodeQuotes(true);
        $this->assertSame($expectedStr, $strFlt->sanitizeUrl());
        $strFlt->setFlagEncodeAmp(true);
        $this->assertSame($expectedStr, $strFlt->sanitizeUrl());
        $strFlt->setFlagStripBacktick(true);
        $this->assertSame($expectedStr, $strFlt->sanitizeUrl());
    }
    
    public function testUrlEncode()
    {
        $expected = [
            "%3Chtml%3E%22%26%60%27%3Eprova%3C",
            "%3Chtml%3E%22%26%27%3Eprova%3C"
        ];
        $this->assertSame($expected[0], $this->object->urlEncode());
        $this->object->setFlagEncodeQuotes(true);
        $this->assertSame($expected[0], $this->object->urlEncode());
        $this->object->setFlagEncodeAmp(true);
        $this->assertSame($expected[0], $this->object->urlEncode());
        $this->object->setFlagStripBacktick(true);
        $this->assertSame($expected[1], $this->object->urlEncode());
    }
    
    public function testValidateEmail()
    {
        $strFlt = new StringFilter("kjgd@ksid`.lk");
        $this->assertFalse($strFlt->validateEmail());
        $strFlt->setFlagStripBacktick(true);
        $this->assertFalse($strFlt->validateEmail());
        $strFltOk = new StringFilter("dskl@por.qa");
        $this->assertTrue($strFltOk->validateEmail());
    }
    
    public function validateHostnameProvider()
    {
        $data = [
            ["kdosd@oi.ig", [false, false, false, false]],
            ["kdosd@oi", [false, false, false, false]],
            ["kdosd#oi", [false, false, false, false]],
            ["kdosd#oi\"'`&", [false, false, false, false]],
            ["\"'`&", [false, false, false, false]],
        ];
        return $data;
    }
    
    /**
     * @dataProvider validateHostnameProvider
     */
    public function testValidateHostname($str, $expected)
    {
        $strFlt = new StringFilter($str);
        $this->assertSame($expected[0], $strFlt->validateHostname());
        $strFlt->setFlagEncodeQuotes(true);
        $this->assertSame($expected[1], $strFlt->validateHostname());
        $strFlt->setFlagEncodeAmp(true);
        $this->assertSame($expected[2], $strFlt->validateHostname());
        $strFlt->setFlagStripBacktick(true);
        $this->assertSame($expected[3], $strFlt->validateHostname());
    }
}
