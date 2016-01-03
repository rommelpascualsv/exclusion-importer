<?php namespace App\Import\OFAC\SDN;

class ProgramList extends Query {

	/**
	 * @var		string		$table
	 * @access	protected
	 */
	protected static $table = 'sdn_program_list';

	/**
	 * @var		int		$id
	 * @access	public
	 */
	public $id;

	/**
	 * @var		int		$sdn_entry_id
	 * @access	public
	 */
	public $sdn_entry_id;

	/**
	 * @var		int		$program
	 * @access	public
	 */
	public $program;

}