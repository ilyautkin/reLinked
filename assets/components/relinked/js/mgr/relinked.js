var reLinked = function (config) {
	config = config || {};
	reLinked.superclass.constructor.call(this, config);
};
Ext.extend(reLinked, Ext.Component, {
	page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('relinked', reLinked);

reLinked = new reLinked();