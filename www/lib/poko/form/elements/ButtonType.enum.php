<?php

class poko_form_elements_ButtonType extends Enum {
	public static $BUTTON;
	public static $RESET;
	public static $SUBMIT;
	public static $__constructors = array(1 => 'BUTTON', 2 => 'RESET', 0 => 'SUBMIT');
	}
poko_form_elements_ButtonType::$BUTTON = new poko_form_elements_ButtonType("BUTTON", 1);
poko_form_elements_ButtonType::$RESET = new poko_form_elements_ButtonType("RESET", 2);
poko_form_elements_ButtonType::$SUBMIT = new poko_form_elements_ButtonType("SUBMIT", 0);
