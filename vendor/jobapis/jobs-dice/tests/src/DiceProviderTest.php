<?php namespace JobBrander\Jobs\Client\Test;

use JobApis\Jobs\Client\Collection;
use JobApis\Jobs\Client\Job;
use JobApis\Jobs\Client\Providers\DiceProvider;
use JobApis\Jobs\Client\Queries\DiceQuery;
use Mockery as m;

class DiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->query = m::mock('JobApis\Jobs\Client\Queries\DiceQuery');

        $this->client = new DiceProvider($this->query);
    }

    public function testItCanGetDefaultResponseFields()
    {
        $fields = [
            'jobTitle',
            'company',
            'location',
            'date',
            'detailUrl',
        ];
        $this->assertEquals($fields, $this->client->getDefaultResponseFields());
    }

    public function testItCanGetListingsPath()
    {
        $this->assertEquals('resultItemList', $this->client->getListingsPath());
    }

    public function testItCanCreateJobObjectFromPayload()
    {
        $payload = $this->createJobArray();

        $results = $this->client->createJobObject($payload);

        $this->assertInstanceOf(Job::class, $results);
        $this->assertEquals($payload['jobTitle'], $results->title);
        $this->assertEquals($payload['company'], $results->company);
        $this->assertEquals($payload['location'], $results->location);
        $this->assertEquals($payload['detailUrl'], $results->url);
    }

    /**
     * Integration test for the client's getJobs() method.
     */
    public function testItCanGetJobs()
    {
        $options = [
            'text' => uniqid(),
            'areacode' => uniqid(),
            'pgcnt' => uniqid(),
        ];

        $guzzle = m::mock('GuzzleHttp\Client');

        $query = new DiceQuery($options);

        $client = new DiceProvider($query);

        $client->setClient($guzzle);

        $response = m::mock('GuzzleHttp\Message\Response');

        $jobObjects = [
            (object) $this->createJobArray(),
            (object) $this->createJobArray(),
            (object) $this->createJobArray(),
        ];

        $jobs = json_encode((object) [
            'resultItemList' => $jobObjects
        ]);

        $guzzle->shouldReceive('get')
            ->with($query->getUrl(), [])
            ->once()
            ->andReturn($response);
        $response->shouldReceive('getBody')
            ->once()
            ->andReturn($jobs);

        $results = $client->getJobs();

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(count($jobObjects), $results);
    }

    /**
     * Integration test with actual API call to the provider.
     */
    public function testItCanGetJobsFromApi()
    {
        if (!getenv('REAL_CALL')) {
            $this->markTestSkipped('REAL_CALL not set. Real API call will not be made.');
        }

        $keyword = 'engineering';

        $query = new DiceQuery([
            'text' => $keyword,
        ]);

        $client = new DiceProvider($query);

        $results = $client->getJobs();

        $this->assertInstanceOf('JobApis\Jobs\Client\Collection', $results);

        foreach($results as $job) {
            $this->assertEquals($keyword, $job->query);
        }
    }

    private function createJobArray() {
        return [
            'jobTitle' => uniqid(),
            'company' => uniqid(),
            'location' => uniqid().', '.uniqid(),
            'date' => '2015-07-'.rand(1,31),
            'detailUrl' => uniqid(),
        ];
    }
}
