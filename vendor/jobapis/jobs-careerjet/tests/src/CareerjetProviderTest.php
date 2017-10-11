<?php namespace JobApis\Jobs\Client\Providers\Test;

use JobApis\Jobs\Client\Collection;
use JobApis\Jobs\Client\Job;
use JobApis\Jobs\Client\Providers\CareerjetProvider;
use JobApis\Jobs\Client\Queries\CareerjetQuery;
use Mockery as m;

class CareerjetProviderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->query = m::mock('JobApis\Jobs\Client\Queries\CareerjetQuery');

        $this->client = new CareerjetProvider($this->query);
    }

    public function testItCanGetDefaultResponseFields()
    {
        $fields = [
            'url',
            'title',
            'locations',
            'company',
            'salary',
            'date',
            'description',
            'site',
        ];
        $this->assertEquals($fields, $this->client->getDefaultResponseFields());
    }

    public function testItCanGetListingsPath()
    {
        $this->assertEquals('jobs', $this->client->getListingsPath());
    }

    public function testItCanCreateJobObjectFromPayload()
    {
        $payload = $this->createJobArray();

        $results = $this->client->createJobObject($payload);

        $this->assertInstanceOf(Job::class, $results);
        $this->assertEquals($payload['title'], $results->getTitle());
        $this->assertEquals($payload['title'], $results->getName());
        $this->assertEquals($payload['description'], $results->getDescription());
        $this->assertEquals($payload['company'], $results->getCompanyName());
        $this->assertEquals($payload['url'], $results->getUrl());
    }

    public function testItCanGetJobs()
    {
        $options = [
            'keywords' => uniqid(),
            'location' => uniqid(),
            'affid' => uniqid(),
        ];

        $guzzle = m::mock('GuzzleHttp\Client');

        $query = new CareerjetQuery($options);

        $client = new CareerjetProvider($query);

        $client->setClient($guzzle);

        $response = m::mock('GuzzleHttp\Message\Response');

        $jobs = ['jobs' => [
                $this->createJobArray(),
                $this->createJobArray(),
            ],
        ];

        $guzzle->shouldReceive('get')
            ->with($query->getUrl(), [])
            ->once()
            ->andReturn($response);
        $response->shouldReceive('getBody')
            ->once()
            ->andReturn(json_encode($jobs));

        $results = $client->getJobs();

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(count($jobs['jobs']), $results);
    }

    /**
     * Integration test with actual API call to the provider.
     */
    public function testItCanGetJobsFromApi()
    {
        if (!getenv('AFFILIATE_ID')) {
            $this->markTestSkipped('API_KEY not set. Real API call will not be made.');
        }

        $keyword = 'engineering';

        $query = new CareerjetQuery([
            'keywords' => $keyword,
            'affid' => getenv('AFFILIATE_ID'),
        ]);

        $client = new CareerjetProvider($query);

        $results = $client->getJobs();

        $this->assertInstanceOf('JobApis\Jobs\Client\Collection', $results);

        foreach($results as $job) {
            $this->assertEquals($keyword, $job->query);
        }
    }

    private function createJobArray() {
        return [
            'url' => uniqid(),
            'title' => uniqid(),
            'locations' => uniqid(),
            'company' => uniqid(),
            'salary' => uniqid(),
            'date' => '2015-'.rand(1,12).'-'.rand(1,31),
            'description' => uniqid(),
            'site' => uniqid(),
        ];
    }
}
