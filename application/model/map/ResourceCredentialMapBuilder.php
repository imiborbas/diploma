<?php


/**
 * This class adds structure of 'resource_credential' table to 'propel' DatabaseMap object.
 *
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    .map
 */
class ResourceCredentialMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = '.map.ResourceCredentialMapBuilder';

	/**
	 * The database map.
	 */
	private $dbMap;

	/**
	 * Tells us if this DatabaseMapBuilder is built so that we
	 * don't have to re-build it every time.
	 *
	 * @return     boolean true if this DatabaseMapBuilder is built, false otherwise.
	 */
	public function isBuilt()
	{
		return ($this->dbMap !== null);
	}

	/**
	 * Gets the databasemap this map builder built.
	 *
	 * @return     the databasemap
	 */
	public function getDatabaseMap()
	{
		return $this->dbMap;
	}

	/**
	 * The doBuild() method builds the DatabaseMap
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function doBuild()
	{
		$this->dbMap = Propel::getDatabaseMap(ResourceCredentialPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(ResourceCredentialPeer::TABLE_NAME);
		$tMap->setPhpName('ResourceCredential');
		$tMap->setClassname('ResourceCredential');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addColumn('RESOURCE_CLASS', 'ResourceClass', 'VARCHAR', true, 128);

		$tMap->addColumn('RESOURCE_ID', 'ResourceId', 'INTEGER', true, null);

		$tMap->addForeignKey('CREDENTIAL_ID', 'CredentialId', 'INTEGER', 'credential', 'ID', false, null);

	} // doBuild()

} // ResourceCredentialMapBuilder
