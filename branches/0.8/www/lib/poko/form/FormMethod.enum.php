<?php

class poko_form_FormMethod extends Enum {
	public static $GET;
	public static $POST;
	public static $__constructors = array(0 => 'GET', 1 => 'POST');
	}
poko_form_FormMethod::$GET = new poko_form_FormMethod("GET", 0);
poko_form_FormMethod::$POST = new poko_form_FormMethod("POST", 1);
