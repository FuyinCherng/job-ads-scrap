<?php namespace JobApis\Jobs\Client\Tests;

use JobApis\Jobs\Client\Queries\GithubQuery;
use Mockery as m;

class GithubQueryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->query = new GithubQuery();
    }

    public function testItCanGetBaseUrl()
    {
        $this->assertEquals(
            'https://jobs.github.com/positions.json',
            $this->query->getBaseUrl()
        );
    }

    public function testItCanGetKeyword()
    {
        $keyword = uniqid();
        $this->query->set('search', $keyword);
        $this->assertEquals($keyword, $this->query->getKeyword());
    }

    public function testItCanSetAndGetDescription()
    {
        $keyword = uniqid();
        $this->query->set('description', $keyword);
        $this->assertEquals($keyword, $this->query->get('description'));
        $this->assertEquals($keyword, $this->query->get('search'));
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
            'search' => uniqid(),
            'location' => uniqid(),
            'full_time' => uniqid(),
        ];

        foreach ($attributes as $key => $value) {
            $this->query->set($key, $value);
        }

        foreach ($attributes as $key => $value) {
            $this->assertEquals($value, $this->query->get($key));
        }

        $url = $this->query->getUrl();

        $this->assertContains('search=', $url);
        $this->assertContains('location=', $url);
        $this->assertContains('full_time=', $url);
    }
}
