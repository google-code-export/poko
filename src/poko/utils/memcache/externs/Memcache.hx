package poko.utils.memcache.externs;

/**
 * Wrapper for http://www.php.net/manual/en/book.memcache.php
 */
extern class Memcache {

    public function new():Void;

    public function add(_key:String, _var:Dynamic, ?_flag:Bool=false, ?_expire:Int=0):Bool;
    public function addServer(_host:String, ?_port:Int=11211, ?_persistent:Bool=true, ?_weight:Int=1, ?_timeout:Int=1, ?_retry_interval:Int=15, ?_status:Bool=true, ?_failure_callback:String->Int->Void, ?_timeoutms:Int):Bool;
    public function close():Bool;
    public function connect(_host:String, ?_port:Int=11211, ?_timeout:Int=1):Bool;
    public function debug(_state:Bool):Bool;
    public function decrement(_key:String, ?_value:Int=1):Dynamic;
    public function delete(_key:String, ?_timeout:Int=0):Bool;
    public function flush():Bool;
    //public function get(_key:String, ?_flags:Dynamic):String; // flags is a reference
    //public function get(_keys:Array<String>, ?_flags:Array<Dynamic>):Array<String>; // flags is a reference
    public function getExtendedStats(?_type:String, ?_slabid:Int, ?_limit:Int=100):Dynamic; // returns a 2D array
    public function getServerStatus(_host:String, ?_port:Int=11211):Int;
    public function getStats(?_type:String, ?_slabid:Int, ?_limit:Int=100):Dynamic; // returns an associativee array
    public function getVersion():String;
    public function increment(_key:String, _value:Int=1):Dynamic;
    public function pconnect(_host:String, ?_port:Int=11211, ?_timeout:Int=1):Bool;
    public function replace(_key:String, _var:Dynamic, ?_flag:Bool=false, ?_expire:Int=0):Bool;
    public function set(_key:String, _var:Dynamic, ?_flag:Bool=false, ?_expire:Int=0):Bool;
    public function setCompressThreshold(_threshold:Int, ?_min_savings:Float=0.2):Bool;
    public function setServerParams(_host:String, ?_port:Int=11211, ?_timeout:Int=1, ?_retry_interval:Int=15, ?_status:Bool=true, ?_failure_callback:String->Int->Void):Bool;
}