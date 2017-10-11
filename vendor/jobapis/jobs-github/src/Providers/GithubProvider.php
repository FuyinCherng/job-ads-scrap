<?php namespace JobApis\Jobs\Client\Providers;

use JobApis\Jobs\Client\Job;

class GithubProvider extends AbstractProvider
{
    /**
     * Returns the standardized job object.
     *
     * @param array $payload
     *
     * @return \JobApis\Jobs\Client\Job
     */
    public function createJobObject($payload)
    {
        $job = new Job;

        $map = $this->getJobSetterMap();

        array_walk($map, function ($path, $setter) use ($payload, &$job) {
            try {
                $value = static::getValue(explode('.', $path), $payload);
                $job->$setter($value);
            } catch (\OutOfRangeException $e) {
                // do nothing
            }
        });

        $job = $this->setCityStateLocation($job);

        return $job;
    }

    public function getDefaultResponseFields()
    {
        return [
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
    }

    /**
     * Retrieves array that maps job setter methods to payload keys.
     *
     * @return array
     */
    protected function getJobSetterMap()
    {
        return [
            'setCompany' => 'company',
            'setCompanyName' => 'company',
            'setCompanyLogo' => 'company_logo',
            'setCompanyUrl' => 'company_url',
            'setDescription' => 'description',
            'setDatePostedAsString' => 'created_at',
            'setLocation' => 'location',
            'setName' => 'title',
            'setTitle' => 'title',
            'setSourceId' => 'id',
            'setUrl' => 'url',
            'setWorkHours' => 'type',
        ];
    }

    /**
     * Get listings path.
     *
     * @return  string
     */
    public function getListingsPath()
    {
        return '';
    }

    /**
     * Get source attribution
     *
     * @return string
     */
    public function getSource()
    {
        return 'Github';
    }

    /**
     * Sets city and state from single field on job
     *
     * @param Job $job Job object
     *
     * @return Job
     */
    public function setCityStateLocation($job)
    {
        if (isset($job->location)) {
            $locationArray = static::parseLocation($job->location);

            if (isset($locationArray[0])) {
                $job->setCity($locationArray[0]);
            }
            if (isset($locationArray[1])) {
                $job->setState($locationArray[1]);
            }
        }

        return $job;
    }
}
