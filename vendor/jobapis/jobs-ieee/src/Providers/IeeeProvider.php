<?php namespace JobApis\Jobs\Client\Providers;

use JobApis\Jobs\Client\Job;

class IeeeProvider extends AbstractProvider
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
            'title' => $payload['JobTitle'],
            'name' => $payload['JobTitle'],
            'description' => $payload['Description'],
            'url' => $payload['Url'],
            'maximumSalary' => $payload['SalaryMax'],
            'sourceId' => $payload['Id'],
            'alternateName' => $payload['NormalizedJobTitle'],
        ]);

        $job->setDatePostedAsString($payload['PostDate'])
            ->setMinimumSalary($payload['SalaryMin'])
            ->setLocation($payload['FormattedCityState'])
            ->setCompany($payload['Company'])
            ->setCompanyDescription($payload['CompanyProfileDescription'])
            ->setCompanyEmail($payload['ApplyEmail'])
            ->setCompanyLogo($payload['CompanyLogo'])
            ->setCompanyUrl($payload['CompanyProfileUrl'])
            ->setCountry($payload['Country'])
            ->setCity($payload['City'])
            ->setState($payload['State'])
            ->setPostalCode($payload['Zip']);

        $this->setEmploymentType($job, $payload['WorkStatus']);
        $this->setOccupationalCategory($job, $payload['CategoryDisplay']);

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

    protected function setEmploymentType(Job &$job, $typeArray = [])
    {
        if (is_array($typeArray) && !empty($typeArray)) {
            $job->setEmploymentType(implode(', ', $typeArray));
        }
        return $job;
    }

    protected function setOccupationalCategory(Job &$job, $typeArray = [])
    {
        if (is_array($typeArray) && !empty($typeArray)) {
            $job->setOccupationalCategory(implode(', ', $typeArray));
        }
        return $job;
    }
}
