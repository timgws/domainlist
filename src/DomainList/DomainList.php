<?php

namespace timgws;

use timgws\DomainListException;

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
     * @throws DomainListException
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

        if ($this->validDomain($domain_name)) {
            $this->list[] = $domain_name . $this->tld;
        }
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

    /**
     * Convert text from UTF-8 to a plain string...
     *
     * @param $string
     * @return array
     */
    public function convertText($string)
    {
        $return = [];

        try {
            $translit = $this->translit($string);

            $return[] = $translit;
            $return[] = str_replace('-', '', $translit);
        } catch (DomainListException $e) {
            /**
             * @TODO I should maybe do something with this?
             *
             * I don't really need it for my project. Sorry guys :)
             */
        }

        return $return;
    }

    /**
     * Shortcut to the `convertText` method.
     *
     * @param $string
     * @return array
     */
    public function c($string)
    {
        return $this->convertText($string);
    }

    /**
     * Remove any UTF-8 characters.
     *
     * @param $string
     * @return string
     */
    private function iconv($string)
    {
        return iconv("UTF-8", "ISO-8859-1//TRANSLIT", $string);
    }

    /**
     * Remove any UTF-8 characters.
     *
     * @param $string
     * @throws DomainListException
     * @return string
     */
    public function translit($string)
    {
        $transliterated = $this->iconv($string);

        /**
         * Domain names should be lower case.
         *
         * @see https://tools.ietf.org/html/rfc1035#section-2.3.3
         */
        $lowercase = strtolower($transliterated);

        /**
         * We only want a-z, hyphens and spaces.
         * We need to remove duplicate spaces and hyphens.
         *
         * Numbers are allowed, but who wants them?
         *
         * @see https://tools.ietf.org/html/rfc1035#section-2.3.4
         */
        $string = preg_replace('|[^0-9a-z\-\s]*|', '', $lowercase);
        $string = preg_replace(array('|\s\s*|', '|\-\-*|'), '-', $string);

        /**
         * Make sure that a domain name starts with a letter, and ends with a letter/digit.
         *
         * @see https://tools.ietf.org/html/rfc1035#section-2.3.1
         */
        if (!preg_match('|^[a-z]([a-z0-9\-]*)[0-9a-z]$|', $string)) {
            throw DomainListException();
        }

        return $string;
    }

    /**
     * Make sure that a domain name starts with a letter, and ends with a letter/digit.
     *
     * @param string $string domain name
     * @throws DomainListException
     * @return bool if domain name is valid
     * @see https://tools.ietf.org/html/rfc1035#section-2.3.1
     */
    private function validDomain($string)
    {
        if (!preg_match('|^[a-z]([a-z0-9\-]{1,63})?[0-9a-z]?$|', $string)) {
            throw new DomainListException();
        }

        return true;
    }
}
