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
		this._add.bindAsEventListener(this);
		
		// initialize variables
		this.name = name;
		this.data = data;
		
		// now do the "real" work
		// observe button
		var button = $(this.name + 'AddInput');
		if (button) {
			button.observe('click', this.add);
		}
	},
	
	_add: function() {
		alert('Test');
	}
};
