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

Library::import('ValidationPlugin.annotations.ValidatesAnnotation');
Library::import('ValidationPlugin.wrappers.ValidatesExclusionOfWrapper');

//
// ValidatesExclusionOf
// 
// This annotation adds a validator to a Model class that ensures
// the specified fields' values are NOT in an array of values.
// 
// USAGE:
// 
// /**
//  * !ValidatesExclusionOf Fields: (genre), On: (save, insert, update, delete), From: (horror, romance), Message: (is not an acceptable value)
//  */
// class Book extends Model {
// 		public $genre;
// }
//
// Fields:
// - This key is required
// - Accepts a comma delimited list of Model attributes to be validated
//
// On:
// - This key is required
// - Accepts a comma delimited list of Model actions (save, update, insert)
//
// From:
// - This key is required
// - Accepts a comma delimited list of black-list values
//
// Message:
// - This key is optional
// - Accepts a string
// - Default is "is not an acceptable value"
//
// @author Josh Lockhart <info@joshlockhart.com>
// @since Version 1.0
//
class ValidatesExclusionOfAnnotation extends ValidatesAnnotation {
	
	public function usage() {
		return '!ValidatesExclusionOf Fields: (category), On: (insert, update), From: (cakes, pies, candies), Message: (is an excluded value)';		
	}
	
	protected function validate($class) {
		$this->acceptsNoKeylessValues();
		$this->acceptedKeys(array('fields','on', 'from', 'message'));
		$this->requiredKeys(array('fields', 'from'));
		$this->validOnSubclassesOf($class,'Model');
	}
	
	protected function expand($class, $reflection, $descriptor) {
		$validateMethods = ( isset($this->on) ) ? $this->on : array('save');
		foreach( $validateMethods as $method ) {
			$method = strtolower($method);
			if( in_array($method, $this->validMethods) ) {
				$message = ( isset($this->message) ) ? $this->message : 'is not an acceptable value';
				$descriptor->addWrapper($method, new ValidatesWrapper(array('ValidatesExclusionOfWrapper::validate', array($this->fields, $message, $this->from))));
			}
		}
		return $descriptor;
	}
	
}

?>