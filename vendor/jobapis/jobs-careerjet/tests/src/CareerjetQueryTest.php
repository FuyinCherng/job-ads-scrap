<?php namespace JobApis\Jobs\Client\Test;

use JobApis\Jobs\Client\Queries\CareerjetQuery;
use Mockery as m;

class CareerjetQueryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->query = new CareerjetQuery();
    }

    public function testItCanGetBaseUrl()
    {
        $this->assertEquals(
            'http://public.api.careerjet.net/search',
            $this->query->getBaseUrl()
        );
    }

    public function testItCanGetKeyword()
    {
        $keyword = uniqid();
        $this->query->set('keywords', $keyword);
        $this->assertEquals($keyword, $this->query->getKeyword());
    }

    public function testItReturnsFalseIfRequiredAttributesMissing()
    {
        $this->assertFalse($this->query->isValid());
    }

    public function testItSetsDefaultAttribute()
    {
        $this->assertEquals('en_US', $this->query->get('locale_code'));
    }

    public function testItReturnsTrueIfRequiredAttributesPresent()
    {
        $this->query->set('affid', uniqid());

        $this->assertTrue($this->query->isValid());
    }

    public function testItCanAddAttributesToUrl()
    {
        $this->query->set('affid', uniqid());
        $this->query->set('keywords', uniqid());

        $url = $this->query->getUrl();

        $this->assertContains('affid=', $url);
        $this->assertContains('keywords=', $url);
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
            'keywords' => uniqid(),
            'location' => uniqid(),
            'page' => rand(1,100),
            'pagesize' => rand(1,100),
        ];

        foreach ($attributes as $key => $value) {
            $this->query->set($key, $value);
        }

        foreach ($attributes as $key => $value) {
            $this->assertEquals($value, $this->query->get($key));
        }
    }
}
