<?php namespace JobApis\Jobs\Client\Test;

use JobApis\Jobs\Client\Queries\UsajobsQuery;
use Mockery as m;

class UsajobsQueryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->query = new UsajobsQuery();
    }

    public function testItCanGetBaseUrl()
    {
        $this->assertEquals(
            'https://data.usajobs.gov/api/search',
            $this->query->getBaseUrl()
        );
    }

    public function testItCanGetKeyword()
    {
        $keyword = uniqid();
        $this->query->set('Keyword', $keyword);
        $this->assertEquals($keyword, $this->query->getKeyword());
    }

    public function testItCanGetHttpMethodOptions()
    {
        $this->assertTrue(array_key_exists('headers', $this->query->getHttpMethodOptions()));
    }

    public function testItReturnsFalseIfRequiredAttributesMissing()
    {
        $this->assertFalse($this->query->isValid());
    }

    public function testItReturnsTrueIfRequiredAttributesPresent()
    {
        $this->query->set('AuthorizationKey', uniqid());

        $this->assertTrue($this->query->isValid());
    }

    public function testItCanAddAttributesToUrl()
    {
        $keyword = uniqid();

        $this->query->set('Keyword', $keyword);
        $this->query->set('SecurityClearanceRequired', 'Secret');

        $url = $this->query->getUrl();

        $this->assertContains('Keyword=', $url);
        $this->assertContains('SecurityClearanceRequired=', $url);
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
            'Keyword' => uniqid(),
            'PositionTitle' => uniqid(),
            'PayGradeHigh' => uniqid(),
            'TravelPercentage' => uniqid(),
        ];

        foreach ($attributes as $key => $value) {
            $this->query->set($key, $value);
        }

        foreach ($attributes as $key => $value) {
            $this->assertEquals($value, $this->query->get($key));
        }
    }
}
