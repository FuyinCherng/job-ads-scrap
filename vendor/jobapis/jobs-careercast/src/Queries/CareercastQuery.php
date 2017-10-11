<?php namespace JobApis\Jobs\Client\Queries;

class CareercastQuery extends AbstractQuery
{
    /**
     * Search keyword
     *
     * @var string
     */
    protected $keyword;

    /**
     * Results per page
     *
     * @var integer
     */
    protected $rows;

    /**
     * Page number
     *
     * @var integer
     */
    protected $page;

    /**
     * Radius from location (in miles).
     *
     * @var integer
     */
    protected $radius;

    /**
     * Job title (must match one of Careercast's standard titles)
     *
     * @var string
     */
    protected $normalizedJobTitle;

    /**
     * Job category (must match one of Careercast's standard categories)
     *
     * @var string
     */
    protected $category;

    /**
     * Company name
     *
     * @var string
     */
    protected $company;

    /**
     * Job source
     *
     * @var string
     */
    protected $jobSource;

    /**
     * Date the job was posted
     *
     * @var string
     */
    protected $postDate;

    /**
     * Results format ("json" or "rss")
     *
     * @var string
     */
    protected $format;

    /**
     * Work status
     *
     * @var string
     */
    protected $workStatus;

    /**
     * Location
     *
     * @var string
     */
    protected $location;

    /**
     * Only look for keywords in title
     *
     * @var string
     */
    protected $kwsJobTitleOnly;

    /**
     * Sort by. Options:
     *
     * PostDate
     * score
     * geo_distance
     * JobTitle
     * Company
     * IsFeatured
     * scorelocation
     * Priority
     *
     * Can be followed with " desc" or " asc"
     *
     * Can also use multiple sort options by comma delimiting them.
     *
     * @var string
     */
    protected $sort;

    /**
     * Get baseUrl
     *
     * @return  string Value of the base url to this api
     */
    public function getBaseUrl()
    {
        return 'http://www.careercast.com/jobs/results/keyword/';
    }

    /**
     * Get keyword
     *
     * @return  string Attribute being used as the search keyword
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Get query string for client based on properties
     *
     * @return string
     */
    public function getQueryString()
    {
        $keyword = urlencode($this->keyword);

        return $keyword.'?'.http_build_query($this->getQueryAttributes());
    }

    /**
     * Default parameters
     *
     * @var array
     */
    protected function defaultAttributes()
    {
        return [
            'format' => 'json',
        ];
    }

    /**
     * Gets the attributes to use for this API's query
     *
     * @var array
     */
    protected function getQueryAttributes()
    {
        $attributes = get_object_vars($this);
        unset($attributes['keyword']);
        return $attributes;
    }

    /**
     * Required attributes for the query
     *
     * @var array
     */
    protected function requiredAttributes()
    {
        return [
            'keyword',
        ];
    }
}
