package poko.utils.memcache;
import poko.utils.memcache.externs.Memcache;

/**
 * Wrapper for http://www.php.net/manual/en/book.memcache.php
 */
class Memcache {

    var m_mc:poko.utils.memcache.externs.Memcache;
    public var flags:Dynamic; // check on status of flags after a get or getArray
    public var prefix:String;

    public function new(){
        m_mc = new poko.utils.memcache.externs.Memcache();
        prefix = "";
    }

    public function add(_key:String, _var:Dynamic, ?_flag:Dynamic, ?_expire:Int=0):Bool {
        return m_mc.add(prefix+_key, _var, _flag, _expire);
    }

    public function addServer(_host:String, ?_port:Int=11211, ?_persistent:Bool=true, ?_weight:Int=1, ?_timeout:Int=1, ?_retry_interval:Int=15, ?_status:Bool=true, ?_failure_callback:String->Int->Void, ?_timeoutms:Int):Bool {
        try {
            return m_mc.addServer(_host, _port, _persistent, _weight, _timeout, _retry_interval, _status, _failure_callback, _timeoutms);
        } catch(e:Dynamic) {
            _failure_callback(_host, _port);
        }
        return false;
    }

    public function close():Bool {
        return m_mc.close();
    }

    public function connect(_host:String, ?_port:Int=11211, ?_timeout:Int=1):Bool {
        return m_mc.connect(_host, _port, _timeout);
    }

    public function debug(_state:Bool):Bool {
        return m_mc.debug(_state);
    }

    public function decrement(_key:String, ?_value:Int=1):Int {
        var res = m_mc.decrement(prefix+_key, _value);
        return cast(res);
    }

    public function delete(_key:String, ?_timeout:Int=0):Bool {
        return m_mc.delete(prefix+_key, _timeout);
    }

    public function flush():Bool {
        return m_mc.flush();
    }

    public function get(_key:String):Dynamic {
        var val = untyped __php__("$this->m_mc->get($this->prefix.$_key)");
		if (val == false) val = null;
        return val;
    }

    public function getArray(_keys:Array<String>):Array<Dynamic> {
        var prefixedKeys:Array<Dynamic> = _keys.copy();
        for(i in 0..._keys.length) {
            prefixedKeys[i] = prefix+_keys[i];
        }

        var keys:php.NativeArray = php.Lib.toPhpArray(prefixedKeys);
        var val:php.NativeArray = untyped __php__("$this->m_mc->get($keys)");
        return cast(php.Lib.toHaxeArray(val));
    }

    public function getExtendedStats(?_type:String, ?_slabid:Int, ?_limit:Int=100):Hash<Hash<Dynamic>> {
        var res:Dynamic = m_mc.getExtendedStats(_type, _slabid, _limit);
        if(res != false) {
            var val:Hash<Dynamic> = php.Lib.hashOfAssociativeArray(res);
            for(i in val.keys()) {
                var e = val.get(i);
                val.set(i, php.Lib.hashOfAssociativeArray(e));
            }
            return cast(val);
        }
        return null;
    }

    public function getServerStatus(_host:String, ?_port:Int=11211):Int {
        return m_mc.getServerStatus(_host, _port);
    }

    public function getStats(?_type:String, ?_slabid:Int, ?_limit:Int=100):Hash<Dynamic> {
        var res:Dynamic = m_mc.getStats(_type, _slabid, _limit);
        if(res == false) {
            return null;
        } else {
            return cast(php.Lib.hashOfAssociativeArray(res));
        }
    }

    public function getVersion():String {
        return m_mc.getVersion();
    }

    public function increment(_key:String, _value:Int=1):Int {
        var res = m_mc.increment(prefix+_key, _value);
        return cast(res);
    }

    public function pconnect(_host:String, ?_port:Int=11211, ?_timeout:Int=1):Bool {
        return m_mc.pconnect(_host, _port, _timeout);
    }

    public function replace(_key:String, _var:Dynamic, ?_flag:Bool=false, ?_expire:Int=0):Bool {
        return m_mc.replace(prefix+_key, _var, _flag, _expire);
    }
    
    public function set(_key:String, _var:Dynamic, ?_flag:Bool=false, ?_expire:Int=0):Bool {
        return m_mc.set(prefix+_key, _var, _flag, _expire);
    }
   
    public function setCompressThreshold(_threshold:Int, ?_min_savings:Float=0.2):Bool {
        return m_mc.setCompressThreshold(_threshold, _min_savings); 
    }

    public function setServerParams(_host:String, ?_port:Int=11211, ?_timeout:Int=1, ?_retry_interval:Int=15, ?_status:Bool=true, ?_failure_callback:String->Int->Void):Bool {
        // On failure an exception is thrown.  This is a workaround.
        try {
            return m_mc.setServerParams(_host, _port, _timeout, _retry_interval, _status, _failure_callback);
        } catch (e:Dynamic) {
            _failure_callback(_host, _port);
        }
        return false;
    }
}