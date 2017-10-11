<?php namespace JobApis\Jobs\Client;

use JobApis\Jobs\Client\Providers\AbstractProvider;
use JobApis\Jobs\Client\Queries\AbstractQuery;

class JobsMulti
{
    /**
     * Search keyword
     *
     * @var string
     */
    protected $keyword;

    /**
     * Search location
     *
     * @var string
     */
    protected $location;

    /**
     * Maximum age of results (in days)
     *
     * @var integer
     */
    protected $maxAge;

    /**
     * Maximum number of results to return in all results
     *
     * @var integer
     */
    protected $maxResults;

    /**
     * Order of results
     *
     * @var string
     */
    protected $order;

    /**
     * Field to order results by
     *
     * @var string
     */
    protected $orderBy;

    /**
     * Results page number
     *
     * @var integer
     */
    protected $pageNumber;

    /**
     * Results per page
     *
     * @var integer
     */
    protected $perPage;

    /**
     * Job board API providers
     *
     * @var array
     */
    protected $providers = [];

    /**
     * Job board API query objects
     *
     * @var array
     */
    protected $queries = [];

    /**
     * Creates query objects for each provider and creates this unified client.
     *
     * @param array $providers
     */
    public function __construct($providers = [])
    {
        $this->setProviders($providers);
    }

    /**
     * Gets jobs from all providers in a single go and returns a MultiCollection
     *
     * @return Collection
     */
    public function getAllJobs($options = [])
    {
        // Set options that are passed in
        $this->setOptions($options);

        // Create a new Collection
        $collection = new Collection();
        foreach ($this->providers as $providerName => $options) {
            $collection->addCollection($this->getJobsByProvider($providerName));
        }

        // Apply sorting and ordering options and return the collection
        return $this->applyOptions($collection);
    }

    /**
     * Gets jobs from a single provider and hydrates a new jobs collection.
     *
     * @var $name string Provider name.
     *
     * @return \JobApis\Jobs\Client\Collection
     */
    public function getJobsByProvider($name = null, $options = [])
    {
        // Set options that are passed in
        $this->setOptions($options);

        try {
            // Instantiate the query with all our parameters
            $query = $this->instantiateQuery($name);

            // Instantiate the provider
            $provider = $this->instantiateProvider($name, $query);

            // Apply sorting and ordering options and return the collection
            return $this->applyOptions($provider->getJobs());
        } catch (\Exception $e) {
            return (new Collection())->addError($e->getMessage());
        }
    }

    /**
     * Sets a keyword on the query.
     *
     * @param $keyword string
     *
     * @return $this
     */
    public function setKeyword($keyword = null)
    {
        $this->keyword = $keyword;

        return $this;
    }

    /**
     * Sets a location on the query for each provider.
     *
     * @param $location
     *
     * @return $this
     */
    public function setLocation($location = null)
    {
        if (!$this->isValidLocation($location)) {
            throw new \OutOfBoundsException("Location parameter must follow the pattern 'City, ST'.");
        }
        $this->location = $location;

        return $this;
    }

    /**
     * Sets the options used for the resulting collection
     *
     * @param array $options
     *
     * @return $this
     */
    public function setOptions($options = [])
    {
        if (isset($options['maxAge'])) {
            $this->maxAge = $options['maxAge'];
        }
        if (isset($options['maxResults'])) {
            $this->maxResults = $options['maxResults'];
        }
        if (isset($options['order'])) {
            $this->order = $options['order'];
        }
        if (isset($options['orderBy'])) {
            $this->orderBy = $options['orderBy'];
        }

        return $this;
    }

    /**
     * Sets a page number and number of results per page for each provider.
     *
     * @param $pageNumber integer
     * @param $perPage integer
     *
     * @return $this
     */
    public function setPage($pageNumber = 1, $perPage = 10)
    {
        $this->pageNumber = $pageNumber;
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * Sets an array of providers.
     *
     * @param $providers array
     *
     * @return $this
     */
    public function setProviders($providers = [])
    {
        $this->providers = $providers;

        return $this;
    }

    /**
     * Apply the options for this JobsMulti object to the Collection
     *
     * @param Collection $collection
     *
     * @return Collection
     */
    protected function applyOptions(Collection $collection)
    {
        // Order the results
        if ($this->orderBy && $this->order) {
            $collection->orderBy($this->orderBy, $this->order);
        }

        // Filter older listings out
        if ($this->maxAge) {
            $collection->filter(
                'datePosted',
                new \DateTime($this->maxAge.' days ago'),
                '>'
            );
        }

        // Truncate to the maximum results (all by default)
        if ($this->maxResults) {
            $collection->truncate($this->maxResults);
        }

        return $collection;
    }

    /**
     * Gets an array of options from a translator array
     *
     * @param array $translator
     *
     * @return array
     */
    protected function getOptionsFromTranslator($translator = [])
    {
        $options = [];
        foreach ($translator as $standardKey => $providerKey) {
            if (method_exists($this, $providerKey)) {
                $options = array_merge(
                    $options,
                    $this->$providerKey($this->{$standardKey})
                );
            } else {
                $options[$providerKey] = $this->{$standardKey};
            }
        }
        return $options;
    }

    /**
     * Gets the options array based on the provider name.
     *
     * @param $name
     *
     * @return array
     */
    protected function getTranslatorForProvider($name)
    {
        switch ($name) {
            case 'Careerbuilder':
                return [
                    'keyword' => 'Keywords',
                    'location' => 'Location',
                    'pageNumber' => 'PageNumber',
                    'perPage' => 'PerPage',
                ];
                break;
            case 'Careercast':
                return [
                    'keyword' => 'keyword',
                    'location' => 'location',
                    'pageNumber' => 'page',
                    'perPage' => 'rows',
                ];
                break;
            case 'Careerjet':
                return [
                    'keyword' => 'keywords',
                    'location' => 'location',
                    'pageNumber' => 'page',
                    'perPage' => 'pagesize',
                ];
                break;
            case 'Dice':
                return [
                    'keyword' => 'text',
                    'location' => 'getCityAndState',
                    'pageNumber' => 'page',
                    'perPage' => 'pgcnt',
                ];
                break;
            case 'Github':
                return [
                    'keyword' => 'search',
                    'location' => 'location',
                    'pageNumber' => 'getPageMinusOne',
                ];
                break;
            case 'Govt':
                return [
                    'keyword' => 'getQueryWithKeywordAndLocation',
                    'pageNumber' => 'getFrom',
                    'perPage' => 'size',
                ];
                break;
            case 'Ieee':
                return [
                    'keyword' => 'keyword',
                    'location' => 'location',
                    'pageNumber' => 'page',
                    'perPage' => 'rows',
                ];
                break;
            case 'Indeed':
                return [
                    'keyword' => 'q',
                    'location' => 'l',
                    'pageNumber' => 'getStart',
                    'perPage' => 'limit',
                ];
                break;
            case 'Jobinventory':
                return [
                    'keyword' => 'q',
                    'location' => 'l',
                    'pageNumber' => 'getStart',
                    'perPage' => 'limit',
                ];
                break;
            case 'J2c':
                return [
                    'keyword' => 'q',
                    'location' => 'l',
                    'pageNumber' => 'start',
                    'perPage' => 'limit',
                ];
                break;
            case 'Juju':
                return [
                    'keyword' => 'k',
                    'location' => 'l',
                    'pageNumber' => 'page',
                    'perPage' => 'jpp',
                ];
                break;
            case 'Monster':
                return [
                    'keyword' => 'q',
                    'location' => 'where',
                    'pageNumber' => 'page',
                ];
                break;
            case 'Stackoverflow':
                return [
                    'keyword' => 'q',
                    'location' => 'l',
                    'pageNumber' => 'pg',
                ];
                break;
            case 'Usajobs':
                return [
                    'keyword' => 'Keyword',
                    'location' => 'LocationName',
                    'pageNumber' => 'Page',
                    'perPage' => 'ResultsPerPage',
                ];
                break;
            case 'Ziprecruiter':
                return [
                    'keyword' => 'search',
                    'location' => 'location',
                    'pageNumber' => 'page',
                    'perPage' => 'jobs_per_page',
                ];
                break;
            default:
                throw new \Exception("Provider {$name} not found");
        }
    }

    /**
     * Instantiates a provider using a query object.
     *
     * @param null $name
     * @param AbstractQuery $query
     *
     * @return AbstractProvider
     */
    protected function instantiateProvider($name, AbstractQuery $query)
    {
        $path = 'JobApis\\Jobs\\Client\\Providers\\' . $name . 'Provider';

        return new $path($query);
    }

    /**
     * Instantiates a query using a client name.
     *
     * @param null $name
     *
     * @return AbstractQuery
     */
    protected function instantiateQuery($name)
    {
        $path = 'JobApis\\Jobs\\Client\\Queries\\' . $name . 'Query';

        $options = array_merge(
            $this->providers[$name],
            $this->getOptionsFromTranslator($this->getTranslatorForProvider($name))
        );

        return new $path($options);
    }

    /**
     * Get the city and state as an array from a location string.
     *
     * @return array
     */
    private function getCityAndState()
    {
        if ($this->location) {
            $locationArr = explode(', ', $this->location);
            return [
                'city' => $locationArr[0],
                'state' => $locationArr[1],
            ];
        }
        return [];
    }

    /**
     * Gets a from value.
     *
     * @return array
     */
    private function getFrom()
    {
        if ($this->pageNumber && $this->perPage) {
            return [
                'from' => ($this->pageNumber * $this->perPage) - $this->perPage,
            ];
        }
        return [];
    }

    /**
     * Gets page number minus 1.
     *
     * @return array
     */
    private function getPageMinusOne()
    {
        if ($this->pageNumber) {
            return [
                'page' => $this->pageNumber - 1,
            ];
        }
        return [];
    }

    /**
     * Get the query with keyword and location.
     *
     * @return array
     */
    private function getQueryWithKeywordAndLocation()
    {
        $queryString = $this->keyword;

        if ($this->location) {
            $queryString .= ' in '.$this->location;
        }

        return [
            'query' => $queryString,
        ];
    }

    /**
     * Gets a start at value.
     *
     * @return array
     */
    private function getStart()
    {
        if ($this->pageNumber && $this->perPage) {
            return [
                'start' => ($this->pageNumber * $this->perPage) - $this->perPage,
            ];
        }
        return [];
    }

    /**
     * Tests whether location string follows valid convention (City, ST).
     *
     * @param string $location
     *
     * @return bool
     */
    private function isValidLocation($location = null)
    {
        preg_match("/([^,]+),\s*(\w{2})/", $location, $matches);
        return isset($matches[1]) && isset($matches[2]) ? true : false;
    }
}
