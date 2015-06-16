<?php

namespace timgws;

/**
 * Class DomainList.
 *
 * A list of domain names, automatically adds the TLD to the domain.
 */
class DomainList
{
    /**
     * @var array
     */
    private $list = [];

    /**
     * @var string the TLD
     */
    private $tld = '.example';

    /**
     * @param $tld the TLD that we want to use.
     */
    public function __construct($tld = null)
    {
        if (!is_null($tld)) {
            $this->setTLD($tld);
        }
    }

    /**
     * Set the TLD that will be added to domains.
     *
     * @param $tld
     */
    public function setTLD($tld)
    {
        if (strpos($tld, '.') !== 0) {
            $this->tld = '.'.$tld;
        } else {
            $this->tld = $tld;
        }
    }

    /**
     * Add a domain name to the list of domains.
     *
     * @param $domain_name
     */
    public function add($domain_name)
    {
        if (is_array($domain_name)) {
            foreach ($domain_name as $_item) {
                $this->add($_item);
            }

            return;
        }

        $this->list[] = $domain_name.$this->tld;
    }

    /**
     * Get a list of all the domains that have been added.
     *
     * @return array
     */
    public function get()
    {
        $list = $this->list;
        asort($list);

        return array_unique($list);
    }

    /**
     * Get a list of all the domains that have been added, and clear the internal array.
     *
     * @return array
     */
    public function getAndClear()
    {
        $list = $this->get();
        $this->list = [];

        return $list;
    }

    /**
     * Clear the list!
     *
     * @return array
     */
    public function clear()
    {
        return $this->getAndClear();
    }
}
