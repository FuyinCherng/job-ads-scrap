<?php namespace JobApis\Jobs\Client\Tests;

use DateTime;
use JobApis\Jobs\Client\Collection;
use JobApis\Jobs\Client\Job;
use JobApis\Jobs\Client\Providers\GithubProvider;
use JobApis\Jobs\Client\Queries\GithubQuery;
use Mockery as m;

class GithubProviderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->query = m::mock('JobApis\Jobs\Client\Queries\GithubQuery');

        $this->client = new GithubProvider($this->query);
    }

    public function testItCanGetDefaultResponseFields()
    {
        $fields = [
            'company',
            'company_logo',
            'company_url',
            'description',
            'created_at',
            'title',
            'id',
            'url',
            'type',
        ];
        $this->assertEquals($fields, $this->client->getDefaultResponseFields());
    }

    public function testItCanGetListingsPath()
    {
        $this->assertEmpty($this->client->getListingsPath());
    }

    public function testItCanGetSource()
    {
        $this->assertEquals('Github', $this->client->getSource());
    }

    public function testItCanCreateJobObjectFromPayload()
    {
        $json = $this->getListingJson();
        $payload = json_decode($json, true);
        $job = $this->client->createJobObject($payload);
        $this->assertInstanceOf(Job::class, $job);
        $this->assertEquals($payload['id'], $job->getSourceId());
        $this->assertEquals($payload['title'], $job->getName());
        $this->assertEquals($payload['description'], $job->getDescription());
        $this->assertEquals($payload['location'], $job->getLocation());
        $this->assertEquals($payload['type'], $job->getWorkHours());
        $this->assertEquals($payload['url'], $job->getUrl());
        $this->assertEquals(new DateTime(date('Y-m-d H:i:s', strtotime($payload['created_at']))), $job->getDatePosted());
        $this->assertEquals($payload['company'], $job->getCompanyName());
        $this->assertEquals($payload['company_logo'], $job->getCompanyLogo());
        $this->assertEquals($payload['company_url'], $job->getCompanyUrl());
        $this->assertContains($job->getJobLocation()->getAddress()->getAddressLocality(), $payload['location']);
    }

    // Integration test for the client's getJobs() method.
    public function testItCanGetJobs()
    {
        $options = [
            'search' => uniqid(),
            'location' => uniqid(),
            'page' => rand(1,10),
        ];

        $guzzle = m::mock('GuzzleHttp\Client');

        $query = new GithubQuery($options);

        $client = new GithubProvider($query);

        $client->setClient($guzzle);

        $response = m::mock('GuzzleHttp\Message\Response');

        $jobObjects = [
            json_decode($this->getListingJson()),
            json_decode($this->getListingJson()),
            json_decode($this->getListingJson()),
        ];

        $jobs = json_encode($jobObjects);

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

    // Integration test with actual API call to the provider.
    public function testItCanGetJobsFromApi()
    {
        if (!getenv('REAL_CALL')) {
            $this->markTestSkipped('REAL_CALL not set. Real API call will not be made.');
        }

        $keyword = 'engineering';

        $query = new GithubQuery([
            'search' => $keyword,
        ]);

        $client = new GithubProvider($query);

        $results = $client->getJobs();

        $this->assertInstanceOf('JobApis\Jobs\Client\Collection', $results);

        foreach($results as $job) {
            $this->assertEquals($keyword, $job->query);
        }
    }

    protected function getListingJson()
    {
        return '{
            "id": "6df24250-3fa0-11e5-86b5-f121d4f70ffa",
            "created_at": "Mon Aug 10 20:44:37 UTC 2015",
            "title": "Python / Full Stack engineer - Innovative Digital Health company with top talent and investors",
            "location": "San Francisco",
            "type": "Full Time",
            "description": "<h1>Python Full Stack Software Engineer</h1>\n\n<p>Virta is a science-based digital health company that solves the #1 health challenge of our generation. Virta was founded by successful second time tech entrepreneurs, scientists and physicians and we are backed by some of the Silicon Valley’s most prominent individual and institutional investors.  Our small, crazy-smart team is based in San Francisco. </p>\n\n<p>We are looking for a talented full-stack engineer to join our top-notch mission-driven team.  If you meet the requirements below, we would love to tell you more about Virta in person.</p>\n\n<h3>Minimum Requirements:</h3>\n\n<ul>\n<li>4+ years of experience shipping full-stack production code in high quality environments </li>\n<li>Well versed in python, javascript, and their popular frameworks and toolsets</li>\n<li>Has great attention to detail, carefully crafts both tests and code </li>\n<li>Has designed and built scalable APIs</li>\n<li>Loves learning new technologies</li>\n</ul>\n\n<h3>Alignment with our Virta culture:</h3>\n\n<ul>\n<li>Demonstrated passion towards our purpose improving the health of 100s of millions of people</li>\n<li>Experienced and thrives in a chaotic, fast-growing startup environment</li>\n<li>Demonstrated resourcefulness and have completed ambitious projects outside of work / school</li>\n<li>High intellectual horsepower - able to keep up with the rest of the Virta crew.  :)</li>\n</ul>\n\n<h3>Responsibilities:</h3>\n\n<ul>\n<li>Code and ship complex new features across the full software stack at high velocity with high quality</li>\n<li>Troubleshoot debug, and fix issues in production and non-production environments</li>\n<li>Coach and mentor peer engineers to become great developers and recommend best practices and tools.</li>\n<li>Help out with DevOps and IT tasks as necessary</li>\n</ul>\n\n<h3>Within your first 90 days at Virta, we expect you will do the following:</h3>\n\n<ul>\n<li>Code and ship at least one new complex feature that involves both front and back end code with full test coverage </li>\n<li>Teach and inspire other engineering team members through knowledge sharing, pair programming, and/or giving feedback during code reviews</li>\n<li>Propose and implement one or more process improvements to make our engineering team even better</li>\n</ul>\n\n<h3>If this sounds like the kind of work you would love to be doing, reach out to us and let’s chat about how we might combine forces!</h3>\n",
            "how_to_apply": "<p>Contact us at <a href=\"mailto:jobs@virtahealth.com\">jobs@virtahealth.com</a></p>\n",
            "company": "Virta Health",
            "company_url": "http://www.virtahealth.com/",
            "company_logo": "http://github-jobs.s3.amazonaws.com/181e1c1e-3fa0-11e5-8561-11e91b7e1dfb.png",
            "url": "http://jobs.github.com/positions/6df24250-3fa0-11e5-86b5-f121d4f70ffa"
        }';
    }
}
