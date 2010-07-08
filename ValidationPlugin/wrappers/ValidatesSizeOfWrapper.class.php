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

/**
 * Validates Size Of
 *
 * This wrapper ensures each field value is an expected size (inclusive).
 * Although both $min and $max keys are optional, at least one of them
 * should be provided.
 *
 * @param Model $object The Model being validated
 * @param array $fields An array of field names to be validated
 * @param int $min The minimum size (optional)
 * @param int $max The maximum size (optional)
 * @return void
 * @author Josh Lockhart <info@joshlockhart.com>
 * @since Version 1.0
 */
class ValidatesSizeOfWrapper extends ValidatesWrapper {
	
	public static function validate( $object, $fields, $min = null, $max = null ) {
		foreach( $fields as $fieldName ) {
			if( isset($object->$fieldName) && ( is_string($object->$fieldName) || is_numeric($object->$fieldName) ) ) {
				$length = is_string($object->$fieldName) ? strlen($object->$fieldName) : $object->$fieldName;
				if( !is_null($min) && $length < intval($min) ) {
					$object->errors[] = Inflector::toProperCaps($fieldName) . " must be greater than $min in size.";
				}
				if( !is_null($max) && $length > intval($max) ) {
					$object->errors[] = Inflector::toProperCaps($fieldName) . " must be less than $max in size.";
				}
			}
		}
	}
	
}

?>