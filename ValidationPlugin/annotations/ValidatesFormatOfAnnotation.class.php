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
Library::import('ValidationPlugin.wrappers.ValidatesFormatOfWrapper');

//
// ValidatesFormatOf
// 
// This annotation adds a validator to a Model class that ensures
// the specified fields' values match a REGEX pattern.
// 
// USAGE:
// 
// /**
//  * !ValidatesFormatOf Fields: (isbn), On: (save, insert, update), With: @^[a-zA-Z0-9]+$@
//  */
// class Book extends Model {
// 		public $isbn;
// }
//
// Fields:
// - This key is required
// - Accepts a comma delimited list of Model attributes (wrapped in parens)
//
// On:
// - This key is required
// - Accepts a comma delimited list of Model actions (save, update, insert)
//
// With:
// - This field is required
// - Accepts a REGEX pattern (ie. what you would pass as the first parameter of
//   preg_match). Be sure you also include pattern delimiters!
//
// Message:
// - This field is optional
// - Accepts a string value
// - Default "is an invalid format"
//
// @author Josh Lockhart <info@joshlockhart.com>
// @since Version 1.0
//
class ValidatesFormatOfAnnotation extends ValidatesAnnotation {
		
	public function usage() {
		return '!ValidatesFormatOf Fields: (one, two, three), On: (insert, update), With: \d{1,2}, Message: "is an invalid format"';		
	}
	
	protected function validate($class) {
		$this->acceptsNoKeylessValues();
		$this->acceptedKeys(array('fields','on', 'with', 'message'));
		$this->requiredKeys(array('fields', 'with'));
		$this->validOnSubclassesOf($class,'Model');
	}
	
	protected function expand($class, $reflection, $descriptor) {
		$validateMethods = ( isset($this->on) ) ? $this->on : array('save');
		foreach( $validateMethods as $method ) {
			$method = strtolower($method);
			if( in_array($method, $this->validMethods) ) {
				$message = ( isset($this->message) ) ? $this->message : 'is an invalid format';
				$descriptor->addWrapper($method, new ValidatesWrapper(array('ValidatesFormatOfWrapper::validate', array($this->fields, $message, $this->with))));
			}
		}
		return $descriptor;
	}
	
}

?>