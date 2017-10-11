<?php namespace JobApis\Jobs\Client\Queries;

class GithubQuery extends AbstractQuery
{
    /**
     * A search term, such as "ruby" or "java". Aliases to "search"
     *
     * @var string
     */
    protected $description;

    /**
     * A search term, such as "ruby" or "java".
     *
     * @var string
     */
    protected $search;

    /**
     * A city name, zip code, or other location search term.
     *
     * @var string
     */
    protected $location;

    /**
     * A specific latitude. If used, you must also send long and must not send location.
     *
     * @var string
     */
    protected $lat;

    /**
     * A specific longitude. If used, you must also send lat and must not send location.
     *
     * @var string
     */
    protected $long;

    /**
     * If you want to limit results to full time positions set this parameter to 'true'.
     *
     * @var string
     */
    protected $full_time;

    /**
     * Page number to return.
     *
     * @var string
     */
    protected $page;

    /**
     * Get baseUrl
     *
     * @return  string Value of the base url to this api
     */
    public function getBaseUrl()
    {
        return 'https://jobs.github.com/positions.json';
    }

    /**
     * Get keyword
     *
     * @return  string Attribute being used as the search keyword
     */
    public function getKeyword()
    {
        return $this->search;
    }

    /**
     * Get description (alias for search)
     *
     * @return  string Attribute being used as the search keyword
     */
    public function getDescription()
    {
        return $this->search;
    }

    /**
     * Set description (alias for search)
     *
     * @return  string Attribute being used as the search keyword
     */
    public function setDescription($value = null)
    {
        $this->search = $value;
        return $this;
    }
}
