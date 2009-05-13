/**
 * ...
 * @author Tony Polinelli
 */

package poko.form.validators;
import poko.form.Form;
import poko.form.FormElement;
import poko.form.Validator;

class CustomValidator extends Validator
{
	public var validationFunction:FormElement->Bool;
	
	public function new(validationFunction:FormElement->Bool) 
	{
		this.validationFunction = validationFunction;
	}
	
	override public function isValid(value:Dyanmic):Bool
	{
		super.isValid(value);
		
		return Reflect.callMethod(null, validationFunction, [formElement])
	}
}