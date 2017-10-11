<?php namespace JobApis\Jobs\Client\Providers;

use JobApis\Jobs\Client\Job;

class CareercastProvider extends AbstractProvider
{
    /**
     * Returns the standardized job object
     *
     * @param array $payload
     *
     * @return \JobApis\Jobs\Client\Job
     */
    public function createJobObject($payload)
    {
        $job = new Job([
            'description' => $payload['Description'],
            'title' => $payload['JobTitle'],
            'name' => $payload['JobTitle'],
            'url' => $payload['Url'],
            'sourceId' => $payload['Id'],
            'qualifications' => $payload['Requirements'],
            'maximumSalaray' => $payload['SalaryMax'],
            'minimumSalaray' => $payload['SalaryMin'],
            'baseSalaray' => $payload['SalaryMin'],
        ]);
        $job->setDatePostedAsString($payload['PostDate']);
        $job = $this->setValidThroughAsString($payload['ExpireDate'], $job);
        $job = $this->setCategory($payload, $job);
        $job = $this->setCompany($payload, $job);
        return $this->setLocation($payload, $job);
    }

    /**
     * Job response object default keys that should be set
     *
     * @return  string
     */
    public function getDefaultResponseFields()
    {
        return [
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
    }

    /**
     * Get listings path
     *
     * @return  string
     */
    public function getListingsPath()
    {
        return 'Jobs';
    }

    /**
     * Parses the category and work type and attaches it to the job
     *
     * @param $payload array
     * @param $job \JobApis\Jobs\Client\Job
     *
     * @return \JobApis\Jobs\Client\Job
     */
    protected function setCategory($payload, $job)
    {
        if (isset($payload['CategoryDisplay']) && is_array($payload['CategoryDisplay'])) {
            $job->setOccupationalCategory(implode(', ', $payload['CategoryDisplay']));
        }
        if (isset($payload['CategoryDisplay']) && is_array($payload['WorkStatusDisplay'])) {
            $job->setEmploymentType(implode(', ', $payload['WorkStatusDisplay']));
        }
        return $job;
    }

    /**
     * Parses the company name and attaches it to the job
     *
     * @param $payload array
     * @param $job \JobApis\Jobs\Client\Job
     *
     * @return \JobApis\Jobs\Client\Job
     */
    protected function setCompany($payload, $job)
    {
        if (isset($payload['Company'])) {
            $job->setCompany($payload['Company']);
        }
        return $job;
    }

    /**
     * Parses the location and attaches it to the job
     *
     * @param $payload array
     * @param $job \JobApis\Jobs\Client\Job
     *
     * @return \JobApis\Jobs\Client\Job
     */
    protected function setLocation($payload, $job)
    {
        if (isset($payload['FormattedCityState'])) {
            $job->setLocation($payload['FormattedCityState']);
        }
        if (isset($payload['State'])) {
            $job->setState($payload['State']);
        }
        if (isset($payload['Country'])) {
            $job->setCountry($payload['Country']);
        }
        if (isset($payload['City'])) {
            $job->setCity($payload['City']);
        }
        if (isset($payload['Longitude'])) {
            $job->setLongitude($payload['Longitude']);
        }
        if (isset($payload['Latitude'])) {
            $job->setLatitude($payload['Latitude']);
        }
        if (isset($payload['Zip'])) {
            $job->setPostalCode($payload['Zip']);
        }
        return $job;
    }

    /**
     * Sets validThrough.
     *
     * @param string $validThrough
     * @param $job \JobApis\Jobs\Client\Job
     *
     * @return $job
     */
    protected function setValidThroughAsString($validThrough, $job)
    {
        if (strtotime($validThrough) !== false) {
            $job->validThrough = new \DateTime($validThrough);
        }

        return $job;
    }
}
