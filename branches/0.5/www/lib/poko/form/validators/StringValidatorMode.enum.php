<?php

class poko_form_validators_StringValidatorMode extends Enum {
		public static $ALLOW;
		public static $DENY;
	}
	poko_form_validators_StringValidatorMode::$ALLOW = new poko_form_validators_StringValidatorMode("ALLOW", 0);
	poko_form_validators_StringValidatorMode::$DENY = new poko_form_validators_StringValidatorMode("DENY", 1);
