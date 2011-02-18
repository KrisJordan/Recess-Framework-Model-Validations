<?php
/**
 * Recess Framework Model Validations
 *
 * Add Rails-esque validation annotations to your Recess models
 *
 * @author		Josh Lockhart <info@joshlockhart.com>
 * @link		http://code.joshlockhart.com/recess/validations/
 * @copyright	2010 Josh Lockhart
 * 
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 * 
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

Library::import('recess.lang.IWrapper');

/**
 * Validates Wrapper
 *
 * This is the super class for all validation wrapper classes. This class
 * is responsible for queueing and running all validations on a Model.
 *
 * If a validation fails, then the validated Model will be assigned a
 * $errors property (an array) which contains the validation errors.
 *
 * @author Josh Lockhart <info@joshlockhart.com>
 * @since Version 1.0
 */
class ValidatesWrapper implements IWrapper {
	
	protected $validations;
	
	public function __construct( $validation ) {
		$this->validations[] = $validation;
	}
	
	public function before( $object, &$args ) {
		if ( !isset($object->errors) || !is_array($object->errors) ) {
			$object->errors = array();
		}
		foreach( $this->validations as $validation ) {
			array_unshift($validation[1], $object);
			$errors = call_user_func_array($validation[0], $validation[1]);
			if( !empty($errors) ) {
				$object->errors = array_merge($object->errors, $errors);
			}
		}
		return empty($object->errors) ? null : false;
	}
	
	public function after ( $object, $returnValue ) {
		return $returnValue;
	}
	
	public function combine( IWrapper $wrapper ) {
		if( $wrapper instanceof ValidatesWrapper ) {
			$this->validations = array_merge($this->validations, $wrapper->validations);
			return true;
		} else {
			return false;
		}
	}
	
	public static function labelForObjectProperty($object, $field) {
		$descriptor = Model::getDescriptor(get_class($object));
		$property = isset($descriptor->properties[$field]) ? $descriptor->properties[$field] : new stdClass();
		return isset($property->label) ? $property->label : Inflector::toProperCaps($field);
	}
}

?>