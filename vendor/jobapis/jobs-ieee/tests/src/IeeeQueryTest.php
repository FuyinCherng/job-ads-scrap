<?php namespace JobApis\Jobs\Client\Test;

use JobApis\Jobs\Client\Queries\IeeeQuery;
use Mockery as m;

class IeeeQueryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->query = new IeeeQuery();
    }

    public function testItCanGetBaseUrl()
    {
        $this->assertEquals(
            'http://jobs.ieee.org/jobs/results',
            $this->query->getBaseUrl()
        );
    }

    public function testItCanGetKeyword()
    {
        $keyword = uniqid();
        $this->query->set('keyword', $keyword);
        $this->assertEquals($keyword, $this->query->getKeyword());
    }

    public function testItReturnsFalseIfRequiredAttributesMissing()
    {
        $this->assertFalse($this->query->isValid());
    }

    public function testItReturnsTrueIfRequiredAttributesPresent()
    {
        $this->query->set('keyword', uniqid());

        $this->assertTrue($this->query->isValid());
    }

    public function testItCanAddAttributesToUrl()
    {
        $this->query->set('keyword', uniqid());
        $this->query->set('location', uniqid());

        $url = $this->query->getUrl();

        $this->assertContains('keyword/', $url);
        $this->assertContains('location=', $url);
    }

    /**
     * @expectedException OutOfRangeException
     */
    public function testItThrowsExceptionWhenSettingInvalidAttribute()
    {
        $this->query->set(uniqid(), uniqid());
    }

    /**
     * @expectedException OutOfRangeException
     */
    public function testItThrowsExceptionWhenGettingInvalidAttribute()
    {
        $this->query->get(uniqid());
    }

    public function testItSetsAndGetsValidAttributes()
    {
        $attributes = [
            'keyword' => uniqid(),
            'location' => uniqid(),
            'radius' => rand(1,100),
        ];

        foreach ($attributes as $key => $value) {
            $this->query->set($key, $value);
        }

        foreach ($attributes as $key => $value) {
            $this->assertEquals($value, $this->query->get($key));
        }
    }
}
