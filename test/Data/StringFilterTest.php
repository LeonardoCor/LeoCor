<?php
namespace LeoCor\Data;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2019-04-01 at 09:45:58.
 */
class StringFilterTest extends \PHPUnit\Framework\TestCase {
    /**
     * @var StringFilter
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->object = new StringFilter("'Prova\"");
    }

    /**
     * @covers LeoCor\Data\StringFilter::getOptions
     * @covers LeoCor\Data\StringFilter::noEncodeQuotes
     * @covers LeoCor\Data\StringFilter::reset
     */
    public function testGetOptions()
    {
        $this->assertSame(
            $this->object->getOptions(),
            ['flags' => FILTER_FLAG_NO_ENCODE_QUOTES]
        );
        
        $this->object->noEncodeQuotes(false);
        $this->assertSame(
            $this->object->getOptions(),
            ['flags' => 0]
        );
        
        $this->object->reset();
        $this->assertSame(
            $this->object->getOptions(),
            ['flags' => FILTER_FLAG_NO_ENCODE_QUOTES]
        );
    }

    /**
     * @covers LeoCor\Data\StringFilter::validate
     */
    public function testValidate()
    {
        $this->assertTrue($this->object->validate());
    }

    /**
     * @covers LeoCor\Data\StringFilter::sanitize
     */
    public function testSanitize()
    {
        $this->assertSame(
            $this->object->sanitize(),
            "'Prova\""
        );
        
        $this->object->noEncodeQuotes(false);
        $this->assertSame(
            $this->object->sanitize(),
            "&#39;Prova&#34;"
        );
    }
}
