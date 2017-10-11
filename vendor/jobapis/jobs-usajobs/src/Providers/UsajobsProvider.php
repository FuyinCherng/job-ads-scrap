<?php namespace JobApis\Jobs\Client\Providers;

use JobApis\Jobs\Client\Job;

class UsajobsProvider extends AbstractProvider
{
    /**
     * Returns the standardized job object
     *
     * NOTE: The following properties are not being set:
     * PositionID
     * DepartmentName
     * ApplyURI
     * UserArea
     *
     * @param array $payload Raw job payload from the API
     *
     * @return \JobApis\Jobs\Client\Job
     */
    public function createJobObject($payload = [])
    {
        $id = $payload['MatchedObjectId'];
        $payload = $payload['MatchedObjectDescriptor'];

        $job = new Job([
            'sourceId' => $id,
            'title' => $payload['PositionTitle'],
            'name' => $payload['PositionTitle'],
            'url' => $payload['PositionURI'],
            'qualifications' => $payload['QualificationSummary'],
        ]);

        $job->setCompany($payload['OrganizationName']);

        $job = $this->setDates($payload, $job);
        $job = $this->setNestedProperties($payload, $job);
        $job = $this->setSalary($payload['PositionRemuneration'], $job);
        return $this->setLocation($payload['PositionLocation'], $job);
    }

    /**
     * Job response object default keys that should be set
     *
     * @return  array
     */
    public function getDefaultResponseFields()
    {
        return [
            'MatchedObjectId',
            'MatchedObjectDescriptor',
        ];
    }

    /**
     * Get listings path
     *
     * @return string
     */
    public function getListingsPath()
    {
        return 'SearchResult.SearchResultItems';
    }

    /**
     * Sets nested properties
     *
     * @param $payload array
     * @param $job \JobApis\Jobs\Client\Job
     *
     * @return \JobApis\Jobs\Client\Job
     */
    protected function setDates($payload, $job)
    {
        $dateFields = [
            'PublicationStartDate' => 'datePosted',
            'ApplicationCloseDate' => 'validThrough',
            'PositionStartDate' => 'startDate',
            'PositionEndDate' => 'endDate',
        ];
        foreach ($dateFields as $key => $field) {
            if (strtotime($payload[$key]) !== false) {
                $job->{'set'.ucfirst($field)}(new \DateTime($payload[$key]));
            }
        }

        return $job;
    }

    /**
     * Sets nested properties
     *
     * @param $payload array
     * @param $job \JobApis\Jobs\Client\Job
     *
     * @return \JobApis\Jobs\Client\Job
     */
    protected function setNestedProperties($payload, $job)
    {
        $nestedProperties = [
            'PositionFormattedDescription' => [
                'attribute' => 'Content',
                'property' => 'description',
            ],
            'PositionSchedule' => [
                'attribute' => 'Name',
                'property' => 'employmentType',
            ],
            'JobCategory' => [
                'attribute' => 'Name',
                'property' => 'occupationalCategory',
            ],
            'PositionOfferingType' => [
                'attribute' => 'Name',
                'property' => 'additionalType',
            ],
        ];

        foreach ($nestedProperties as $property => $value) {
            if (isset($payload[$property])
                && isset($payload[$property][0])
                && isset($payload[$property][0][$value['attribute']])
            ) {
                $job->{"set".ucfirst($value['property'])}($payload[$property][0][$value['attribute']]);
            }
        }

        return $job;
    }

    /**
     * Parses the salary range and adds it to the job
     *
     * @param $salaries array
     * @param $job \JobApis\Jobs\Client\Job
     *
     * @return \JobApis\Jobs\Client\Job
     */
    protected function setSalary($salaries, $job)
    {
        if (isset($salaries[0]) && isset($salaries[0]['MinimumRange'])) {
            $job->setMinimumSalary($salaries[0]['MinimumRange']);
        }
        if (isset($salaries[0]) && isset($salaries[0]['MaximumRange'])) {
            $job->setMaximumSalary($salaries[0]['MaximumRange']);
        }
        // NOTE: Can also get pay shedule from $salaries[0]["RateIntervalCode"]
        return $job;
    }

    /**
     * Parses the location and attaches it to the job
     *
     * @param $locations array
     * @param $job \JobApis\Jobs\Client\Job
     *
     * @return \JobApis\Jobs\Client\Job
     */
    protected function setLocation($locations, $job)
    {
        if (isset($locations[0])) {
            if (isset($locations[0]['LocationName'])) {
                $job->setLocation($locations[0]['LocationName']);
            }
            if (isset($locations[0]['CountryCode'])) {
                $job->setCountry($locations[0]['CountryCode']);
            }
            if (isset($locations[0]['CountrySubDivisionCode'])) {
                $job->setState($locations[0]['CountrySubDivisionCode']);
            }
            if (isset($locations[0]['CityName'])) {
                $job->setCity($locations[0]['CityName']);
            }
            if (isset($locations[0]['Longitude'])) {
                $job->setLongitude($locations[0]['Longitude']);
            }
            if (isset($locations[0]['Latitude'])) {
                $job->setLatitude($locations[0]['Latitude']);
            }
        }
        return $job;
    }
}
