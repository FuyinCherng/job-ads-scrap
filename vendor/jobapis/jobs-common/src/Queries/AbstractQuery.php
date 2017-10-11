<?php namespace JobApis\Jobs\Client\Queries;

abstract class AbstractQuery
{
    /**
     * Create new query from parameters and defaults
     *
     * @param array $parameters
     */
    public function __construct($parameters = [])
    {
        $parameters = array_merge($this->defaultAttributes(), $parameters);

        foreach ($parameters as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Get baseUrl
     *
     * @return  string Value of the base url to this api
     */
    abstract public function getBaseUrl();

    /**
     * Get keyword
     *
     * @return  string Attribute being used as the search keyword
     */
    abstract public function getKeyword();

    /**
     * Attempts to get an attribute by key
     *
     * @param  string  $key
     *
     * @return AbstractQuery
     */
    public function get($key)
    {
        if (!$this->isValidParameter($key)) {
            throw new \OutOfRangeException(sprintf(
                '%s does not contain a property by the name of "%s"',
                __CLASS__,
                $key
            ));
        }
        if (method_exists($this, 'get'.self::toStudlyCase($key))) {
            return $this->{'get'.self::toStudlyCase($key)}();
        }
        return $this->$key;
    }

    /**
     * Get http method options based on current client. Good for adding POST parameters.
     *
     * @return array
     */
    public function getHttpMethodOptions()
    {
        return [];
    }

    /**
     * Get query string for client based on properties
     *
     * @return string
     */
    public function getQueryString()
    {
        return '?'.http_build_query($this->getQueryAttributes());
    }

    /**
     * Get url
     *
     * @return  string
     */
    public function getUrl()
    {
        return $this->getBaseUrl().$this->getQueryString();
    }

    /**
     * Get http verb to use when making request
     *
     * @return  string
     */
    public function getVerb()
    {
        return 'GET';
    }

    /**
     * Attempts to update attribute with key/value pair
     *
     * @param  string  $key
     * @param  string  $value
     *
     * @return AbstractQuery
     */
    public function set($key, $value)
    {
        // Then check to see if there's a query parameter
        if (!$this->isValidParameter($key)) {
            throw new \OutOfRangeException(sprintf(
                '%s does not contain a property by the name of "%s"',
                __CLASS__,
                $key
            ));
        }
        if (method_exists($this, 'set'.self::toStudlyCase($key))) {
            return $this->{'set'.self::toStudlyCase($key)}($value);
        }
        $this->$key = $value;
        return $this;
    }

    /**
     * Determines whether the query is valid and ready to use
     *
     * @return bool Validity of the Query
     */
    public function isValid()
    {
        foreach ($this->requiredAttributes() as $key) {
            if (!isset($this->$key)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Gets the attributes to use for this API's query
     *
     * @var array
     */
    protected function getQueryAttributes()
    {
        return get_object_vars($this);
    }

    /**
     * Default parameters
     *
     * @var array
     */
    protected function defaultAttributes()
    {
        return [];
    }

    /**
     * Attempts to get an attribute by key
     *
     * @param  string  $key
     *
     * @return bool
     */
    protected function isValidParameter($key)
    {
        if (property_exists($this, $key)) {
            return true;
        }
        return false;
    }

    /**
     * Required attributes for the query
     *
     * @var array
     */
    protected function requiredAttributes()
    {
        return [];
    }

    /**
     * Converts snake case or underscores to camelcase
     *
     * @param string
     *
     * @return string
     */
    protected static function toStudlyCase($value)
    {
        return str_replace(' ', '', ucwords(str_replace(array('-', '_'), ' ', $value)));
    }
}
