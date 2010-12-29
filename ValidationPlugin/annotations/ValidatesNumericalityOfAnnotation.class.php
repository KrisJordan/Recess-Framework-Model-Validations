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
Library::import('ValidationPlugin.wrappers.ValidatesNumericalityOfWrapper');

//
// ValidatesNumericalityOf
// 
// This annotation adds a validator to a Model class that ensures
// the specified fields' values are numeric.
// 
// USAGE:
// 
// /**
//  * !ValidatesNumericalityOf Fields: (pageCount), On: (save, insert, update)
//  */
// class Book extends Model {
// 		public $pageCount;
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
// Message:
// - This field is optional
// - Accepts a string value
// - Default "must be numeric"
//
// @author Josh Lockhart <info@joshlockhart.com>
// @since Version 1.0
//
class ValidatesNumericalityOfAnnotation extends ValidatesAnnotation {
		
	public function usage() {
		return '!ValidatesNumericalityOf Fields: (one, two, three), On: (insert, update), Message: "must be numeric"';		
	}
	
	protected function expand($class, $reflection, $descriptor) {
		$validateMethods = ( isset($this->on) ) ? $this->on : array('save');
		foreach( $validateMethods as $method ) {
			$method = strtolower($method);
			if( in_array($method, $this->validMethods) ) {
				$message = ( isset($this->message) ) ? $this->message : 'must be numeric';
				$descriptor->addWrapper($method, new ValidatesWrapper(array('ValidatesNumericalityOfWrapper::validate', array($this->fields, $message))));
			}
		}
		return $descriptor;
	}
	
}

?>