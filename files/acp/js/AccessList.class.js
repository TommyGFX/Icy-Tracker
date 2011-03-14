/**
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @todo		add group support, create a separate package for this stuff --RouL
 */

var AccessList = Class.create({
	initialize: function(name, entities) {
		// bindings
		this.add = this._add.bindAsEventListener(this);
		this.gotFocus = this._gotFocus.bindAsEventListener(this);
		this.lostFocus = this._lostFocus.bindAsEventListener(this);
		this.keyup = this._keyup.bindAsEventListener(this);
		this.ajaxResponse = this._ajaxResponse.bindAsEventListener(this);
		
		// initialize variables
		this.name = name;
		this.entities = entities;
		this.inputHasFocus = false;
		
		// now do the "real" work
		// observe button
		this.btnAdd = $(this.name + 'AddButton');
		if (this.btnAdd != null) {
			this.btnAdd.observe('click', this.add);
		}
		
		// observe input
		this.listInput = $(this.name + 'AddInput');
		if (this.listInput != null) {
			this.listInput.observe('focus', this.gotFocus);
			this.listInput.observe('blur', this.lostFocus);
			this.listInput.observe('keyup', this.keyup);
		}
		
		this.canvas = $(this.name);
		if (this.canvas != null) {
			this.canvas.accessList = this;
		}
		
		this.refresh();
	},
	
	_add: function() {
		var entityName = this.listInput.value.strip();
		
		if (entityName != '') {
			new Ajax.Request('index.php?page=AccessEntities' + SID_ARG_2ND,{
				parameters: {
					q: entityName
				},
				onSuccess: this.ajaxResponse
			});
		} 
	},
	
	_ajaxResponse: function(transport) {
		if (transport.responseXML != null) {
			var entities = transport.responseXML.getElementsByTagName('entity');
			if (entities != null && entities.length > 0) {
				for (var i = 0; i < entities.length; i++) {
					var entityData = entities[i];
					
					var entity = new Object();
					entity.id = entityData.getAttribute('id');
					entity.type = entityData.getAttribute('type');
					entity.name = entityData.firstChild.nodeValue;
					
					var cont = false;
					for (var j = 0; j < this.entities.length; j++) {
						if (this.entities[j]['id'] == entity.id && this.entities[j]['type'] == entity.type) cont = true;
					}
					if (cont) continue;
					
					this.entities.push(entity);
				}
				
				this.listInput.value = '';
				this.refresh();
			}
		}
	},
	
	refresh: function() {
		if (this.canvas != null) {
			this.canvas.fire('access:refresh');
			
			// clear canvas
			this.canvas.childElements().each(function(element) {
				element.remove();
			});
			
			// create list-container
			var list = new Element('ul');
			this.canvas.insert(list);
			
			// create list-content
			for (var i = 0; i < this.entities.length; i++) {
				var entity = this.entities[i];
				
				var item = new Element('li');
				list.insert(item);
				
				var btnRemove = new Element('a', {
					'class': 'remove'
				});
				item.insert(btnRemove);
				btnRemove.observe('click', this._remove.bindAsEventListener(this, i));
				var imgRemove = new Element('img', {
					src: RELATIVE_WCF_DIR + 'icon/deleteS.png'
				});
				btnRemove.insert(imgRemove);
				
				var name = new Element('a');
				item.insert(name);
				var imgName = new Element('img', {
					src: RELATIVE_WCF_DIR + 'icon/' + entity.type + 'S.png'
				});
				name.insert(imgName);
				name.insert(entity.name);
			}
		}
	},
	
	submit: function(form) {
		for (var i = 0; i < this.entities.length; i++) {
			var entity = this.entities[i];
			
			var idField = new Element('input', {
				type: 'hidden',
				name: this.name + '[' + i + '][id]',
				value: entity.id
			});
			form.insert(idField);
			
			var typeField = new Element('input', {
				type: 'hidden',
				name: this.name + '[' + i + '][type]',
				value: entity.type
			});
			form.insert(typeField);
			
			var nameField = new Element('input', {
				type: 'hidden',
				name: this.name + '[' + i + '][name]',
				value: entity.name
			});
			form.insert(nameField);
		}
	},
	
	_gotFocus: function() {
		this.inputHasFocus = true;
	},
	
	_lostFocus: function() {
		this.inputHasFocus = false;
	},
	
	_keyup: function(e) {
		var keyCode = 0;
		if (e.which) keyCode = e.which;
		else if (e.keyCode) keyCode = e.keyCode;
		
		if (keyCode == Event.KEY_RETURN) {
			this.add();
		}
	},
	
	_remove: function(e, item) {
		this.entities.splice(item, 1);
		this.refresh();
	}
});
