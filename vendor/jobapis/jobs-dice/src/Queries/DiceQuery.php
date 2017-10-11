<?php namespace JobApis\Jobs\Client\Queries;

class DiceQuery extends AbstractQuery
{
    /**
     * Direct hire jobs only
     *
     * @var boolean
     */
    protected $direct;

    /**
     * Jobs' area code
     *
     * @var string
     */
    protected $areacode;

    /**
     * Country
     *
     * @var string
     */
    protected $country;

    /**
     * State
     *
     * @var string
     */
    protected $state;

    /**
     * Skill to search for
     *
     * @var string
     */
    protected $skill;

    /**
     * City
     *
     * @var string
     */
    protected $city;

    /**
     * Search query string
     *
     * @var string
     */
    protected $text;

    /**
     * IP address that will be used to look up a geocode
     *
     * @var string
     */
    protected $ip;

    /**
     * Posting age in days
     *
     * @var string
     */
    protected $age;

    /**
     * Specific Dice user ID who posted the job
     *
     * @var string
     */
    protected $diceid;

    /**
     * Page number of results to display
     *
     * @var integer
     */
    protected $page;

    /**
     * Results per page
     *
     * @var integer
     */
    protected $pgcnt;

    /**
     * Sort parameter:
     *  sort=1 sorts by posted age
     *  sort=2 sorts by job title
     *  sort=3 sorts by company
     *  sort=4 sorts by location
     *
     * @var integer
     */
    protected $sort;

    /**
     * Sort direction:
     *  sd=a sort order is ASCENDING
     *  sd=d sort order is DESCENDING
     *
     * @var string
     */
    protected $sd;

    /**
     * Get baseUrl
     *
     * @return  string Value of the base url to this api
     */
    public function getBaseUrl()
    {
        return 'http://service.dice.com/api/rest/jobsearch/v1/simple.json';
    }

    /**
     * Get keyword
     *
     * @return  string Attribute being used as the search keyword
     */
    public function getKeyword()
    {
        return $this->text;
    }
}
