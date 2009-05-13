/**
 * ...
 * @author ...
 */

package site.cms.modules.base;

import poko.form.elements.Input;
import poko.form.elements.Button;
import poko.form.elements.Selectbox;
import poko.form.Form;
import poko.utils.JsBinding;
import poko.utils.PhpTools;
import haxe.Md5;
import haxe.Unserializer;
import php.io.File;
import php.Sys;
import site.cms.common.Definition;
import poko.Request;
import poko.utils.ListData;
import site.cms.common.DefinitionElementMeta;
import site.cms.modules.base.Datasets;

class Dataset extends DatasetBase
{
	public var dataset:Int;
	public var label:String;
	public var table:String;
	public var data:List<Dynamic>;
	public var fields:List<Dynamic>;
	private var fieldLabels:List<Dynamic>;
	private var definition:Definition;
	
	public var isOrderingEnabled:Bool;
	public var orderField:String;
	
	public var optionsForm:Form;
	public var showOrderBy:Bool;
	public var showFiltering:Bool;
	
	private var linkCategoryField:String;
	private var linkCategory:String;
	private var linkValueField:String;
	private var linkValue:Int;
	
	private var associateExtras:Hash<Hash<Dynamic>>;
	public var jsBind:JsBinding;
	
	public function new() 
	{
		super();
	}
	
	override public function pre():Void
	{	
		super.pre();
		
		dataset = Std.parseInt(application.params.get("dataset"));
		definition = new Definition(dataset);
		table = definition.table;
		label = definition.name;
		
		navigation.pageHeading += " (" + label + ")";
		
		jsBind = new JsBinding("site.cms.modules.base.js.JsDataset");
		
		remoting.addObject("api", { getFilterInfo:getFilterInfo } );
		
		// change layout for link view
		if (linkMode)
		{
			head.css.add("css/cms/miniView.css");
			this.template_file = "site/cms/templates/CmsTemplate_mini.mtt";
		}
	}
	
	
	/** remoting */
	
	public function getFilterInfo(field):Dynamic
	{
		var response:Dynamic = { };
		response.type = definition.getElement(field).type;
		response.ass= definition.getElement(field).properties.table;
		response.data = getAssocFields(field);
		return response;
	}
	
	public function getAssocFields(field:String):Hash<Dynamic>
	{
		getAssociationExtras();
		return associateExtras.get(field);
	}
	
	/** end remoting */
	
	override public function main():Void
	{		
		orderField = getOrderField();
		isOrderingEnabled = orderField != null;
		
		if (application.params.get("action")) process();
		
		fields = getFieldMatches();
	
		
		// get primary key
		var primaryData = application.db.request("SHOW COLUMNS FROM `"+table+"` WHERE `Key`='PRI' AND `Extra`='auto_increment'");
		if (primaryData.length < 1)
		{
			application.messages.addError("<b>'"+table+"'</b> does not have a field set as both: <b>auto_increment</b> AND <b>primary key</b>");
			setupLeftNav();
			setContentOutput("cannot display dataset");
			return;
		} else {
			var field = primaryData.pop().Field;
			definition.primaryKey = field;
		}
		
		
		getAssociationExtras();
		
		setupOptionsForm();
		
		var ths = this;
		fieldLabels= Lambda.map(fields, function(row:Dynamic) {	
			var el = ths.definition.getElement(row);
			return el.label != "" ? el.label : el.name; 
		});
		
		// Build SQL to get data
		var sql = "SELECT *, `" + definition.primaryKey + "` as 'cms_primaryKey' ";
		
		// get the orderfield to display
		if (orderField != null)
			sql += ", `"+orderField+"` as 'dataset__orderField' ";
		
		sql += "FROM `" + table + "` ";
		
		var hasWhere = false;
		// filtering
		var filterBySelector = optionsForm.getElement('filterBy');
		if(optionsForm.isSubmitted() && filterBySelector.value != "")
		{
			// Associative filter
			if (definition.getElement(filterBySelector.value).type == "association")
			{	
				var filterByAssocSelector = optionsForm.getElement('filterByAssoc');
				if (filterByAssocSelector.value != "")
					sql += "WHERE `" + filterBySelector.value + "`='" + filterByAssocSelector.value + "' ";
				hasWhere = true;
			}
			// Evaluate Filter
			else 
			{	
				var filterByOperatorSelector = optionsForm.getElement('filterByOperator');
				var filterByValueSelector = optionsForm.getElement('filterByValue');
				if (filterByOperatorSelector.value != "" && filterByValueSelector.value != "")
				{
					var op = filterByOperatorSelector.value == "~" ? "LIKE" : filterByOperatorSelector.value;
					var val = filterByOperatorSelector.value == "~" ? "%" +filterByValueSelector.value+ "%" : filterByValueSelector.value;
					sql += "WHERE `" + filterBySelector.value + "` " + op + " '" + val + "' ";
					hasWhere = true;
				}				
			}
		}
		
		// Only display a section of data for linking
		if (linkMode)
		{
			linkCategoryField = application.params.get("linkCategoryField");
			linkCategory = application.params.get("linkCategory");
			linkValueField= application.params.get("linkValueField");
			linkValue= Std.parseInt(application.params.get("linkValue"));
			
			if(!hasWhere)
				sql += " WHERE ";
			else 
				sql += " AND ";
				
			sql += "`" + linkCategoryField + "`=\"" + linkCategory + "\" ";
			sql += "AND `" + linkValueField + "`=\"" + linkValue + "\" ";
		}
		
		// Ordering 
		
		// Use Order Field
		var orderBySelector = optionsForm.getElement("orderBy");
		if (isOrderingEnabled && this.orderField != null && (!optionsForm.isSubmitted() || orderBySelector.value == null))
		{
			sql += "ORDER BY `dataset__orderField`";
		} 
		// Use OrderBy Filter
		else if (optionsForm.isSubmitted() && orderBySelector.value != null)
		{
			sql += "ORDER BY `" + orderBySelector.value + "` " + optionsForm.getElement("orderByDirection").value;
		} 
		// Use Primary key
		else 
		{
			sql += "ORDER BY `" + definition.primaryKey + "`";
		}
		
		data = application.db.request(sql);	
		
		setupLeftNav();
	}
	
	/** Process Delete & Order */
	private function process():Void
	{
		// delete
		if (php.Web.getParamValues("delete") != null)
		{
			for (delId in php.Web.getParamValues("delete"))
				application.db.delete(table, "`" + definition.primaryKey + "`='" + delId + "'");	
		}	
		// ordering
		if (isOrderingEnabled)
		{
			var c = 0;
			for (orderId in php.Web.getParamValues("order"))
			{
				if (orderId != null) {
					
					var d:Dynamic = { };
					Reflect.setField(d, orderField, orderId);
					application.db.update(table, d, "`"+definition.primaryKey+"`='"+c+"'");
				}
				c++;
			}
			
			c = 0;
			var res = application.db.request("SELECT `" + definition.primaryKey + "` as 'id' from " + table + " ORDER BY `" + orderField + "`");
			for (item in res)
			{
				var d:Dynamic = { };
				Reflect.setField(d, orderField, ++c);
				application.db.update(table, d, "`" + definition.primaryKey + "`='" + item.id + "'");
			}
		}
		
		// duplicate if that's what we're doing?!
		if (application.params.get("action") == "duplicate") {
			var id = application.params.get("id");
			if (id == null || id == "")
				return;
			var data = application.db.requestSingle("SELECT * FROM `"+table+"` WHERE " + definition.primaryKey + "=" + id);

			// pre duplication field stuff
			for (element in definition.elements) {
				switch(element.type) {
					case "image":
						// duplicate the images
						var value:String = Reflect.field(data, element.name);
						var prefix = Md5.encode(Date.now().toString());
						Reflect.setField(data, element.name, prefix + value.substr(32));
						File.copy("res/uploads/" + value, "res/uploads/" + prefix + value.substr(32));
				}
			}
			
			// we have to do special stuff to make a new primary key unless it's an auto increment int field
			var tableInfo = application.db.request("SHOW FIELDS FROM `" + table + "`");
			
			var pField:Dynamic = {};
			for (f in tableInfo) {
				if (f.Field == definition.primaryKey) {
					pField = f;
					break;
				}
			}
			
			if(pField.Type.indexOf("int") == 0 && pField.Extra == "auto_increment"){
				Reflect.deleteField(data, definition.primaryKey);
			}else{
				application.messages.addError("Duplicate only works on datasets with primary keys that are auto-increment ints.");
				return;
			}
			
			application.db.insert(table, data);
			var insertedId = application.db.cnx.lastInsertId();
			
			if (application.db.lastAffectedRows > 0) {
				var element:DefinitionElementMeta;
				
				// post duplication field stuff
				for (element in definition.elements) {
					switch(element.type) {
						case "multilink":
							// add the multilinks
							var p = element.properties;
							var result = application.db.request("SELECT `" + p.linkField1 + "`, `" + p.linkField2 + "` FROM `" + p.link + "` WHERE `" + p.linkField1 + "`=" + id);
							for (o in result) {
								Reflect.setField(o, p.linkField1, insertedId);
								application.db.insert(p.link, o);
							}
					}
				}
				
				// jump to edit mode
				var url = "?request=cms.modules.base.DatasetItem&action=edit";
				url += "&dataset=" + dataset;
				url += "&id=" + insertedId;
				url += "&linkMode=" + (linkMode ? "true" : "false");
				url += "&linkCategoryField=" + application.params.get("linkCategoryField");
				url += "&linkCategory=" + application.params.get("linkCategory");
				url += "&linkValueField=" + application.params.get("linkValueField");
				url += "&linkValue=" + application.params.get("linkValue");
				application.redirect(url);
				
			}else {
				application.messages.addError("Error duplicating item.");
			}
		}
	}
	
	/** get the values of all associated fields - for naming */
	private function getAssociationExtras():Void
	{
		associateExtras = new Hash();
		for (element in definition.elements) {
			if (element.properties.type == "association" && element.properties.showAsLabel == "1") {
				var sql = "SELECT " + element.properties.field + " AS id, "+element.properties.fieldLabel + " AS label FROM " + element.properties.table;
				var result = application.db.request(sql);
				var h:Hash<Dynamic> = new Hash();
				for (e in result)
					h.set(Std.string(e.id), e.label);
				associateExtras.set(element.properties.name, h);
			}
		}
	}
	
	/** Setup the Options for to allow FilterBy and OrderBy options */
	public function setupOptionsForm()
	{
		showOrderBy = definition.showOrdering;
		showFiltering = definition.showFiltering;
		
		// No initital form values, as they are not needed (no db saving etc)
		optionsForm = new Form("options");
		optionsForm.addElement(new Selectbox("filterBy", "filterSBy"));
		optionsForm.addElement(new Selectbox("filterByOperator", "filterOperator"));
		optionsForm.addElement(new Input("filterByValue", "filterByValue"));
		optionsForm.addElement(new Selectbox("filterByAssoc", "filterByAssoc"));
		
		optionsForm.addElement(new Selectbox("orderBy", "orderBy"));
		optionsForm.addElement(new Selectbox("orderByDirection", "direction"));
		
		optionsForm.addElement(new Button("updateButton", "Update Filter"));
		optionsForm.addElement(new Button("resetButton", "Reset", "", poko.form.elements.ButtonType.BUTTON));
		
		// populate the elements with PostBack values from the user's input
		optionsForm.populateElements();
		
		// Populate elements with data - eg all options for select boxes
		poplateOptionsFormData();
	}
	
	/** Populate form elements with thier data (depending on PostBack values) */	
	public function poplateOptionsFormData()
	{
		if (showFiltering)
		{
			var filterBySelector = optionsForm.getElementTyped("filterBy", Selectbox);
			
			for (field in fields)
				if (definition.getElement(field).showInFiltering)
				{
					var label = definition.getElement(field).label != "" ? definition.getElement(field).label : field;
					filterBySelector.addOption( { key:label, value:field } );
				}
			var filterAssocSelector = optionsForm.getElementTyped("filterByAssoc", Selectbox);
			var data:Hash<Dynamic> = associateExtras.get(filterBySelector.value);
			if (data != null)
				for (d in data.keys())
					filterAssocSelector.addOption( { key:data.get(d), value:d } );
			
			var filterOperatorSelector = optionsForm.getElementTyped("filterByOperator", Selectbox);
			filterOperatorSelector.addOption( { key:"=", value:"=" } );
			filterOperatorSelector.addOption( { key:"~", value:"~" } );
			filterOperatorSelector.addOption( { key:">", value:">" } );
			filterOperatorSelector.addOption( { key:"<", value:"<" } );
		}
		
		if (showOrderBy)
		{
			var orderBySelector = optionsForm.getElementTyped("orderBy", Selectbox);
			for (field in fields)
				if(definition.getElement(field).showInOrdering)
					orderBySelector.addOption( { key:field, value:field } );
					
			var orderByDirectionSelector = optionsForm.getElementTyped("orderByDirection", Selectbox);
			orderByDirectionSelector.addOption( { key:"ASC", value:"ASC" } );
			orderByDirectionSelector.addOption( { key:"DESC", value:"DESC" } );
		}
	}
	
	private function getOrderField():String
	{
		for (element in definition.elements)
			if (element.type == "order")
				return element.name;
		return null;
	}
	
	private function getFieldMatches():List<Dynamic>
	{
		// get a list of fields to match on
		var definitionFields:Array<String> = new Array();
		for (element in definition.elements)
			definitionFields.push(element.name);
			
		var ths = this;
		
		// get fields
		var fields = application.db.request("SHOW FIELDS FROM `" + table + "`");
		fields = Lambda.map(fields, function(row:Dynamic) {	
			return row.Field;
		});
		
		// find matches on definition fields and table fields
		fields = Lambda.filter(fields, function(row:String) {	
			var match = Lambda.has(definitionFields, row) && ((ths.definition.getElement(row).type != "hidden" && ths.definition.getElement(row).type != "order" && ths.definition.getElement(row).showInList == 1));
			return match || row == ths.definition.primaryKey;
		});
		
		return fields;
	}
	
	public function orderItems() 
	{
		var c = 0;
		for (val in php.Web.getParamValues("orderNum"))
		{
			if (val != null) application.db.update("news", { order:val }, "`id`=" + c);
			c++;
		}
	}
	
	public function preview(row, field)
	{
		var data:Dynamic = Reflect.field(row, field);
		
		var properties = definition.getElement(field).properties;

		return switch(properties.type)
		{
			case "text": (data).substr(0,50) + (data.length > 50 ? "..." :  "");
			case "richtext": StringTools.htmlEscape(data.substr(0,50)) + ((data.length > 50) ? "..." : "");
			case "image": "<img src=\"?request=services.Image&preset=tiny&src="+data+"\" /> <br/>";
			case "bool": formatBool(cast data, properties);
			case "date": formatDate(cast data);
			case "keyvalue": "list of values";
			case "association":
				properties.showAsLabel == "1" ? associateExtras.get(field).get(cast data) : data;
			default: data;
		}
	}
	public function formatBool(data:Bool, properties:Dynamic)
	{
		if (properties.labelTrue == "" || properties.labelFalse == "")
			return data ? "&#x2714;" : "&#x02610;";
		else 
			return data ? properties.labelTrue : properties.labelFalse;
	}
	public function formatDate(d:Date)
	{
		var months = Lambda.array(ListData.getMonths());
		return d.getDate() + " " + months[d.getMonth()].key +" " + d.getFullYear();
	}
}
