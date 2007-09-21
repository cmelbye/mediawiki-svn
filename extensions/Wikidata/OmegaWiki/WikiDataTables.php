<?php

/**
 * This unit is meant to provide a database abstraction layer. The main purposes of this layer are:
 * 1) To centralize table identifiers and table column identifiers 
 * 2) To provide meta data on the wikidata tables we use in the (MySQL) database (possibly for creating the tables through CREATE TABLE)
 * 3) To ease querying of the wikidata tables making use of the meta data
 * 4) To ease querying of the wikidata tables by using PHP functions and types instead of plain SQL
 * 5) To hide some frequently used constructions in queries like getting the latest version of a record (remove_transaction_id IS NULL)
 * 6) To provide a starting point for a generic structure that queries these tables into Records and RecordSets
 * 
 * The basic components of this abstraction Layer are:
 * 1) DatabaseExpression: a general interface that can turn PHP data into an SQL expression
 * 2) TableColumn: represents a specific column in a specific table
 * 3) Table: represents a specific table in the database, meant as a base class for specific tables        
 */

interface DatabaseExpression {
	public function toExpression();
}

class DefaultDatabaseExpression implements DatabaseExpression {
	protected $sql;
	
	public function __construct($sql) {
		$this->sql = $sql;
	} 
	
	public function toExpression() {
		return $this->sql;
	}
	
	public function __toString() {
		return $this->sql;
	}
}

class SelectExpression implements DatabaseExpression {
	protected $selectSQL;
	
	public function __construct($selectSQL) {
		$this->selectSQL = $selectSQL;
	} 
	
	public function toExpression() {
		return $this->selectSQL;
	}
	
	public function __toString() {
		return $this->selectSQL;
	}
}

class TableColumn implements DatabaseExpression {
	public $table;
	public $identifier;
	protected $length;
	
	public function __construct($table, $identifier, $length = null) {
		$this->table = $table;		
		$this->identifier = $identifier;
		$this->length = $length;
	}
	
	public function getIdentifier() {
		return $this->identifier;
	}
	
	public function qualifiedName() {
		return $this->table->getIdentifier() . '.' . $this->identifier;
	}
	
	public function toExpression() {
		return $this->qualifiedName();
	}
	
	public function subPart($length) {
		return new TableColumn($this->table, $this->identifier, $length);
	}
	
	public function getLength() {
		return $this->length;
	}
}

class Table {
	public $identifier;
	public $isVersioned;
	public $keyColumns;
	public $columns;
	
	protected $webSiteIndexes;
	protected $massUpdateIndexes;
	
	public function __construct($identifier, $isVersioned) {
		$this->identifier = $identifier;
		$this->isVersioned = $isVersioned;
		$this->columns = array();
		
		$this->webSiteIndexes = array();
		$this->massUpdateIndexes = array();
	}

	public function getIdentifier() {
		return $this->identifier;
	}	
	
	protected function createColumn($identifier) {
		$result = new TableColumn($this, $identifier);
		$this->columns[] = $result;
		
		return $result;
	}
	
	protected function setKeyColumns(array $keyColumns) {
		$this->keyColumns = $keyColumns;
	}
	
	protected function setWebSiteIndexes(array $webSiteIndexes) {
		$this->webSiteIndexes = $webSiteIndexes;
	}
	
	public function getWebSiteIndexes() {
		return $this->webSiteIndexes;
	}
	
	protected function setMassUpdateIndexes(array $massUpdateIndexes) {
		$this->massUpdateIndexes = $massUpdateIndexes;
	}
	
	public function getMassUpdateIndexes() {
		return $this->massUpdateIndexes;
	}
	
	public function getIndexes($purpose) {
		if ($purpose == "WebSite")
			return $this->getWebSiteIndexes();
		else if ($purpose == "MassUpdate")
			return $this->getMassUpdateIndexes();
		else
			return null;
	}
}

class TableIndex {
	protected $name;
	protected $columns;
	
	public function __construct($name, array $columns) {
		$this->name = $name;
		$this->columns = $columns;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getColumns() {
		return $this->columns;
	}
}

class VersionedTable extends Table {
	public $addTransactionId;
	public $removeTransactionId;
	
	public function __construct($identifier) {
		parent::__construct($identifier, true);
		
		$this->addTransactionId = $this->createColumn("add_transaction_id");
		$this->removeTransactionId = $this->createColumn("remove_transaction_id");
	}
}

class BootstrappedDefinedMeaningsTable extends Table {
	public $name;
	public $definedMeaningId;
	
	public function __construct($identifier) {
		parent::__construct($identifier, false);
		
		$this->name = $this->createColumn("name");
		$this->definedMeaningId = $this->createColumn("defined_meaning_id");
		
		$this->setKeyColumns(array($this->name));	
	}
}

class TransactionsTable extends Table {
	public $transactionId;
	public $userId;
	public $userIp;
	public $timestamp;
	public $comment;
	
	public function __construct($identifier) {
		parent::__construct($identifier, false);
		
		$this->transactionId = $this->createColumn("transaction_id"); 	
		$this->userId = $this->createColumn("user_id"); 	
		$this->userIp = $this->createColumn("user_ip"); 	
		$this->timestamp = $this->createColumn("timestamp"); 	
		$this->comment = $this->createColumn("comment");
		
		$this->setKeyColumns(array($this->transactionId));	
	}
}

class DefinedMeaningTable extends VersionedTable {
	public $definedMeaningId;
	public $expressionId;
	public $meaningTextTcid;
	
	public function __construct($identifier) {
		parent::__construct($identifier);
		
		$this->definedMeaningId = $this->createColumn("defined_meaning_id");
		$this->expressionId = $this->createColumn("expression_id");
		$this->meaningTextTcid = $this->createColumn("meaning_text_tcid");
		
		$this->setKeyColumns(array($this->definedMeaningId));	
	}
}

class AlternativeDefinitionsTable extends VersionedTable {
	public $meaningMid;
	public $meaningTextTcid;
	public $sourceId;

	public function __construct($identifier) {
		parent::__construct($identifier);
		
		$this->meaningMid = $this->createColumn("meaning_mid");
		$this->meaningTextTcid = $this->createColumn("meaning_text_tcid");
		$this->sourceId = $this->createColumn("source_id");
		
		$this->setKeyColumns(array($this->meaningMid, $this->meaningTextTcid));	
	}
}

class ExpressionTable extends VersionedTable {
	public $expressionId;
	public $spelling;	
	public $languageId;
	
	public function __construct($name) {
		parent::__construct($name);
		
		$this->expressionId = $this->createColumn("expression_id");
		$this->spelling = $this->createColumn("spelling");
		$this->languageId = $this->createColumn("language_id");
		
		$this->setKeyColumns(array($this->expressionId));
		
		$this->setWebSiteIndexes(array(
			new TableIndex("versioned_end_expression", array(
				$this->removeTransactionId,
				$this->expressionId, 
				$this->languageId
			)),
			new TableIndex("versioned_end_language", array(
				$this->removeTransactionId, 
				$this->languageId, 
				$this->expressionId
			)),
			new TableIndex("versioned_end_spelling", array(
				$this->removeTransactionId, 
				$this->spelling->subPart(255), 
				$this->expressionId, 
				$this->languageId
			)),
			new TableIndex("versioned_start_expression", array(
				$this->addTransactionId, 
				$this->expressionId, 
				$this->languageId
			)),
			new TableIndex("versioned_start_language", array(
				$this->addTransactionId, 
				$this->languageId, 
				$this->expressionId
			)),
			new TableIndex("versioned_start_spelling", array(
				$this->addTransactionId, 
				$this->spelling->subPart(255), 
				$this->expressionId, 
				$this->languageId
			)),
			new TableIndex("expressions_unique_idx", array(
				$this->expressionId, 
				$this->languageId
			))
		));	
	}
}

class ClassAttributesTable extends VersionedTable {
	public $objectId;
	public $classMid;	
	public $levelMid;
	public $attributeMid;
	public $attributeType;
	
	public function __construct($name) {
		parent::__construct($name);
	
		$this->objectId = $this->createColumn("object_id");
		$this->classMid = $this->createColumn("class_mid");
		$this->levelMid = $this->createColumn("level_mid"); 	
		$this->attributeMid = $this->createColumn("attribute_mid"); 	
		$this->attributeType = $this->createColumn("attribute_type");
		
		$this->setKeyColumns(array($this->objectId));	
	}
}

class ClassMembershipsTable extends VersionedTable {
	public $classMembershipId;
	public $classMid;	
	public $classMemberMid;
	
	public function __construct($name) {
		parent::__construct($name);
		
		$this->classMembershipId = $this->createColumn("class_membership_id"); 	
		$this->classMid = $this->createColumn("class_mid"); 	
		$this->classMemberMid = $this->createColumn("class_member_mid");
		
		$this->setKeyColumns(array($this->classMembershipId));	
	}
}

class CollectionMembershipsTable extends VersionedTable {
	public $collectionId;
	public $memberMid;	
	public $internalMemberId;
	public $applicableLanguageId;
	
	public function __construct($name) {
		parent::__construct($name);
		
		$this->collectionId = $this->createColumn("collection_id"); 	
		$this->memberMid = $this->createColumn("member_mid"); 	
		$this->internalMemberId = $this->createColumn("internal_member_id"); 	
		$this->applicableLanguageId = $this->createColumn("applicable_language_id");
		
		$this->setKeyColumns(array($this->collectionId, $this->memberMid));	
	}
}

class MeaningRelationsTable extends VersionedTable {
	public $relationId;
	public $meaning1Mid;	
	public $meaning2Mid;
	public $relationTypeMid;
	
	public function __construct($name) {
		parent::__construct($name);
		
		$this->relationId = $this->createColumn("relation_id"); 	
		$this->meaning1Mid = $this->createColumn("meaning1_mid"); 	
		$this->meaning2Mid = $this->createColumn("meaning2_mid"); 	
		$this->relationTypeMid = $this->createColumn("relationtype_mid");
		
		$this->setKeyColumns(array($this->relationId));	
	}
}

class SyntransTable extends VersionedTable {
	public $syntransSid;
	public $definedMeaningId;	
	public $expressionId;
	public $identicalMeaning;
	
	public function __construct($name) {
		parent::__construct($name);
		
		$this->syntransSid = $this->createColumn("syntrans_sid"); 	
		$this->definedMeaningId = $this->createColumn("defined_meaning_id"); 	
		$this->expressionId = $this->createColumn("expression_id"); 	
		$this->identicalMeaning = $this->createColumn("identical_meaning");
		
		$this->setKeyColumns(array($this->syntransSid));	
	}
}

class TextAttributeValuesTable extends VersionedTable {
	public $valueId;
	public $objectId;	
	public $attributeMid;
	public $text;
	
	public function __construct($name) {
		parent::__construct($name);
		
		$this->valueId = $this->createColumn("value_id"); 	
		$this->objectId = $this->createColumn("object_id"); 	
		$this->attributeMid = $this->createColumn("attribute_mid"); 	
		$this->text = $this->createColumn("text");
		
		$this->setKeyColumns(array($this->valueId));	
	}
}

class TranslatedContentAttributeValuesTable extends VersionedTable {
	public $valueId;
	public $objectId;	
	public $attributeMid;
	public $valueTcid;
	
	public function __construct($name) {
		parent::__construct($name);
		
		$this->valueId = $this->createColumn("value_id"); 	
		$this->objectId = $this->createColumn("object_id"); 	
		$this->attributeMid = $this->createColumn("attribute_mid"); 	
		$this->valueTcid = $this->createColumn("value_tcid");
		
		$this->setKeyColumns(array($this->valueId));	
	}
}

class TranslatedContentTable extends VersionedTable {
	public $translatedContentId;
	public $languageId;	
	public $textId;
	public $originalLanguageId;
	
	public function __construct($name) {
		parent::__construct($name);
		
		$this->translatedContentId = $this->createColumn("translated_content_id"); 	
		$this->languageId = $this->createColumn("language_id"); 	
		$this->textId = $this->createColumn("text_id"); 	
		$this->originalLanguageId = $this->createColumn("original_language_id");
		
		$this->setKeyColumns(array($this->translatedContentId, $this->languageId));	
	}
}

class OptionAttributeOptionsTable extends VersionedTable {
	public $optionId;
	public $attributeId;	
	public $optionMid;
	public $languageId;
	
	public function __construct($name) {
		parent::__construct($name);
		
		$this->optionId = $this->createColumn("option_id"); 	
		$this->attributeId = $this->createColumn("attribute_id"); 	
		$this->optionMid = $this->createColumn("option_mid"); 	
		$this->languageId = $this->createColumn("language_id");
		
		$this->setKeyColumns(array($this->attributeId, $this->optionMid)); // TODO: is this the correct key?	
	}
}

class OptionAttributeValuesTable extends VersionedTable {
	public $valueId;
	public $objectId;	
	public $optionId;
	
	public function __construct($name) {
		parent::__construct($name);
		
		$this->valueId = $this->createColumn("value_id"); 	
		$this->objectId = $this->createColumn("object_id"); 	
		$this->optionId = $this->createColumn("option_id"); 	
		
		$this->setKeyColumns(array($this->valueId));	
	}
}

class LinkAttributeValuesTable extends VersionedTable {
	public $valueId;
	public $objectId;	
	public $attributeMid;
	public $url;
	public $label;
	
	public function __construct($name) {
		parent::__construct($name);
		
		$this->valueId = $this->createColumn("value_id"); 	
		$this->objectId = $this->createColumn("object_id"); 	
		$this->attributeMid = $this->createColumn("attribute_mid"); 	
		$this->url = $this->createColumn("url");
		$this->label = $this->createColumn("label");
		
		$this->setKeyColumns(array($this->valueId));	
	}
}

class WikiDataSet {
	public $alternativeDefinitions;
	public $bootstrappedDefinedMeanings; 
	public $classAttributes;
	public $classMemberships; 
	public $collectionMemberships;
	public $definedMeaning; 
	public $expression;
	public $linkAttributeValues;
	public $meaningRelations; 
	public $optionAttributeOptions; 
	public $optionAttributeValues; 
	public $syntrans; 
	public $textAttributeValues; 
	public $translatedContentAttributeValues; 
	public $translatedContent; 
	public $transactions;
	
	public function __construct($dataSetPrefix) {
		$this->alternativeDefinitions = new AlternativeDefinitionsTable("{$dataSetPrefix}_alt_meaningtexts");
		$this->bootstrappedDefinedMeanings = new BootstrappedDefinedMeaningsTable("{$dataSetPrefix}_bootstrapped_defined_meanings");
		$this->classAttributes = new ClassAttributesTable("{$dataSetPrefix}_class_attributes");
		$this->classMemberships = new ClassMembershipsTable("{$dataSetPrefix}_class_membership");
		$this->collectionMemberships = new CollectionMembershipsTable("{$dataSetPrefix}_collection_contents");
		$this->definedMeaning = new DefinedMeaningTable("{$dataSetPrefix}_defined_meaning");
		$this->expression = new ExpressionTable("{$dataSetPrefix}_expression");
		$this->linkAttributeValues = new LinkAttributeValuesTable("{$dataSetPrefix}_url_attribute_values");
		$this->meaningRelations = new MeaningRelationsTable("{$dataSetPrefix}_meaning_relations");
		$this->syntrans = new SyntransTable("{$dataSetPrefix}_syntrans");
		$this->textAttributeValues = new TextAttributeValuesTable("{$dataSetPrefix}_text_attribute_values");
		$this->transactions = new Table("{$dataSetPrefix}_transactions", false, array("transaction_id"));
		$this->translatedContentAttributeValues = new TranslatedContentAttributeValuesTable("{$dataSetPrefix}_translated_content_attribute_values");
		$this->translatedContent = new TranslatedContentTable("{$dataSetPrefix}_translated_content");
		$this->optionAttributeOptions = new OptionAttributeOptionsTable("{$dataSetPrefix}_option_attribute_options");
		$this->optionAttributeValues = new OptionAttributeValuesTable("{$dataSetPrefix}_option_attribute_values");
	}
}

global
	$dataSet;

require_once("Wikidata.php");

$dataSet = new WikiDataSet(wdGetDataSetContext());

function genericSelect($selectCommand, array $expressions, array $tables, array $restrictions) {
	$result = $selectCommand . " " . $expressions[0]->toExpression();
	
	for ($i = 1; $i < count($expressions); $i++)
		$result .= ", " . $expressions[$i]->toExpression();
		
	if (count($tables) > 0) {
		$result .= " FROM " . $tables[0]->getIdentifier();
		
		for ($i = 1; $i < count($tables); $i++)
			$result .= ", " . $tables[$i]->getIdentifier();
	}
	
	if (count($restrictions) > 0) {
		$result .= " WHERE (" . $restrictions[0] . ")";
		
		for ($i = 1; $i < count($restrictions); $i++)
			$result .= " AND (" . $restrictions[$i] . ")";
	}
	
	return new SelectExpression($result);
}

function select(array $expressions, array $tables, array $restrictions) {
	return genericSelect("SELECT", $expressions, $tables, $restrictions);
}

function selectDistinct(array $expressions, array $tables, array $restrictions) {
	return genericSelect("SELECT DISTINCT", $expressions, $tables, $restrictions);
}

function genericSelectLatest($selectCommand, array $expressions, array $tables, array $restrictions) {
	foreach($tables as $table)
		if ($table->isVersioned)
			$restrictions[] = $table->removeTransactionId->toExpression() . " IS NULL";
	
	return genericSelect($selectCommand, $expressions, $tables, $restrictions);
}

function selectLatest(array $expressions, array $tables, array $restrictions) {
	return genericSelectLatest("SELECT", $expressions, $tables, $restrictions);
}

function selectLatestDistinct(array $expressions, array $tables, array $restrictions) {
	return genericSelectLatest("SELECT DISTINCT", $expressions, $tables, $restrictions);
}

function expressionToSQL($expression) {
	if (is_int($expression))
		return $expression;
	else if (is_string($expression)) {
		$dbr =& wfGetDB(DB_SLAVE);
		return $dbr->addQuotes($expression);
	}
	else if (is_object($expression) && $expression instanceof DatabaseExpression) 
		return $expression->toExpression();
	else
		throw new Exception("Cannot convert expression to SQL: " . $expression); 
}

function equals($expression1, $expression2) {
	return new DefaultDatabaseExpression('(' . expressionToSQL($expression1) . ') = (' . expressionToSQL($expression2) . ')');
}

function in(DatabaseExpression $expression1, $expression2) {
	return new DefaultDatabaseExpression($expression1->toExpression() . " IN (" . expressionToSQL($expression2) . ")");
}

function inArray(DatabaseExpression $expression, $values) {
	$sqlValues = array();
	
	foreach($values as $value)
		$sqlValues[] = expressionToSQL($value);
	
	if (count($values) > 0)
		return new DefaultDatabaseExpression($expression->toExpression() . " IN (" . join($sqlValues, ", ") . ")");
	else
		return new DefaultDatabaseExpression(1);
}

function sqlOr($expression1, $expression2) {
	return new DefaultDatabaseExpression('(' . expressionToSQL($expression1) . ') OR (' . expressionToSQL($expression2) . ')');
}

function sqlAnd($expression1, $expression2) {
	return new DefaultDatabaseExpression('(' . expressionToSQL($expression1) . ') AND (' . expressionToSQL($expression2) . ')');
}

?>