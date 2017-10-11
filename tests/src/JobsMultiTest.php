<?php namespace JobApis\Jobs\Client\Tests;

use JobApis\Jobs\Client\Collection;
use Mockery as m;
use JobApis\Jobs\Client\JobsMulti;

class JobsMultiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JobsMulti
     */
    protected $client;

    public function setUp()
    {
        $this->providers = [
            'Careerbuilder' => [
                'DeveloperKey' => uniqid(),
            ],
            'Careercast' => [],
            'Dice' => [],
            'Github' => [],
            'Govt' => [],
            'Ieee' => [],
            'Indeed' => [
                'publisher' => uniqid(),
            ],
            'Jobinventory' => [],
            'J2c' => [
                'id' => uniqid(),
                'pass' => uniqid(),
            ],
            'Juju' => [
                'partnerid' => uniqid(),
            ],
            'Monster' => [],
            'Stackoverflow' => [],
            'Usajobs' => [
                'AuthorizationKey' => uniqid(),
            ],
            'Ziprecruiter' => [
                'api_key' => uniqid(),
            ],
        ];
        $this->client = new JobsMulti($this->providers);
    }

    public function testItCanSetOptions()
    {
        $options = [
            'maxAge' => rand(1, 50),
            'maxResults' => rand(1, 100),
            'orderBy' => uniqid(),
            'order' => 'asc',
        ];

        $this->client->setOptions($options);

        $this->assertEquals(
            $options['maxAge'],
            $this->getProtectedProperty($this->client, 'maxAge')
        );
        $this->assertEquals(
            $options['maxResults'],
            $this->getProtectedProperty($this->client, 'maxResults')
        );
        $this->assertEquals(
            $options['orderBy'],
            $this->getProtectedProperty($this->client, 'orderBy')
        );
        $this->assertEquals(
            $options['order'],
            $this->getProtectedProperty($this->client, 'order')
        );
    }

    public function testItCanSetProviders()
    {
        $providers = [
            uniqid() => uniqid(),
        ];

        $this->client->setProviders($providers);

        $results = $this->getProtectedProperty($this->client, 'providers');

        $this->assertEquals($providers, $results);
    }

    public function testItCanSetKeyword()
    {
        $keyword = uniqid();

        $this->client->setKeyword($keyword);

        $result = $this->getProtectedProperty($this->client, 'keyword');

        $this->assertEquals($keyword, $result);
    }

    public function testItCanSetLocation()
    {
        $location = uniqid().', ST';

        $this->client->setLocation($location);

        $result = $this->getProtectedProperty($this->client, 'location');

        $this->assertEquals($location, $result);
    }

    public function testItCanSetPageDetails()
    {
        $pageNumber = rand(1, 100);
        $perPage = rand(1, 100);

        $this->client->setPage($pageNumber, $perPage);

        $resultPageNumber = $this->getProtectedProperty($this->client, 'pageNumber');
        $resultPerPage = $this->getProtectedProperty($this->client, 'perPage');

        $this->assertEquals($pageNumber, $resultPageNumber);
        $this->assertEquals($perPage, $resultPerPage);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testItCannotSetLocationOnProvidersWhenInvalid()
    {
        $location = uniqid().' '.uniqid();
        $this->client->setLocation($location);
    }

    public function testItCannotGetJobsByProviderWhenExceptionThrown()
    {
        $result = $this->client->getJobsByProvider(uniqid());

        $this->assertEquals(Collection::class, get_class($result));
        $this->assertNotNull($result->getErrors());
    }

    public function testItCanGetResultsFromSingleApi()
    {
        $providers = [
            'Dice',
            'Github',
            'Govt',
            'Ieee',
            'Jobinventory',
            'Monster',
            'Stackoverflow',
        ];

        $keyword = 'engineer';
        $location = 'New York, NY';
        $pageNumber = 1;
        $perPage = 10;

        $provider = $providers[array_rand($providers)];

        $client = new JobsMulti([$provider => []]);

        $client->setKeyword($keyword)
            ->setLocation($location)
            ->setPage($pageNumber, $perPage);

        $results = $client->getJobsByProvider($provider);

        $this->assertInstanceOf('JobApis\Jobs\Client\Collection', $results);
        foreach($results as $job) {
            $this->assertEquals($keyword, $job->query);
        }
    }

    public function testItCanGetResultsFromSingleApiWithOptions()
    {
        $providers = [
            'Dice',
            'Github',
            'Govt',
            'Ieee',
            'Jobinventory',
            'Monster',
            'Stackoverflow',
        ];

        $keyword = 'engineer';
        $options = [
            'maxAge' => rand(1, 180),
            'maxResults' => rand(1, 25),
            'orderBy' => 'datePosted',
            'order' => 'desc',
        ];

        $provider = $providers[array_rand($providers)];

        $client = new JobsMulti([$provider => []]);

        $results = $client->setKeyword($keyword)
            ->getJobsByProvider($provider, $options);

        $this->assertInstanceOf('JobApis\Jobs\Client\Collection', $results);
        $this->assertLessThanOrEqual($options['maxResults'], $results->count());
        $previousJob = null;
        foreach($results as $job) {
            $this->assertEquals($keyword, $job->query);
            if ($previousJob) {
                $this->assertLessThanOrEqual($job->datePosted, $previousJob->datePosted);
            }
            $previousJob = $job;
        }
    }

    public function testItCanGetAllResultsFromApis()
    {
        $providers = [
            'Dice' => [],
            'Github' => [],
            'Govt' => [],
            'Ieee' => [],
            'Jobinventory' => [],
            'Monster' => [],
            'Stackoverflow' => [],
        ];
        $client = new JobsMulti($providers);
        $keyword = 'engineering';

        $client->setKeyword($keyword)
            ->setLocation('Chicago, IL')
            ->setPage(1, 2);

        $results = $client->getAllJobs();

        $this->assertInstanceOf('JobApis\Jobs\Client\Collection', $results);
        foreach($results as $job) {
            $this->assertEquals($keyword, $job->query);
        }
    }

    private function getProtectedProperty($object, $property = null)
    {
        $class = new \ReflectionClass(get_class($object));
        $property = $class->getProperty($property);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    private function getRandomProvider()
    {
        return array_rand($this->providers);
    }
}
