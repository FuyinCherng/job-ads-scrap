<?php namespace JobApis\Jobs\Client\Providers;

use JobApis\Jobs\Client\Job;

class CareerbuilderProvider extends AbstractProvider
{
    /**
     * Returns the standardized job object
     *
     * @param array $payload Raw job payload from the API
     *
     * @return \JobApis\Jobs\Client\Job
     */
    public function createJobObject($payload = [])
    {
        $job = new Job([
            'description' => $payload['DescriptionTeaser'],
            'employmentType' => $payload['EmploymentType'],
            'title' => $payload['JobTitle'],
            'name' => $payload['JobTitle'],
            'url' => $payload['JobDetailsURL'],
            'educationRequirements' => $payload['EducationRequired'],
            'experienceRequirements' => $payload['ExperienceRequired'],
            'sourceId' => $payload['DID'],
        ]);

        $pay = $this->parseSalariesFromString($payload['Pay']);

        $job->setOccupationalCategoryWithCodeAndTitle(
            $payload['OnetCode'],
            $payload['ONetFriendlyTitle']
        )->setCompany($payload['Company'])
            ->setCompanyUrl($payload['CompanyDetailsURL'])
            ->setLocation(
                $this->parseLocationElement($payload['City'])
                .', '.
                $this->parseLocationElement($payload['State'])
            )
            ->setCity($this->parseLocationElement($payload['City']))
            ->setState($this->parseLocationElement($payload['State']))
            ->setDatePostedAsString($payload['PostedDate'])
            ->setCompanyLogo($payload['CompanyImageURL'])
            ->setMinimumSalary($pay['min'])
            ->setMaximumSalary($pay['max']);

        if (isset($payload['Skills']['Skill'])) {
            $job->setSkills($this->parseSkillSet($payload['Skills']['Skill']));
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
            'Company',
            'CompanyDetailsURL',
            'DescriptionTeaser',
            'DID',
            'OnetCode',
            'ONetFriendlyTitle',
            'EmploymentType',
            'EducationRequired',
            'ExperienceRequired',
            'JobDetailsURL',
            'Location',
            'City',
            'State',
            'PostedDate',
            'Pay',
            'JobTitle',
            'CompanyImageURL',
            'Skills',
        ];
    }

    /**
     * Get data format
     *
     * @return string
     */
    public function getFormat()
    {
        return 'xml';
    }

    /**
     * Get listings path
     *
     * @return string
     */
    public function getListingsPath()
    {
        return 'Results.JobSearchResult';
    }

    /**
     * Get min and max salary numbers from string
     *
     * @return array
     */
    public function parseSalariesFromString($input = null)
    {
        $salary = [
            'min' => null,
            'max' => null
        ];
        $expressions = [
            'annualRange' => "/^.\d+k\s-\s.\d+k\/year$/",
            'annualFixed' => "/^.\d+k\/year$/",
            'hourlyRange' => "/^.\d+.\d+\s-\s.\d+.\d+\/hour$/",
            'hourlyFixed' => "/^.\d+.\d+\/hour$/",
        ];

        foreach ($expressions as $key => $expression) {
            if (preg_match($expression, $input)) {
                $method = 'parse'.$key;
                $salary = $this->$method($salary, $input);
            }
        }

        return $salary;
    }

    /**
     * Parse annual salary range from CB API
     *
     * @return array
     */
    protected function parseAnnualRange($salary = [], $input = null)
    {
        preg_replace_callback("/(.\d+k)\s.\s(.\d+k)/", function ($matches) use (&$salary) {
            $salary['min'] = str_replace('k', '000', $matches[1]);
            $salary['max'] = str_replace('k', '000', $matches[2]);
        }, $input);

        return $salary;
    }

    /**
     * Parse fixed annual salary from CB API
     *
     * @return array
     */
    protected function parseAnnualFixed($salary = [], $input = null)
    {
        preg_replace_callback("/(.\d+k)/", function ($matches) use (&$salary) {
            $salary['min'] = str_replace('k', '000', $matches[1]);
        }, $input);

        return $salary;
    }

    /**
     * Parse hourly payrate range from CB API
     *
     * @return array
     */
    protected function parseHourlyRange($salary = [], $input = null)
    {
        preg_replace_callback("/(.\d+.\d+)\s.\s(.\d+.\d+)/", function ($matches) use (&$salary) {
            $salary['min'] = $matches[1];
            $salary['max'] = $matches[2];
        }, $input);

        return $salary;
    }

    /**
     * Parse fixed hourly payrate from CB API
     *
     * @return array
     */
    protected function parseHourlyFixed($salary = [], $input = null)
    {
        preg_replace_callback("/(.\d+.\d+)/", function ($matches) use (&$salary) {
            $salary['min'] = $matches[1];
        }, $input);

        return $salary;
    }

    /**
     * Makes sure that city/state is a string
     *
     * @param $element mixed
     *
     * @return string|null
     */
    protected function parseLocationElement($element)
    {
        if (is_string($element)) {
            return $element;
        }
        return '';
    }

    /**
     * Parse skills array into string
     *
     * @return array
     */
    protected function parseSkillSet($skills)
    {
        if (is_array($skills)) {
            return implode(', ', $skills);
        } elseif (is_string($skills)) {
            return $skills;
        }
        return null;
    }
}
