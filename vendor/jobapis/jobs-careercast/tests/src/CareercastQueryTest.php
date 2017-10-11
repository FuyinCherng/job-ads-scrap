<?php namespace JobApis\Jobs\Client\Test;

use JobApis\Jobs\Client\Queries\CareercastQuery;
use Mockery as m;

class CareercastQueryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->query = new CareercastQuery();
    }

    public function testItCanGetBaseUrl()
    {
        $this->assertEquals(
            'http://www.careercast.com/jobs/results/keyword/',
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
        $keyword = uniqid();

        $this->query->set('keyword', $keyword);
        $this->query->set('rows', rand(1,10));

        $url = $this->query->getUrl();

        $this->assertContains('keyword/'.$keyword, $url);
        $this->assertContains('rows=', $url);
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
            'rows' => uniqid(),
            'page' => uniqid(),
            'company' => uniqid(),
        ];

        foreach ($attributes as $key => $value) {
            $this->query->set($key, $value);
        }

        foreach ($attributes as $key => $value) {
            $this->assertEquals($value, $this->query->get($key));
        }
    }
}
