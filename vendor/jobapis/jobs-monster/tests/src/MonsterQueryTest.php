<?php namespace JobApis\Jobs\Client\Test;

use JobApis\Jobs\Client\Queries\MonsterQuery;
use Mockery as m;

class MonsterQueryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->query = new MonsterQuery();
    }

    public function testItCanGetBaseUrl()
    {
        $this->assertEquals(
            'http://rss.jobsearch.monster.com/rssquery.ashx',
            $this->query->getBaseUrl()
        );
    }

    public function testItCanGetKeyword()
    {
        $keyword = uniqid();
        $this->query->set('q', $keyword);
        $this->assertEquals($keyword, $this->query->getKeyword());
    }

    public function testItReturnsFalseIfRequiredAttributesMissing()
    {
        $this->assertFalse($this->query->isValid());
    }

    public function testItReturnsTrueIfRequiredAttributesPresent()
    {
        $this->query->set('q', uniqid());

        $this->assertTrue($this->query->isValid());
    }

    public function testItCanAddAttributesToUrl()
    {
        $this->query->set('q', uniqid());
        $this->query->set('where', uniqid());

        $url = $this->query->getUrl();

        $this->assertContains('q=', $url);
        $this->assertContains('where=', $url);
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
            'q' => uniqid(),
            'where' => uniqid(),
            'page' => rand(1,100),
        ];

        foreach ($attributes as $key => $value) {
            $this->query->set($key, $value);
        }

        foreach ($attributes as $key => $value) {
            $this->assertEquals($value, $this->query->get($key));
        }
    }
}
