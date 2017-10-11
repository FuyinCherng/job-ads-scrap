<?php namespace JobApis\Jobs\Client\Test;

use JobApis\Jobs\Client\Queries\DiceQuery;
use Mockery as m;

class DiceQueryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->query = new DiceQuery();
    }

    public function testItCanGetBaseUrl()
    {
        $this->assertEquals(
            'http://service.dice.com/api/rest/jobsearch/v1/simple.json',
            $this->query->getBaseUrl()
        );
    }

    public function testItCanGetKeyword()
    {
        $keyword = uniqid();
        $this->query->set('text', $keyword);
        $this->assertEquals($keyword, $this->query->getKeyword());
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
            'text' => uniqid(),
            'country' => uniqid(),
            'diceid' => uniqid(),
            'sort' => uniqid(),
        ];

        foreach ($attributes as $key => $value) {
            $this->query->set($key, $value);
        }

        foreach ($attributes as $key => $value) {
            $this->assertEquals($value, $this->query->get($key));
        }

        $url = $this->query->getUrl();

        $this->assertContains('text=', $url);
        $this->assertContains('country=', $url);
        $this->assertContains('diceid=', $url);
        $this->assertContains('sort=', $url);
    }
}
