package site.cms.modules.base.helper;

import php.Session;

class FilterSettings
{
	public var enabled:Bool;
	public var dataset:String;
	
	public var filterBy:String;
	public var filterByOperator:String;
	public var filterByAssoc:String;
	public var filterByValue:String;
	public var orderBy:String;
	public var orderByDirection:String;
	
	public static var lastDataset:String;
	
	public function new(dataset:String)
	{
		this.dataset = dataset;
		lastDataset = dataset;
		clear();
	}
	
	public function clear():Void
	{
		enabled = false;
		
		filterBy = "";
		filterByOperator = "";
		filterByAssoc = "";
		filterByValue = "";
		
		orderBy = "";
		orderByDirection = "";
		
		save();		
	}
	
	public static function get(dataset:String)
	{
		if (Session.get("datasetFilterSettings-" + dataset)) {
			return Session.get("datasetFilterSettings-" + dataset);
		}else {
			return new FilterSettings(dataset);
		}
	}
	
	public static function getLast():FilterSettings
	{
		return get(lastDataset);
	}	
	
	public function save()
	{
		Session.set("datasetFilterSettings-" + dataset, this);
	}
	
	public function toString():String
	{
		return((enabled ? "ON" : "OFF") +" dataset:" + dataset);
	}
}