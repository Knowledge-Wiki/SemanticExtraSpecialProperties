<?php

namespace SESP;

use Closure;
use RuntimeException;

/**
 * @ingroup SESP
 *
 * @licence GNU GPL v2+
 * @since 0.3
 *
 * @author mwjames
 */
abstract class BaseAnnotator {

	/** @var SemanticData */
	protected $container = array();

	public abstract function addAnnotation();

	/**
	 * @since 0.3
	 *
	 * @param string $objectName
	 * @param Closure $objectSignature
	 */
	public function registerObject( $objectName, Closure $objectSignature ) {
		$this->container[ $objectName ] = $objectSignature;
	}

	protected function loadRegisteredObject( $objectName, $expectedSignature = null ) {
		$instance = isset( $this->container[ $objectName ] ) ? $this->container[ $objectName ]( $this ) : null;

		if ( $instance instanceof $objectName || $instance instanceof $expectedSignature ) {
			return $instance;
		}

		throw new RuntimeException( "Expected a registered {$objectName} object with a {$expectedSignature} signature" );
	}

}
