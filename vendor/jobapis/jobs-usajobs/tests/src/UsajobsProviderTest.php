<?php namespace JobApis\Jobs\Client\Test;

use JobApis\Jobs\Client\Collection;
use JobApis\Jobs\Client\Job;
use JobApis\Jobs\Client\Providers\UsajobsProvider;
use JobApis\Jobs\Client\Queries\UsajobsQuery;
use Mockery as m;

class UsajobsProviderTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->query = m::mock('JobApis\Jobs\Client\Queries\UsajobsQuery');

        $this->client = new UsajobsProvider($this->query);
    }

    public function testItCanGetDefaultResponseFields()
    {
        $fields = [
            'MatchedObjectId',
            'MatchedObjectDescriptor',
        ];
        $this->assertEquals($fields, $this->client->getDefaultResponseFields());
    }

    public function testItCanGetListingsPath()
    {
        $this->assertEquals('SearchResult.SearchResultItems', $this->client->getListingsPath());
    }

    public function testItCanCreateJobObjectFromPayload()
    {
        $payload = $this->createJobArray();

        $results = $this->client->createJobObject($payload);

        $this->assertInstanceOf(Job::class, $results);
        $this->assertEquals($payload['MatchedObjectId'], $results->sourceId);
        $this->assertEquals($payload['MatchedObjectDescriptor']['PositionTitle'], $results->title);
        $this->assertEquals($payload['MatchedObjectDescriptor']['PositionURI'], $results->url);
        $this->assertEquals(\DateTime::class, get_class($results->datePosted));
        $this->assertEquals(\DateTime::class, get_class($results->validThrough));
        $this->assertEquals(\DateTime::class, get_class($results->startDate));
        $this->assertEquals(\DateTime::class, get_class($results->endDate));
    }

    /**
     * Integration test for the client's getJobs() method.
     */
    public function testItCanGetJobs()
    {
        $options = [
            'Keyword' => uniqid(),
            'PayGradeHigh' => uniqid(),
            'LocationName' => uniqid(),
            'AuthorizationKey' => uniqid(),
        ];

        $guzzle = m::mock('GuzzleHttp\Client');

        $query = new UsajobsQuery($options);

        $client = new UsajobsProvider($query);

        $client->setClient($guzzle);

        $response = m::mock('GuzzleHttp\Message\Response');

        $jobs['SearchResult'] = [
            'SearchResultItems' => [
                $this->createJobArray(),
                $this->createJobArray(),
            ],
        ];
        $jobs = json_encode($jobs);

        $guzzle->shouldReceive('get')
            ->with($query->getUrl(), $query->getHttpMethodOptions())
            ->once()
            ->andReturn($response);
        $response->shouldReceive('getBody')
            ->once()
            ->andReturn($jobs);

        $results = $client->getJobs();

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(2, $results);
    }

    /**
     * Integration test with actual API call to the provider.
     */
    public function testItCanGetJobsFromApi()
    {
        if (!getenv('AUTHORIZATION_KEY')) {
            $this->markTestSkipped('AUTHORIZATION_KEY not set. Real API call will not be made.');
        }

        $keyword = 'engineering';

        $query = new UsajobsQuery([
            'Keyword' => $keyword,
            'AuthorizationKey' => getenv('AUTHORIZATION_KEY'),
        ]);

        $client = new UsajobsProvider($query);

        $results = $client->getJobs();

        $this->assertInstanceOf(Collection::class, $results);

        foreach($results as $job) {
            $this->assertInstanceOf(Job::class, $results);
            $this->assertEquals($keyword, $job->query);
        }
    }

    private function createJobArray()
    {
        return json_decode($this->getJsonJob(), true);
    }

    private function getJsonJob()
    {
        return '
        {
            "MatchedObjectId": "3244540200",
            "MatchedObjectDescriptor": {
                "PositionID": "NW52210-12-1397265LQ078990D",
                "PositionTitle": "INFORMATION TECHNOLOGY SPECIALIST (APPLICATION SOFTWARE)",
                "PositionURI": "https://www.uat.usajobs.gov:443/GetJob/ViewDetails/3244540200",
                "ApplyURI": [
                    "https://www.uat.usajobs.gov:443/GetJob/ViewDetails/3244540200?PostingChannelID=RESTAPI"
                ],
                "PositionLocation": [
                    {
                        "LocationName": "Kauai Island, Hawaii",
                        "CountryCode": "United States",
                        "CountrySubDivisionCode": "Hawaii",
                        "CityName": "Kauai Island, Hawaii",
                        "Longitude": -159.546158,
                        "Latitude": 22.0529766
                    }
                ],
                "OrganizationName": "U.S. Pacific Fleet, Commander in Chief",
                "DepartmentName": "Department of the Navy",
                "JobCategory": [
                    {
                        "Name": "Information Technology Management",
                        "Code": "2210"
                    }
                ],
                "JobGrade": [
                    {
                        "Code": "GS"
                    }
                ],
                "PositionSchedule": [
                    {
                        "Name": "Full Time",
                        "Code": "1"
                    }
                ],
                "PositionOfferingType": [
                    {
                        "Name": "Permanent",
                        "Code": "15317"
                    }
                ],
                "QualificationSummary": "In order to qualify for this position, your resume must provide sufficient experience and/or education, knowledge, skills, and abilities, to perform the duties of the specific position for which you are being considered. Your resume is the key means we have for evaluating your skills, knowledge, and abilities, as they relate to this position. Therefore, we encourage you to be clear and specific when describing your experience. Your resume must include the following:...",
                "PositionRemuneration": [
                    {
                        "MinimumRange": "71637",
                        "MaximumRange": "93133",
                        "RateIntervalCode": "Per Year"
                    }
                ],
                "PositionStartDate": "2015-05-01T00:00:00.0000000Z",
                "PositionEndDate": "2015-06-30T00:00:00.0000000Z",
                "PublicationStartDate": "2015-05-01T00:00:00.0000000Z",
                "ApplicationCloseDate": "2015-06-30T00:00:00.0000000Z",
                "PositionFormattedDescription": [
                    {
                        "Content": "position may include, but are not limited to: Developing guidance and/or procedures for implementing organizational information technology software application standards. Providing technical data processing and/or software support for pilot testing, evaluation and/or certification of acceptability",
                        "Label": "Dynamic Teaser",
                        "LabelDescription": "Hit highlighting for keyword searches."
                    }
                ],
                "UserArea": {
                "Details": {
                    "JobSummary": "Salary will be increased by the applicable cost-of-living allowance (COLA), which is subject to change without notice. This position is located in the Range Systems Division of the Range Operations Department, Pacific Missile Range Facility (PMRF) Hawaii.",
                        "WhoMayApply": {
                        "Name": "United States Citizens",
                            "Code": "15514"
                        },
                        "LowGrade": "12",
                        "HighGrade": "12"
                    },
                    "IsRadialSearch": false
                }
            }
        }';
    }
}
