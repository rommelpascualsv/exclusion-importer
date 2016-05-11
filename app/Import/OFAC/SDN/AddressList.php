<?php namespace App\Import\OFAC\SDN;

class AddressList extends Query
{
    /**
     * @var string $table
     * @access  protected
     */
    protected static $table = 'sdn_address_list';

    /**
     * @var int $id
     * @access  public
     */
    public $id;

    /**
     * @var int $sdn_entry_id
     * @access  public
     */
    public $sdn_entry_id;

    /**
     * @var int $uid
     * @access  public
     */
    public $uid;

    /**
     * @var string $address1
     * @access public
     */
    public $address1;

    /**
     * @var string $address2
     * @access  public
     */
    public $address2;

    /**
     * @var string $address3
     * @access  public
     */
    public $address3;

    /**
     * @var string $city
     * @access  public
     */
    public $city;

    /**
     * @var string $stateOrProvince
     * @access  public
     */
    public $stateOrProvince;

    /**
     * @var string $postalCode
     * @access  public
     */
    public $postalcode;

    /**
     * @var string $country
     * @access  public
     */
    public $country;
}
