/**
 * ...
 * @author Tony Polinelli
 */

package poko.form.validators;
import poko.form.Validator;

class BoolValidator extends Validator
{
	public function new() 
	{
		
	}
	override public function isValid(value):Bool
	{
		super.isValid(value);
		
		return true;
	}
}