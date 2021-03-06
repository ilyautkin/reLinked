reLinked.panel.Home = function (config) {
	config = config || {};
	Ext.apply(config, {
		baseCls: 'modx-formpanel',
		layout: 'anchor',
		/*
		 stateful: true,
		 stateId: 'relinked-panel-home',
		 stateEvents: ['tabchange'],
		 getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
		 */
		hideMode: 'offsets',
		items: [{
			html: '<h2>' + _('relinked') + '</h2>',
			cls: '',
			style: {margin: '15px 0'}
		}, {
			xtype: 'modx-tabs',
			defaults: {border: false, autoHeight: true},
			border: true,
			hideMode: 'offsets',
			items: [{
                title: _('relinked_items'),
				layout: 'anchor',
				items: [{
					html: _('relinked_intro_msg'),
					cls: 'panel-desc',
				}, {
					xtype: 'relinked-grid-items',
					cls: 'main-wrapper',
				}]
			}, {
                title: _('relinked_import'),
				layout: 'anchor',
				items: [{
                    html: _('relinked_import_msg'),
                    cls: 'panel-desc',
                }, {
					xtype: 'relinked-import-panel',
					cls: 'main-wrapper',
				}]
			}]
		}]
	});
	reLinked.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(reLinked.panel.Home, MODx.Panel);
Ext.reg('relinked-panel-home', reLinked.panel.Home);
