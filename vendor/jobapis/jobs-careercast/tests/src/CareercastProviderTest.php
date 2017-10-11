<?php namespace JobApis\Jobs\Client\Test;

use JobApis\Jobs\Client\Collection;
use JobApis\Jobs\Client\Job;
use JobApis\Jobs\Client\Providers\CareercastProvider;
use JobApis\Jobs\Client\Queries\CareercastQuery;
use Mockery as m;

class CareercastProviderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->query = m::mock('JobApis\Jobs\Client\Queries\CareercastQuery');

        $this->client = new CareercastProvider($this->query);
    }

    public function testItCanGetDefaultResponseFields()
    {
        $fields = [
            'Description',
            'JobTitle',
            'Url',
            'Id',
            'PostDate',
            'ExpireDate',
            'Requirements',
            'SalaryMax',
            'SalaryMin',
            'SalaryMin',
            'CategoryDisplay',
            'WorkStatusDisplay',
        ];
        $this->assertEquals($fields, $this->client->getDefaultResponseFields());
    }

    public function testItCanGetListingsPath()
    {
        $this->assertEquals('Jobs', $this->client->getListingsPath());
    }

    public function testItCanCreateJobObjectFromPayload()
    {
        $payload = $this->createJobArray();

        $results = $this->client->createJobObject($payload);

        $this->assertInstanceOf(Job::class, $results);
        $this->assertEquals($payload['JobTitle'], $results->title);
        $this->assertEquals($payload['Description'], $results->description);
        $this->assertEquals($payload['Url'], $results->url);
        $this->assertEquals(\DateTime::class, get_class($results->datePosted));
        $this->assertEquals(\DateTime::class, get_class($results->validThrough));
    }

    /**
     * Integration test for the client's getJobs() method.
     */
    public function testItCanGetJobs()
    {
        $options = [
            'keyword' => uniqid(),
            'location' => uniqid(),
            'rows' => uniqid(),
        ];

        $guzzle = m::mock('GuzzleHttp\Client');

        $query = new CareercastQuery($options);

        $client = new CareercastProvider($query);

        $client->setClient($guzzle);

        $response = m::mock('GuzzleHttp\Message\Response');

        $jobs = json_encode(['Jobs' => [
            0 => $this->createJobArray(),
            1 => $this->createJobArray(),
            2 => $this->createJobArray(),
        ]]);

        $guzzle->shouldReceive('get')
            ->with($query->getUrl(), [])
            ->once()
            ->andReturn($response);
        $response->shouldReceive('getBody')
            ->once()
            ->andReturn($jobs);

        $results = $client->getJobs();

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(3, $results);
    }

    /**
     * Integration test with actual API call to the provider.
     */
    public function testItCanGetJobsFromApi()
    {
        if (!getenv('REAL_CALL')) {
            $this->markTestSkipped('REAL_CALL not set. Real API call will not be made.');
        }

        $keyword = 'sales';

        $query = new CareercastQuery([
            'keyword' => $keyword,
        ]);

        $client = new CareercastProvider($query);

        $results = $client->getJobs();

        $this->assertInstanceOf('JobApis\Jobs\Client\Collection', $results);

        foreach($results as $job) {
            $this->assertEquals($keyword, $job->query);
        }
    }

    private function createJobArray() {
        return json_decode('{
          "NormalizedJobTitle": "Automation Engineer",
          "AdId": "57d10f1f7d6210",
          "ApplyCity": "",
          "ApplyCountry": "",
          "ApplyEmail": "",
          "ApplyFax": "",
          "ApplyName": "",
          "ApplyPhone": "",
          "ApplyState": "",
          "ApplyUrl": "https://my.jobs/1f46f5d0f4af4973aff849f085bfb100105",
          "ApplyZip": "",
          "City": "Chicago",
          "CityDisplay": "Chicago",
          "ClientId": "",
          "CompanyId": "",
          "Company": "Volt",
          "CompanyProfileUrl": "",
          "Country": "United States",
          "Description": "Volt has been serving some of the nation\'s strongest companies for over 60 years. We have a talented and upbeat staffing team focused on the quality of your career. As a Volt employee, you can expect the highest level of on-site support. We have a long-standing tradition of developing lasting and mutually beneficial relationships with our employees. We are a Six Sigma company that also offers many direct hire, full-time positions.Volt Workforce Solutions has an opportunity for you to become part of a prestigious team of professionals.Our client, located in Chicago, IL, is looking for a Senior Automation Engineer. This position can also possibly be a remote opportunity in Chicago OR on the East coast only. The candidate will be part of the Client Care - Field Services team to provide world-class project support to the clients. The individual must be able to work independently with minimal supervision to efficiently resolve electro-mechanical issues and equipment errors on automated materials handling systems and related equipment. Candidate will be responsible for site/application specific equipment set-up and programming and client training on proper equipment usage. Flexibility and adaptability to work safely at various worksites are required.The candidate will be working with PLC\'s and having Allen Bradley PLC experience is a huge plus for the role and equipment you will be working with and on along with having experience to diagnose mechanical and electrical problems using technical drawings. The candidate will have a very strong background being able to debug and troubleshoot the specified equipment (usually AB). The candidate must also be computer savvy and able to generate and conduct comprehensive service reports. The candidate should also have experience working directly in the mechanical/electronics field as well as the ability to read and utilize mechanical and electrical schematics to assist in troubleshooting and resolution of those components. *Must possess Bachelor\'s Degree (BSEE, BSME, BSE, etc) + Mechanically troubleshoot and repair minor mechanical problems. + Must know the capabilities and functionality of a PLC. + Interact courteously and professionally with clients, other engineers, managers, subcontractors, and other communications as assigned. + Diagnose mechanical and electrical problems using technical drawings. + Perform preventative maintenance and repairs on equipment. + Compile thorough, detailed reports, based on the work completed onsite. + Follow up with customers and other departments to ensure issues have been resolved.Submit your resume today! Contact a Volt representative by applying to this posting online for immediate consideration. We look forward to speaking with you soon. **Volt is an Equal Opportunity Employer.**",
          "ExpireDate": "2016-10-22T04:59:59Z",
          "HtmlFileUri": "http://slb.adicio.com/files/adguy-c-02/2016-09/22/00/10/57e383eb758d.html",
          "Id": "90129569",
          "JobCode": "",
          "JobSource": "direct_employer",
          "JobSummary": " professionals.Our client, located in Chicago, IL, is looking for a Senior Automation <span class=\"highlightTerm\">Engineer.</span> This position can also possibly be a remote opportunity in Chicago OR on the East coast only....",
          "JobTitle": "AUTOMATION ENGINEER",
          "Latitude": "41.8781136",
          "Longitude": "-87.6297982",
          "ModifiedDate": "2016-09-22T07:00:00Z",
          "NormalizedCountry": "US",
          "NormalizedState": "IL",
          "ParserId": "49baaecd3",
          "PostDate": "2016-09-22T07:00:00Z",
          "PostingCompany": "Volt",
          "PostingCompanyId": "0",
          "Requirements": "",
          "ResponseMethod": "url",
          "SalaryMax": "",
          "SalaryMin": "",
          "Source": "",
          "State": "IL",
          "Zip": "",
          "CompanyConfidential": "",
          "Category": [
            "engineering"
          ],
          "AssignedCategory": [
            "engineering"
          ],
          "Upgrades": [],
          "CategoryDisplay": [
            "Engineering"
          ],
          "SearchNetworks": "",
          "MatchedCategory": "",
          "CompanyLogo": "",
          "CompanyProfileDescription": "",
          "CompanyIndustry": "",
          "WorkStatus": "",
          "WorkStatusDisplay": [],
          "WorkShift": "",
          "WorkType": "",
          "CompanySize": "",
          "CompanyType": "",
          "RemoteDetailUrl": "",
          "PaymentInterval": "",
          "FormattedCityState": "Chicago, IL",
          "FormattedCityStateCountry": "Chicago, IL US",
          "Url": "http://careers.glassceiling.com/jobs/automation-engineer-chicago-il-90129569-d?rsite=careercast&rgroup=1&clientid=glass&widget=1&type=job&"
        }', true);
    }
}
