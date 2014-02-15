<?php

namespace SESP;

use SMWDataItem as DataItem;
use SMW\DIProperty;

/**
 * @ingroup SESP
 *
 * @licence GNU GPL v2+
 * @since 0.3
 *
 * @author mwjames
 */
class PropertyRegistry {

	/** @var PropertyRegistry */
	protected static $instance = null;

	/**
	 * @since 0.3
	 *
	 * @return PropertyRegistry
	 */
	public static function getInstance() {

		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @since 0.3
	 */
	public static function clear() {
		self::$instance = null;
	}

	/**
	 * @since 0.3
	 *
	 * @param string $id
	 *
	 * @return string|null
	 */
	public function getPropertyId( $id ) {

		$container = array(
			'_CUSER'  => '___CUSER',
			'_REVID'  => '___REVID',
			'_PAGEID' => '___PAGEID',
			'_VIEWS'  => '___VIEWS',
			'_SUBP'   => '___SUBP',
			'_NREV'   => '___NREV',
			'_NTREV'  => '___NTREV',
			'_EUSER'  => '___EUSER',
			'_EXIFDATETIME'   => '___EXIFDATETIME',
			'_EXIFSOFTWARE'   => '___EXIFSOFTWARE',
			'_SHORTURL'  => '___SHORTURL',
			'_METADATA'  => '___EXIFDATETIME',
			'_METADATA'  => '___EXIFSOFTWARE',
			'_USERREG'   => '___USERREG',
			'_MIMETYPE'  => '___MIMETYPE',
			'_MEDIATYPE' => '___MEDIATYPE'
		);

		if ( isset( $container[$id] ) ) {
			return $container[$id];
		}

		return null;
	}

	/**
	 * @since 0.3
	 *
	 * @param array $propertyTableDefinitions
	 * @param array $configuration
	 *
	 * @return boolean
	 */
	public function registerAsFixedTables( &$propertyTableDefinitions, $configuration ) {

		if ( !isset( $configuration['sespUseAsFixedTables'] ) || !$configuration['sespUseAsFixedTables'] ) {
			return true;
		}

		$fixedProperties = array(
			'_REVID'  => DataItem::TYPE_NUMBER,
			'_PAGEID' => DataItem::TYPE_NUMBER,
			'_EUSER'  => DataItem::TYPE_WIKIPAGE,
			'_CUSER'  => DataItem::TYPE_WIKIPAGE,
			'_VIEWS'  => DataItem::TYPE_NUMBER,
			'_SUBP'   => DataItem::TYPE_WIKIPAGE,
			'_NREV'   => DataItem::TYPE_NUMBER,
			'_NTREV'  => DataItem::TYPE_NUMBER,
			'_MIMETYPE'  => DataItem::TYPE_BLOB,
			'_MEDIATYPE' => DataItem::TYPE_BLOB,
			'_SHORTURL'  => DataItem::TYPE_URI,
			'_USERREG'   => DataItem::TYPE_TIME,
			'_EXIFDATETIME' => DataItem::TYPE_TIME,
			'_EXIFSOFTWARE' => DataItem::TYPE_BLOB,
		);

		$enabledSpecialProperties = array_flip( $configuration['sespSpecialProperties'] );

		foreach( $fixedProperties as $propertyKey => $tableDataItemType ) {

			if ( !isset( $enabledSpecialProperties[$propertyKey] ) ) {
				continue;
			}

			$tableName = 'smw_ftp_sesp' . strtolower( $propertyKey );

			$propertyTableDefinitions[$tableName] = new \SMW\SQLStore\TableDefinition(
				$tableDataItemType,
				$tableName,
				$this->getPropertyId( $propertyKey )
			);
		}

		return true;
	}

	/**
	 * @since 0.3
	 */
	public function registerPropertiesAndAliases() {

		// Page author
		DIProperty::registerProperty( '___EUSER', '_wpg',
			wfMessage('sesp-property-author')->inContentLanguage()->text() );
		DIProperty::registerPropertyAlias( '___EUSER', 'Page author' );

		// Page creator
		DIProperty::registerProperty( '___CUSER', '_wpg',
			wfMessage('sesp-property-first-author')->inContentLanguage()->text() );
		DIProperty::registerPropertyAlias( '___CUSER', 'Page creator' );

		// Revision ID
		DIProperty::registerProperty( '___REVID', '_num',
			wfMessage('sesp-property-revision-id')->inContentLanguage()->text() );
		DIProperty::registerPropertyAlias( '___REVID', 'Revision ID' );

		// Page Id
		DIProperty::registerProperty( '___PAGEID', '_num',
			wfMessage('sesp-property-page-id')->inContentLanguage()->text() );
		DIProperty::registerPropertyAlias( '___PAGEID', 'Page Id' );

		//View count
		DIProperty::registerProperty( '___VIEWS', '_num',
			wfMessage('sesp-property-view-count')->inContentLanguage()->text() );
		DIProperty::registerPropertyAlias( '___VIEWS', 'Number of page views' );

		//Sub pages
		DIProperty::registerProperty( '___SUBP', '_wpg',
			wfMessage('sesp-property-subpages')->inContentLanguage()->text() );
		DIProperty::registerPropertyAlias( '___SUBP', 'Subpage' );

		//Number of revisions
		DIProperty::registerProperty( '___NREV', '_num',
			wfMessage('sesp-property-revisions')->inContentLanguage()->text() );
		DIProperty::registerPropertyAlias( '___NREV', 'Number of revisions' );

		//Number of talk page revisions
		DIProperty::registerProperty( '___NTREV', '_num',
			wfMessage('sesp-property-talk-revisions')->inContentLanguage()->text() );
		DIProperty::registerPropertyAlias( '___NTREV', 'Number of talk page revisions' );

		// MIME type
		DIProperty::registerProperty( '___MIMETYPE', '_str',
			wfMessage('sesp-property-mimetype')->inContentLanguage()->text() );
		DIProperty::registerPropertyAlias( '___MIMETYPE', 'MIME type' );

		// MIME type
		DIProperty::registerProperty( '___MEDIATYPE', '_str',
			wfMessage('sesp-property-mediatype')->inContentLanguage()->text() );
		DIProperty::registerPropertyAlias( '___MEDIATYPE', 'Media type' );

		// SHORTURL type
		DIProperty::registerProperty( '___SHORTURL', '_uri',
			wfMessage('sesp-property-shorturl')->inContentLanguage()->text() );
		DIProperty::registerPropertyAlias( '___SHORTURL', 'Short URL' );

		// METADATA types
		DIProperty::registerProperty( '___EXIFDATETIME', '_dat',
			wfMessage('exif-datetimeoriginal')->inContentLanguage()->text() );
		DIProperty::registerPropertyAlias( '___EXIFDATETIME', 'Exposure date' );

		DIProperty::registerProperty( '___EXIFSOFTWARE', '_str',
			wfMessage('exif-software')->inContentLanguage()->text() );
		DIProperty::registerPropertyAlias( '___EXIFSOFTWARE', 'Software' );

		//User registration date
		DIProperty::registerProperty( '___USERREG', '_dat',
			wfMessage('sesp-property-user-registration-date')->inContentLanguage()->text() );
		DIProperty::registerPropertyAlias( '___USERREG', 'User registration date' );

		return true;
	}

}
