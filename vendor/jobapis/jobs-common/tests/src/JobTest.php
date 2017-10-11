<?php namespace JobApis\Jobs\Client\Tests;

use JobApis\Jobs\Client\Job;
use JobApis\Jobs\Client\Schema\Entity\GeoCoordinates;
use JobApis\Jobs\Client\Schema\Entity\Organization;
use JobApis\Jobs\Client\Schema\Entity\Place;
use JobApis\Jobs\Client\Schema\Entity\PostalAddress;

/**
 *  Uses PHPUnit to test methods and properties set in
 *  the Job class.
 */
class JobTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->job = new Job();
    }

    public function testGetSourceId()
    {
        $sourceId = uniqid();

        $result = $this->job->setSourceId($sourceId)->getSourceId();

        $this->assertEquals($sourceId, $result);
    }

    public function testInstantiateJobWithAttributes()
    {
        $sourceId = uniqid();
        $title = "test title";
        $job = new Job([
            'sourceId' => $sourceId,
            'title' => $title
        ]);

        $this->assertEquals($sourceId, $job->getSourceId());
        $this->assertEquals($title, $job->getTitle());
    }

    public function testItCanCheckIfExistingPropertyIsset()
    {
        $key = 'sourceId';
        $value = uniqid();

        $this->job->{'set'.ucfirst($key)}($value);
        $result = isset($this->job->{$key});

        $this->assertTrue($result);
    }

    public function testItCanSetAndGetExistingProperty()
    {
        $key = 'sourceId';
        $value = uniqid();

        $this->job->{'set'.ucfirst($key)}($value);
        $result = $this->job->{$key};

        $this->assertEquals($value, $result);
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testItCanNotHandleNonImplementedMethods()
    {
        $method = uniqid();

        $this->job->{$method}();
    }

    /**
     * @expectedException OutOfRangeException
     */
    public function testItCanNotGetNonExistentProperty()
    {
        $key = uniqid();

        $this->job->{$key};
    }

    public function testSetSourceId()
    {
        // Arrange
        $sourceId = uniqid();
        // Act
        $this->job->setSourceId($sourceId);
        // Assert
        $this->assertEquals($sourceId, $this->job->sourceId);
    }

    public function testSetTitle()
    {
        $input = 'test title';
        $this->job->setTitle($input);
        $this->assertEquals($input, $this->job->title);
        $this->assertEquals($input, $this->job->getTitle());
    }

    public function testSetJavascriptAction()
    {
        $input = 'onclick';
        $this->job->setJavascriptAction($input);
        $this->assertEquals($input, $this->job->javascriptAction);
        $this->assertEquals($input, $this->job->getJavascriptAction());
    }

    public function testSetJavascriptFunction()
    {
        $input = 'doJob(' . uniqid() . ')';
        $this->job->setJavascriptFunction($input);
        $this->assertEquals($input, $this->job->javascriptFunction);
        $this->assertEquals($input, $this->job->getJavascriptFunction());
    }

    public function testSetDescription()
    {
        $input = 'Test job listing description';
        $this->job->setDescription($input);
        $this->assertEquals($input, $this->job->description);
    }

    public function testSetSource()
    {
        $input = 'SourceName';
        $this->job->setSource($input);
        $this->assertEquals($input, $this->job->source);
        $this->assertEquals($input, $this->job->getSource());
    }

    public function testSetUrl()
    {
        $input = 'http://www.example.com/job-listing-url';
        $this->job->setUrl($input);
        $this->assertEquals($input, $this->job->url);
        $this->assertEquals($input, $this->job->getUrl());
    }

    public function testSetQuery()
    {
        $input = 'engineering';
        $this->job->setQuery($input);
        $this->assertEquals($input, $this->job->query);
        $this->assertEquals($input, $this->job->getQuery());
    }

    public function testSetStartDate()
    {
        $input = '10-13-1988';
        $this->job->setStartDate($input);
        $this->assertEquals($input, $this->job->startDate);
        $this->assertEquals($input, $this->job->getStartDate());
    }

    public function testSetEndDate()
    {
        $input = '10-13-2016';
        $this->job->setEndDate($input);
        $this->assertEquals($input, $this->job->endDate);
        $this->assertEquals($input, $this->job->getEndDate());
    }

    public function testSetMinimumSalary()
    {
        $input = '10000';
        $this->job->setMinimumSalary($input);
        $this->assertEquals($input, $this->job->minimumSalary);
        $this->assertEquals($input, $this->job->getMinimumSalary());
        $this->assertEquals($input, $this->job->getBaseSalary());
    }

    public function testSetMaximumSalary()
    {
        $input = '100000';
        $this->job->setMaximumSalary($input);
        $this->assertEquals($input, $this->job->maximumSalary);
        $this->assertEquals($input, $this->job->getMaximumSalary());
    }

    public function testSetBaseSalaryWithOnlyNumbers()
    {
        $input = '100000';
        $result = 100000.00;
        $this->job->setBaseSalary($input);
        $this->assertEquals($result, $this->job->baseSalary);
        $this->assertEquals($result, $this->job->getBaseSalary());
    }

    public function testSetBaseSalaryWithOnlyNumbersAndDecimal()
    {
        $input = '100000.00';
        $result = 100000.00;
        $this->job->setBaseSalary($input);
        $this->assertEquals($result, $this->job->baseSalary);
        $this->assertEquals($result, $this->job->getBaseSalary());
    }

    public function testSetBaseSalaryWithOnlyNumbersAndMisplacedDecimal()
    {
        $input = '100.00000';
        $result = 100.00;
        $this->job->setBaseSalary($input);
        $this->assertEquals($result, $this->job->baseSalary);
        $this->assertEquals($result, $this->job->getBaseSalary());
    }

    public function testSetBaseSalaryWithCommas()
    {
        $input = '100,000';
        $result = 100000.00;
        $this->job->setBaseSalary($input);
        $this->assertEquals($result, $this->job->baseSalary);
        $this->assertEquals($result, $this->job->getBaseSalary());
    }

    public function testSetBaseSalaryWithCurrencySymbol()
    {
        $input = '$100000';
        $result = 100000.00;
        $this->job->setBaseSalary($input);
        $this->assertEquals($result, $this->job->baseSalary);
        $this->assertEquals($result, $this->job->getBaseSalary());
    }

    public function testSetBaseSalaryWithoutNumbers()
    {
        $input = 'i like turtles.';
        $this->job->setBaseSalary($input);
        $this->assertNull($this->job->baseSalary);
        $this->assertNull($this->job->getBaseSalary());
    }

    public function testSetCompany()
    {
        $company = uniqid();
        $this->job->setCompany($company);
        $this->assertEquals($company, $this->job->company);
        $this->assertEquals($company, $this->job->getCompanyName());
        $this->assertEquals($company, $this->job->getCompany());
    }

    public function testSetLocation()
    {
        $location = uniqid();
        $this->job->setLocation($location);
        $this->assertEquals($location, $this->job->location);
        $this->assertEquals($location, $this->job->getLocation());
    }

    public function testSetIndustry()
    {
        $industry = uniqid();
        $this->job->setIndustry($industry);
        $this->assertEquals($industry, $this->job->industry);
        $this->assertEquals($industry, $this->job->getIndustry());
    }

    public function testSetStreetAddress()
    {
        $address = uniqid();
        $this->job->setStreetAddress($address);
        $this->assertEquals($address, $this->job->getStreetAddress());
        $this->assertEquals($address, $this->job->getHiringOrganization()->getAddress()->getStreetAddress());
        $this->assertNull($this->job->getHiringOrganization()->getAddress()->getPostOfficeBoxNumber());
        $this->assertEquals($address, $this->job->getJobLocation()->getAddress()->getStreetAddress());
        $this->assertNull($this->job->getJobLocation()->getAddress()->getPostOfficeBoxNumber());
    }

    public function testSetStreetAddressAddsPOBox()
    {
        $address = uniqid().'PO Box';
        $this->job->setStreetAddress($address);
        $this->assertEquals($address, $this->job->getStreetAddress());
        $this->assertEquals($address, $this->job->getHiringOrganization()->getAddress()->getStreetAddress());
        $this->assertEquals($address, $this->job->getHiringOrganization()->getAddress()->getPostOfficeBoxNumber());
        $this->assertEquals($address, $this->job->getJobLocation()->getAddress()->getStreetAddress());
        $this->assertEquals($address, $this->job->getJobLocation()->getAddress()->getPostOfficeBoxNumber());
    }

    public function testSetCity()
    {
        $city = uniqid();
        $this->job->setCity($city);
        $this->assertEquals($city, $this->job->getCity());
        $this->assertEquals($city, $this->job->getHiringOrganization()->getAddress()->getAddressLocality());
        $this->assertEquals($city, $this->job->getJobLocation()->getAddress()->getAddressLocality());
    }

    public function testSetState()
    {
        $state = uniqid();
        $this->job->setState($state);
        $this->assertEquals($state, $this->job->getState());
        $this->assertEquals($state, $this->job->getHiringOrganization()->getAddress()->getAddressRegion());
        $this->assertEquals($state, $this->job->getJobLocation()->getAddress()->getAddressRegion());
    }

    public function testSetCountry()
    {
        $country = uniqid();
        $this->job->setCountry($country);
        $this->assertEquals($country, $this->job->getCountry());
        $this->assertEquals($country, $this->job->getHiringOrganization()->getAddress()->getAddressCountry());
        $this->assertEquals($country, $this->job->getJobLocation()->getAddress()->getAddressCountry());
    }

    public function testSetPostalCode()
    {
        $postal_code = uniqid();
        $this->job->setPostalCode($postal_code);
        $this->assertEquals($postal_code, $this->job->getPostalCode());
        $this->assertEquals($postal_code, $this->job->getHiringOrganization()->getAddress()->getPostalCode());
        $this->assertEquals($postal_code, $this->job->getJobLocation()->getAddress()->getPostalCode());
    }

    public function testSetLatitudeLongitude()
    {
        $latitude = uniqid();
        $longitude = uniqid();
        $this->job->setLatitude($latitude);
        $this->job->setLongitude($longitude);

        $this->assertEquals($latitude, $this->job->getLatitude());
        $this->assertEquals($longitude, $this->job->getLongitude());

        $this->assertEquals($latitude, $this->job->getJobLocation()->getGeo()->getLatitude());
        $this->assertEquals($longitude, $this->job->getJobLocation()->getGeo()->getLongitude());
    }

    public function testSetTelephone()
    {
        $telephone = uniqid();
        $this->job->setTelephone($telephone);
        $this->assertEquals($telephone, $this->job->getTelephone());
        $this->assertEquals($telephone, $this->job->getHiringOrganization()->getTelephone());
        $this->assertEquals($telephone, $this->job->getJobLocation()->getTelephone());
    }

    public function testSetCompanyName()
    {
        $company_name = uniqid();
        $this->job->setCompanyName($company_name);
        $this->assertEquals($company_name, $this->job->getCompanyName());
    }

    public function testSetCompanyDescription()
    {
        $description = uniqid();
        $this->job->setCompanyDescription($description);
        $this->assertEquals($description, $this->job->getCompanyDescription());
    }

    public function testSetCompanyEmail()
    {
        $email = uniqid();
        $this->job->setCompanyEmail($email);
        $this->assertEquals($email, $this->job->getCompanyEmail());
    }

    public function testSetCompanyLogo()
    {
        $logo = 'http://www.example.com/'.uniqid();
        $this->job->setCompanyLogo($logo);
        $this->assertEquals($logo, $this->job->getCompanyLogo());
    }

    public function testSetCompanyUrl()
    {
        $url = 'http://www.example.com/'.uniqid();
        $this->job->setCompanyUrl($url);
        $this->assertEquals($url, $this->job->getCompanyUrl());
    }

    public function testSetDatePostedWithValidDateTime()
    {
        $date = new \DateTime;
        $this->job->setDatePosted($date);
        $this->assertEquals($date, $this->job->getDatePosted());
    }

    public function testSetDatePostedAsStringWithValidDateTimeString()
    {
        $date = '2015-05-01';
        $this->job->setDatePostedAsString($date);
    }

    /**
     * @expectedException JobApis\Jobs\Client\Exceptions\InvalidFormatException
     */
    public function testSetDatePostedAsStringWithoutValidDateTimeString()
    {
        $date = 'i like turtles';
        $this->job->setDatePostedAsString($date);
    }

    public function testSetOccupationalCategoryWithCodeAndTitle()
    {
        $code = rand(1, 20) . '-' . rand(1000, 9999);
        $title = uniqid();
        $this->job->setOccupationalCategoryWithCodeAndTitle($code, $title);
        $this->assertEquals($code . ' - ' . $title, $this->job->getOccupationalCategory());
    }

    public function testBenignTextValues()
    {
        $attributes = [
            'alternateName', 'jobBenefits', 'educationRequirements', 'employmentType',
            'experienceRequirements', 'incentiveCompensation', 'occupationalCategory',
            'qualifications', 'responsibilities', 'salaryCurrency', 'skills',
            'specialCommitments', 'title', 'workHours'
        ];

        array_walk($attributes, function ($attribute) {
            $value = uniqid();
            $this->job->{'set'.ucfirst($attribute)}($value);
            $this->assertEquals($value, $this->job->{'get'.ucfirst($attribute)}());
        });
    }

    public function testReturnNullForUnsetProperties()
    {
        $this->assertNull($this->job->getTelephone());

        $this->assertNull($this->job->getCompanyName());
        $this->assertNull($this->job->getCompanyDescription());
        $this->assertNull($this->job->getCompanyLogo());
        $this->assertNull($this->job->getCompanyEmail());
        $this->assertNull($this->job->getCompanyUrl());

        $this->job->setJobLocation(new Place);
        $this->assertNull($this->job->getStreetAddress());
        $this->assertNull($this->job->getCity());
        $this->assertNull($this->job->getState());
        $this->assertNull($this->job->getPostalCode());
        $this->assertNull($this->job->getCountry());
    }

    public function testItCanJsonEncode()
    {
        $attributes = $this->getJobAttributes();

        $job = new Job($attributes);
        $job->setCompanyName($attributes['company']);

        $jsonEncode = json_encode($job);
        $toJson = $job->toJson();

        $this->assertEquals($jsonEncode, $toJson);

        foreach ($attributes as $key => $value) {
            $this->assertEquals($value, $job->{$key});
        }

    }

    public function testItCanSerializeWithAllPropertiesByDefault()
    {
        $attributes = $this->getJobAttributes();
        $job = new Job($attributes);
        $ref = new \ReflectionClass($job);
        $properties = $ref->getProperties();

        $toJson = $job->toJson();
        $json = json_decode($toJson, true);

        $this->assertNotContains('@context', array_keys($json));
        $this->assertEquals(count($properties), count($json));
    }

    public function testItCanSerializeWithAllPropertiesAndLinkedDataContexts()
    {
        $attributes = $this->getJobAttributes();
        $job = new Job($attributes);
        $ref = new \ReflectionClass($job);
        $properties = $ref->getProperties();

        $toJson = $job->toJson(Job::SERIALIZE_STANDARD_LD);
        $json = json_decode($toJson, true);

        $this->assertContains('@context', array_keys($json));
        $this->assertEquals((count($properties) + 2), count($json));
    }

    public function testItCanSerializeWithCorePropertiesAndLinkedDataContexts()
    {
        $attributes = $this->getJobAttributes();
        $job = new Job($attributes);
        $ref = new \ReflectionClass($job);
        $properties = array_filter($ref->getProperties(), function ($property) {
            return $property->class != 'JobApis\Jobs\Client\Job';
        });

        $toJson = $job->toJson(Job::SERIALIZE_CORE_SCHEMA_LD);
        $json = json_decode($toJson, true);

        $this->assertContains('@context', array_keys($json));
        $this->assertEquals((count($properties) + 2), count($json));
    }

    public function testItCanBeRepresentedAsArray()
    {
        $attributes = $this->getJobAttributes();
        $address = new PostalAddress;
        $hiringOrg = new Organization;
        $jobLocation = new Place;
        $geoCoordinates = new GeoCoordinates;
        $hiringOrg->setAddress($address);
        $jobLocation->setAddress($address);
        $job = new Job($attributes);
        $jobArray = $job->setHiringOrganization($hiringOrg)
            ->setJobLocation($jobLocation)
            ->toArray();

        array_walk_recursive($attributes, function ($value, $key) use ($jobArray) {
            $this->assertEquals($jobArray[$key], $value);
        });

        $this->assertEquals($hiringOrg->toArray(), $jobArray['hiringOrganization']);
        $this->assertEquals($address->toArray(), $jobArray['hiringOrganization']['address']);
        $this->assertEquals($jobLocation->toArray(), $jobArray['jobLocation']);
        $this->assertEquals($address->toArray(), $jobArray['jobLocation']['address']);
    }

    private function getJobAttributes()
    {
        $attributes = [
            'alternateName' => uniqid(),
            'baseSalary' => rand(10, 1000),
            'datePosted' => new \DateTime(),
            'description' => uniqid(),
            'educationRequirements' => uniqid(),
            'employmentType' => uniqid(),
            'endDate' => uniqid(),
            'experienceRequirements' => uniqid(),
            'incentiveCompensation' => uniqid(),
            'industry' => uniqid(),
            'javascriptAction' => uniqid(),
            'javascriptFunction' => uniqid(),
            'jobBenefits' => uniqid(),
            'maximumSalary' => uniqid(),
            'minimumSalary' => uniqid(),
            'name' => uniqid(),
            'occupationalCategory' => uniqid(),
            'qualifications' => uniqid(),
            'query' => uniqid(),
            'responsibilities' => uniqid(),
            'salaryCurrency' => uniqid(),
            'skills' => uniqid(),
            'source' => uniqid(),
            'sourceId' => uniqid(),
            'specialCommitments' => uniqid(),
            'startDate' => new \DateTime(),
            'title' => uniqid(),
            'url' => uniqid(),
            'workHours' => uniqid(),
            'company' => uniqid(),
            'location' => uniqid(),
        ];
        return $attributes;
    }
}
