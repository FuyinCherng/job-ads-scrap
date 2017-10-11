<?php namespace JobApis\Jobs\Client\Providers\Test;

use JobApis\Jobs\Client\Collection;
use JobApis\Jobs\Client\Job;
use JobApis\Jobs\Client\Providers\IeeeProvider;
use JobApis\Jobs\Client\Queries\IeeeQuery;
use Mockery as m;

class IeeeProviderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->query = m::mock('JobApis\Jobs\Client\Queries\IeeeQuery');

        $this->client = new IeeeProvider($this->query);
    }

    public function testItCanGetDefaultResponseFields()
    {
        $fields = [
            "NormalizedJobTitle",
            "AdId",
            "ApplyCity",
            "ApplyCountry",
            "ApplyEmail",
            "ApplyFax",
            "ApplyName",
            "ApplyPhone",
            "ApplyState",
            "ApplyUrl",
            "ApplyZip",
            "City",
            "CityDisplay",
            "ClientId",
            "CompanyProfileDescription",
            "CompanyId",
            "Company",
            "CompanyProfileUrl",
            "CompanySize",
            "CompanyType",
            "Country",
            "Description",
            "ExpireDate",
            "HtmlFileUri",
            "Id",
            "JobCode",
            "JobSource",
            "JobSummary",
            "JobTitle",
            "Latitude",
            "Longitude",
            "ModifiedDate",
            "NormalizedCountry",
            "NormalizedState",
            "ParserId",
            "PostDate",
            "PostingCompany",
            "PostingCompanyId",
            "Requirements",
            "ResponseMethod",
            "SalaryMax",
            "SalaryMin",
            "Source",
            "State",
            "Zip",
            "CompanyConfidential",
            "Category",
            "WorkStatus",
            "AssignedCategory",
            "Upgrades",
            "MatchedCategory",
            "CategoryDisplay",
            "WorkType",
            "SearchNetworks",
            "CompanyLogo",
            "CompanyIndustry",
            "WorkStatusDisplay",
            "WorkShift",
            "RemoteDetailUrl",
            "PaymentInterval",
            "FormattedCityState",
            "FormattedCityStateCountry",
            "Url",
        ];
        $this->assertEquals($fields, $this->client->getDefaultResponseFields());
    }

    public function testItCanGetListingsPath()
    {
        $this->assertEquals('Jobs', $this->client->getListingsPath());
    }

    public function testItCanCreateJobObjectWhenLocationAndCompanyNotSet()
    {
        $payload = $this->createJobArray();

        $this->query->shouldReceive('get')
            ->twice()
            ->andReturn(null);

        $results = $this->client->createJobObject($payload);

        $this->assertInstanceOf(Job::class, $results);
        $this->assertEquals($payload['JobTitle'], $results->getTitle());
        $this->assertEquals($payload['JobTitle'], $results->getName());
        $this->assertEquals($payload['Description'], $results->getDescription());
        $this->assertEquals($payload['Url'], $results->getUrl());
    }

    /**
     * Integration test for the client's getJobs() method.
     */
    public function testItCanGetJobs()
    {
        $options = [
            'keyword' => uniqid(),
            'location' => uniqid(),
        ];

        $guzzle = m::mock('GuzzleHttp\Client');

        $query = new IeeeQuery($options);

        $client = new IeeeProvider($query);

        $client->setClient($guzzle);

        $response = m::mock('GuzzleHttp\Message\Response');

        $jobs = [
            0 => $this->createJobArray(),
            1 => $this->createJobArray(),
        ];

        $guzzle->shouldReceive('get')
            ->with($query->getUrl(), [])
            ->once()
            ->andReturn($response);
        $response->shouldReceive('getBody')
            ->once()
            ->andReturn(json_encode(['Jobs' => $jobs]));

        $results = $client->getJobs();

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(2, $results);
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

        $query = new IeeeQuery([
            'keyword' => $keyword,
        ]);

        $client = new IeeeProvider($query);

        $results = $client->getJobs();

        $this->assertInstanceOf('JobApis\Jobs\Client\Collection', $results);

        foreach($results as $job) {
            $this->assertEquals($keyword, $job->query);
        }
    }

    private function createJobArray()
    {
        return [
            'NormalizedJobTitle' => 'Assistant Professor',
            'AdId' => '',
            'ApplyCity' => '',
            'ApplyCountry' => 'United States',
            'ApplyEmail' => 'CompEngPos-group@usna.edu',
            'ApplyFax' => '',
            'ApplyName' => ' ',
            'ApplyPhone' => '',
            'ApplyState' => '',
            'ApplyUrl' => 'https://www.usna.edu/HRO/jobinfo/ASSTPROF-CE2016.php',
            'ApplyZip' => '',
            'City' => 'Annapolis',
            'CityDisplay' => 'Annapolis',
            'ClientId' => 'ieee',
            'CompanyProfileDescription' => '',
            'CompanyId' => '894935',
            'Company' => 'Electrical & Computer Engineering Department',
            'CompanyProfileUrl' => '',
            'CompanySize' => '',
            'CompanyType' => '',
            'Country' => 'United States',
            'Description' => '<p><span style="font-size: small; font-family: arial, helvetica, sans-serif;">The Electrical and Computer Engineering Department at the United States Naval Academy is seeking applicants to fill multiple tenure-track positions at the Assistant Professor level in Computer Engineering. Applicants with teaching and research interests in all areas of computer engineering will be considered, including but not limited to </span><span style="font-size: small; font-family: arial, helvetica, sans-serif;">cyber security</span><span style="font-size: small; font-family: arial, helvetica, sans-serif;"><span style="font-family: arial, helvetica, sans-serif;">,</span> operating systems, computer networking, parallel computing, distributed systems, storage systems, embedded </span><span style="font-family: arial, helvetica, sans-serif; font-size: small;">systems, and compilers. </span></p>
<div>
<p><span style="font-size: small; font-family: arial, helvetica, sans-serif;">For more information about these positions and how to apply please visit the USNA position announcement at <a href="https://www.usna.edu/HRO/jobinfo/ASSTPROF-CE2016.php" target="_blank">https://www.usna.edu/HRO/jobinfo/ASSTPROF-CE2016.php</a>  </span></p>
</div>',
            'ExpireDate' => '2016-12-09T04:59:59Z',
            'HtmlFileUri' => '',
            'Id' => '90265813',
            'JobCode' => '',
            'JobSource' => 'direct_employer',
            'JobSummary' => 'The Electrical and Computer Engineering Department at the United States Naval Academy is seeking applicants to fill multiple tenure-track positions at the Assistant Professor level in Computer Engineering. Applicants with teaching and research interests in all areas of computer engineering will be c...',
            'JobTitle' => 'Assistant Professor of Computer Engineering',
            'Latitude' => '38.9889503',
            'Longitude' => '-76.4620928',
            'ModifiedDate' => '2016-11-08T19:00:00Z',
            'NormalizedCountry' => 'US',
            'NormalizedState' => 'MD',
            'ParserId' => '',
            'PostDate' => '2016-11-08T19:00:00Z',
            'PostingCompany' => '',
            'PostingCompanyId' => '0',
            'Requirements' => '',
            'ResponseMethod' => 'none',
            'SalaryMax' => '',
            'SalaryMin' => '',
            'Source' => '',
            'State' => 'Maryland',
            'Zip' => '21402',
            'CompanyConfidential' => '',
            'Category' =>
                array (
                    0 => 'computer_programming_systems',
                    1 => 'school_admin',
                    2 => 'computer_engineering',
                ),
            'WorkStatus' =>
                array (
                    0 => 'full_time',
                ),
            'AssignedCategory' =>
                array (
                    0 => 'computer_programming_systems',
                    1 => 'school_admin',
                    2 => 'computer_engineering',
                ),
            'Upgrades' =>
                array (
                    0 => '',
                ),
            'MatchedCategory' =>
                array (
                    0 => 'information_technology',
                    1 => 'education_training',
                ),
            'CategoryDisplay' =>
                array (
                    0 => 'Computer Programming/Systems',
                    1 => 'Faculty',
                    2 => 'Computer Engineering',
                    3 => 'Information Technology',
                    4 => 'Education',
                ),
            'WorkType' =>
                array (
                    0 => 'employee',
                ),
            'SearchNetworks' => '',
            'CompanyLogo' => '',
            'CompanyIndustry' => '',
            'WorkStatusDisplay' =>
                array (
                    0 => 'Full Time',
                ),
            'WorkShift' => '',
            'RemoteDetailUrl' => '',
            'PaymentInterval' => '',
            'FormattedCityState' => 'Annapolis, MD',
            'FormattedCityStateCountry' => 'Annapolis, MD US',
            'Url' => 'http://jobs.ieee.org/jobs/assistant-professor-of-computer-engineering-annapolis-maryland-21402-90265813-d?widget=1&type=job&',
        ];
    }
}
