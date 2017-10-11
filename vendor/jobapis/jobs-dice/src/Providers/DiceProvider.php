<?php namespace JobApis\Jobs\Client\Providers;

use JobApis\Jobs\Client\Job;

class DiceProvider extends AbstractProvider
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
            'title' => $payload['jobTitle'],
            'name' => $payload['jobTitle'],
            'url' => $payload['detailUrl'],
            'location' => $payload['location'],
        ]);

        $location = static::parseLocation($payload['location']);

        $job->setCompany($payload['company'])
            ->setDatePostedAsString($payload['date']);

        if (isset($location[0])) {
            $job->setCity($location[0]);
        }
        if (isset($location[1])) {
            $job->setState($location[1]);
        }

        return $job;
    }

    /**
     * Job response object default keys that should be set
     *
     * @return  string
     */
    public function getDefaultResponseFields()
    {
        return [
            'jobTitle',
            'company',
            'location',
            'date',
            'detailUrl'
        ];
    }

    /**
     * Get listings path
     *
     * @return  string
     */
    public function getListingsPath()
    {
        return 'resultItemList';
    }
}
