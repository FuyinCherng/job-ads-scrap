<?php namespace JobApis\Jobs\Client\Queries;

class IeeeQuery extends AbstractQuery
{
    /**
     * keyword
     *
     * The search string.
     *
     * @var string
     */
    protected $keyword;

    /**
     * location
     *
     * The search location.
     *
     * @var string
     */
    protected $location;

    /**
     * rows
     *
     * Results per page. Should be one of the following:
     * - 15
     * - 25
     * - 50
     *
     * @var integer
     */
    protected $rows;

    /**
     * page
     *
     * @var integer
     */
    protected $page;

    /**
     * radius
     *
     * Miles away to search (up to 150).
     *
     * @var integer
     */
    protected $radius;

    /**
     * kwsJobTitleOnly
     *
     * Search keywords apply to job title only.
     *
     * @var boolean
     */
    protected $kwsJobTitleOnly;

    /**
     * category
     *
     * Array of categories to search (eg: `category[0]=computer_engineering`).
     *
     * @var array
     */
    protected $category;

    /**
     * sort
     *
     * Order and order by fields combined (eg: `sort=score+desc`).
     *
     * @var string
     */
    protected $sort;

    /**
     * region
     *
     * Valid options include:
     *  us_central
     *  us_e
     *  us_ne
     *  us_se
     *  us_sw
     *  us_w
     *  africa
     *  australia_and_oceania
     *  ca
     *  europe_e
     *  europe_w
     *  asia_e_and_se
     *  europe
     *  latin_america
     *  middle_e
     *  asia_sc
     *
     * @var string
     */
    protected $region;

    /**
     * SearchNetworks
     *
     * Networks to search (eg: `US`).
     *
     * @var string
     */
    protected $SearchNetworks;

    /**
     * networkView
     *
     * Network view (eg: `national`)
     *
     * @var string
     */
    protected $networkView;

    /**
     * format
     *
     * Format of results. Should default to `json`.
     *
     * @var string
     */
    protected $format;

    /**
     * Get baseUrl
     *
     * @return  string Value of the base url to this api
     */
    public function getBaseUrl()
    {
        return 'http://jobs.ieee.org/jobs/results';
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
     * Get url
     *
     * @return  string
     */
    public function getUrl()
    {
        return $this->getBaseUrl().'/keyword/'.$this->keyword.$this->getQueryString();
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
     * Required parameters
     *
     * @return array
     */
    protected function defaultAttributes()
    {
        return [
            'format' => 'json',
        ];
    }

    /**
     * Required parameters
     *
     * @return array
     */
    protected function requiredAttributes()
    {
        return [
            'keyword',
            'format',
        ];
    }
}
