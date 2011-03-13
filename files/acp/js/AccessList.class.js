/**
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @todo		create a separate package for this stuff --RouL
 */

var AccessList = Class.create();
AccessList.prototype = {
	initialize: function(name, data) {
		// bindings
		this.add = this._add.bindAsEventListener(this);
		
		// initialize variables
		this.name = name;
		this.data = data;
		
		// now do the "real" work
		// observe button
		this.buttonAdd = $(this.name + 'AddButton');
		if (this.buttonAdd) {
			this.buttonAdd.observe('click', this.add);
		}
	},
	
	_add: function() {
		alert('Test');
	}
};
