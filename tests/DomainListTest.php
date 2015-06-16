<?php
use timgws\DomainList;

class DomainListTest extends PHPUnit_Framework_TestCase
{
    /**
     * Nothing crazy, just a simple test to make sure that the class inits
     */
    public function testInitCleanHTML()
    {
        new DomainList();
    }

    /**
     * Add a domain name.
     */
    public function testAddDomain()
    {
        $list = new DomainList('.ing');
        $list->add('laugh');

        $domains = $list->get();
        $this->assertEquals(array('laugh.ing'), $domains);
    }

    /**
     * Add a domain name.
     */
    public function testAddTwoDomains()
    {
        $list = new DomainList('ing');
        $list->add([
            'laugh', 'cry'
        ]);

        $domains = $list->get();
        $this->assertEquals(array('laugh.ing', 'cry.ing'), $domains);
    }

    /**
     * Add an invalid domain name in an array...
     *
     * @expectedException timgws\DomainListException
     */
    public function testAddInvalidDomainInArray()
    {
        $list = new DomainList('ing');
        $list->add(['-invalid']);
    }

    /**
     * Add an invalid domain name...
     *
     * @expectedException timgws\DomainListException
     */
    public function testAddInvalidDomain()
    {
        $list = new DomainList('ing');
        $list->add('-invalid');
    }

    /**
     * Test calling clear second time returns empty array
     */
    public function testGetAndClear()
    {
        $list = new DomainList('ing');
        $list->add([
            'laugh', 'cry'
        ]);

        $domains = $list->clear();
        $this->assertEquals(array('laugh.ing', 'cry.ing'), $domains);

        $domains = $list->clear();
        $this->assertEmpty($domains);
    }

    public function testEuroString()
    {
        $list = new DomainList('ing');

        $list->add([
            $list->c('the € shop')
        ]);

        $this->assertEquals(
            ['the-eur-shop.ing', 'theeurshop.ing'], $list->get()
        );
    }

    public function testNoDoubleSpacesOrHyphens()
    {
        $list = new DomainList('ing');

        $list->add([
            $list->c('the awesome   € shop'),
            $list->c('hex-follows-0xaf--0xfacebeef'),
            $list->c('--lol-0xfacebeef')
        ]);

        $domains = array_values($list->get());

        $domainsTest = [
            'hex-follows-0xaf-0xfacebeef.ing',
            'hexfollows0xaf0xfacebeef.ing',
            'the-awesome-eur-shop.ing',
            'theawesomeeurshop.ing'
        ];

        $this->assertEquals($domains, $domainsTest);
    }

    /**
     */
    public function testDomainCanNotStartWithNumber()
    {
        $list = new DomainList('ing');
        $list->add('a');
    }

    public function testAddingStrangeDomain()
    {
        $list = new DomainList('ing');
        $list->add('a-');
    }
}