Ext.override(MODx.panel.Resource, {

    originals: {
		getFields: MODx.panel.Resource.prototype.getFields
	},

	getFields: function(config) {
		var originals = this.originals.getFields.call(this, config);

		for (var i in originals) {
			if (!originals.hasOwnProperty(i)) {
				continue;
			}
			var item = originals[i];

			if (item.id == 'modx-resource-tabs') {
				if (typeof config.record.properties['relinked'] != 'undefined') {
					item.items.push({
						xtype: "relinked-page",
						id: "relinked-page",
						title: _("relinked_items"),
						record: {
							id: config.record.id
						}
					});
				}
				else {
					console.log('Could not find ms2gallery properties in resource');
				}
			}
		}

		return originals;
	}

});

reLinked.panel.ResourceLinks = function(config) {
    config = config || {};

	Ext.apply(config,{
		id: 'relinked-page',
		baseCls: 'x-panel relinked ' + (MODx.modx23 ? 'modx23' : 'modx22'),
		items: [{
			border: false,
			baseCls: 'panel-desc',
			html: '<p>' + _('relinked_page_introtext') + '</p>'
		}, {
			border: false,
			style: {padding: '5px', overflow: 'hidden'},
			layout: 'anchor',
			items: [{
				border: false,
				xtype: 'relinked-grid-positions',
				id: 'relinked-grid-positions',
				record: config.record,
				gridHeight: 150
			}]
		}]
	});
	reLinked.panel.ResourceLinks.superclass.constructor.call(this,config);
};
Ext.extend(reLinked.panel.ResourceLinks,MODx.Panel);
Ext.reg('relinked-page',reLinked.panel.ResourceLinks);