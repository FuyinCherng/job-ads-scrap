<?php namespace JobApis\Jobs\Client\Providers;

use JobApis\Jobs\Client\Job;

class CareerjetProvider extends AbstractProvider
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
            'title' => $payload['title'],
            'name' => $payload['title'],
            'description' => $payload['description'],
            'url' => $payload['url'],
            'location' => $payload['locations'],
        ]);

        $job->setCompany($payload['company'])
            ->setDatePostedAsString($payload['date'])
            ->setBaseSalary($payload['salary']);

        return $job;
    }

    /**
     * Job response object default keys that should be set
     *
     * @return  array
     */
    public function getDefaultResponseFields()
    {
        return [
            'url',
            'title',
            'locations',
            'company',
            'salary',
            'date',
            'description',
            'site',
        ];
    }

    /**
     * Get listings path
     *
     * @return  string
     */
    public function getListingsPath()
    {
        return 'jobs';
    }
}
