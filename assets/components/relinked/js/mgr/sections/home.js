reLinked.page.Home = function (config) {
	config = config || {};
	Ext.applyIf(config, {
		components: [{
			xtype: 'relinked-panel-home', renderTo: 'relinked-panel-home-div'
		}]
	});
	reLinked.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(reLinked.page.Home, MODx.Component);
Ext.reg('relinked-page-home', reLinked.page.Home);