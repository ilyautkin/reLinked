reLinked.grid.Items = function (config) {
    config = config || {};
	if (!config.id) {
		config.id = 'relinked-grid-items';
	}
	Ext.applyIf(config, {
		url: reLinked.config.connector_url,
		fields: this.getFields(config),
		columns: this.getColumns(config),
		tbar: this.getTopBar(config),
		sm: new Ext.grid.CheckboxSelectionModel(),
		baseParams: {
			action: 'mgr/link/getlist'
		},
		listeners: {
			rowDblClick: function (grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.updateItem(grid, e, row);
			}
		},
		viewConfig: {
			forceFit: true,
			enableRowBody: true,
			autoFill: true,
			showPreview: true,
			scrollOffset: 0,
			getRowClass: function (rec, ri, p) {
				return !rec.data.active
					? 'relinked-grid-row-disabled'
					: '';
			}
		},
		paging: true,
		remoteSort: true,
		autoHeight: true,
	});
	reLinked.grid.Items.superclass.constructor.call(this, config);

	// Clear selection on grid refresh
	this.store.on('load', function () {
		if (this._getSelectedIds().length) {
			this.getSelectionModel().clearSelections();
		}
	}, this);
};
Ext.extend(reLinked.grid.Items, MODx.grid.Grid, {
	windows: {},

	getMenu: function (grid, rowIndex) {
		var ids = this._getSelectedIds();

		var row = grid.getStore().getAt(rowIndex);
		var menu = reLinked.utils.getMenu(row.data['actions'], this, ids);

		this.addContextMenuItem(menu);
	},

	createItem: function (btn, e) {
		var w = MODx.load({
			xtype: 'relinked-item-window-create',
			id: Ext.id(),
			listeners: {
				success: {
					fn: function () {
						this.refresh();
					}, scope: this
				}
			}
		});
		w.reset();
		w.setValues({active: true});
		w.show(e.target);
	},

	updateItem: function (btn, e, row) {
		if (typeof(row) != 'undefined') {
			this.menu.record = row.data;
		}
		else if (!this.menu.record) {
			return false;
		}
		var id = this.menu.record.id;

		MODx.Ajax.request({
			url: this.config.url,
			params: {
				action: 'mgr/link/get',
				id: id
			},
			listeners: {
				success: {
					fn: function (r) {
						var w = MODx.load({
							xtype: 'relinked-item-window-update',
							id: Ext.id(),
							record: r,
							listeners: {
								success: {
									fn: function () {
										this.refresh();
									}, scope: this
								}
							}
						});
						w.reset();
						w.setValues(r.object);
						w.show(e.target);
					}, scope: this
				}
			}
		});
	},

	removeItem: function (act, btn, e) {
		var ids = this._getSelectedIds();
		if (!ids.length) {
			return false;
		}
		MODx.msg.confirm({
			title: ids.length > 1
				? _('relinked_items_remove')
				: _('relinked_item_remove'),
			text: ids.length > 1
				? _('relinked_items_remove_confirm')
				: _('relinked_item_remove_confirm'),
			url: this.config.url,
			params: {
				action: 'mgr/link/remove',
				ids: Ext.util.JSON.encode(ids),
			},
			listeners: {
				success: {
					fn: function (r) {
						this.refresh();
					}, scope: this
				}
			}
		});
		return true;
	},

	disableItem: function (act, btn, e) {
		var ids = this._getSelectedIds();
		if (!ids.length) {
			return false;
		}
		MODx.Ajax.request({
			url: this.config.url,
			params: {
				action: 'mgr/link/disable',
				ids: Ext.util.JSON.encode(ids),
			},
			listeners: {
				success: {
					fn: function () {
						this.refresh();
					}, scope: this
				}
			}
		})
	},

	enableItem: function (act, btn, e) {
		var ids = this._getSelectedIds();
		if (!ids.length) {
			return false;
		}
		MODx.Ajax.request({
			url: this.config.url,
			params: {
				action: 'mgr/link/enable',
				ids: Ext.util.JSON.encode(ids),
			},
			listeners: {
				success: {
					fn: function () {
						this.refresh();
					}, scope: this
				}
			}
		})
	},

	getFields: function (config) {
		return ['id', 'page', 'r_title', 'url', 't_title', 'anchor', 'createdon', 'position', 'active', 'actions'];
	},

	getColumns: function (config) {
		return [{
			header: _('relinked_item_id'),
			dataIndex: 'id',
			sortable: true,
			width: 20
		}, /*{
    		header: _('relinked_item_page'),
			dataIndex: 'page',
			sortable: true,
			width: 100,
		}, */{
    		header: _('relinked_item_resource'),
			dataIndex: 'r_title',
            renderer: reLinked.utils.renderResource,
			sortable: true,
			width: 100
		}, /*{
    		header: _('relinked_item_url'),
			dataIndex: 'url',
			sortable: true,
			width: 100,
		}, */{
    		header: _('relinked_item_target'),
			dataIndex: 't_title',
            renderer: reLinked.utils.renderResource,
			sortable: true,
			width: 100
		}, {
			header: _('relinked_item_anchor'),
			dataIndex: 'anchor',
			sortable: false,
			width: 100,
		}, {
			header: _('createdon'),
			dataIndex: 'createdon',
			sortable: true,
			width: 100,
		}, {
    		header: _('relinked_item_position'),
			dataIndex: 'position',
			sortable: true,
			width: 30,
		}, {
            header: _('relinked_item_active'),
			dataIndex: 'active',
			renderer: reLinked.utils.renderBoolean,
			sortable: true,
			width: 30,
		}, {
			header: _('relinked_grid_actions'),
			dataIndex: 'actions',
			renderer: reLinked.utils.renderActions,
			sortable: false,
			width: 45,
			id: 'actions'
		}];
	},

	getTopBar: function (config) {
		return [{
			text: '<i class="icon icon-plus"></i>&nbsp;&nbsp;' + _('relinked_item_create'),
			handler: this.createItem,
			scope: this
		}, '->', {
            xtype: 'modx-combo-context',
			name: 'context',
			width: 200,
			id: config.id + '-context-field',
			emptyText: _('context'),
			listeners: {
				select: {
					fn: function (tf) {
					    this._contextFilter(tf);
					}, scope: this
				}
			}
		}, {
			xtype: 'textfield',
			name: 'query',
			width: 200,
			id: config.id + '-search-field',
			emptyText: _('relinked_grid_search'),
			listeners: {
				render: {
					fn: function (tf) {
						tf.getEl().addKeyListener(Ext.EventObject.ENTER, function () {
							this._doSearch(tf);
						}, this);
					}, scope: this
				}
			}
		}, {
			xtype: 'button',
			id: config.id + '-search-clear',
			text: '<i class="icon icon-times"></i>',
			listeners: {
				click: {fn: this._clearSearch, scope: this}
			}
		}];
	},

	onClick: function (e) {
		var elem = e.getTarget();
		if (elem.nodeName == 'BUTTON') {
			var row = this.getSelectionModel().getSelected();
			if (typeof(row) != 'undefined') {
				var action = elem.getAttribute('action');
				if (action == 'showMenu') {
					var ri = this.getStore().find('id', row.id);
					return this._showMenu(this, ri, e);
				}
				else if (typeof this[action] === 'function') {
					this.menu.record = row.data;
					return this[action](this, e);
				}
			}
		}
		return this.processEvent('click', e);
	},

	_getSelectedIds: function () {
		var ids = [];
		var selected = this.getSelectionModel().getSelections();

		for (var i in selected) {
			if (!selected.hasOwnProperty(i)) {
				continue;
			}
			ids.push(selected[i]['id']);
		}

		return ids;
	},

	_contextFilter: function (tf, nv, ov) {
		this.getStore().baseParams.linkctx = tf.getValue();
		this.getBottomToolbar().changePage(1);
		this.refresh();
	},
	
	_doSearch: function (tf, nv, ov) {
		this.getStore().baseParams.query = tf.getValue();
		this.getBottomToolbar().changePage(1);
		this.refresh();
	},

	_clearSearch: function (btn, e) {
		this.getStore().baseParams.query = this.getStore().baseParams.linkctx = '';
		Ext.getCmp(this.config.id + '-search-field').setValue('');
		Ext.getCmp(this.config.id + '-context-field').setValue('');
		this.getBottomToolbar().changePage(1);
		this.refresh();
	}
});
Ext.reg('relinked-grid-items', reLinked.grid.Items);


MODx.combo.Context = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        valueField: 'key'
        ,displayField: 'key'
        ,fields: ['key']
        ,url: MODx.config.connectors_url+'resource/index.php'
        ,baseParams: {
                action: 'context/getlist'
                ,exclude: 'mgr'
                ,limit:0
        }
        ,tpl: new Ext.XTemplate('<tpl for=".">'
            ,'<div class="x-combo-list-item">'
            ,'<h4 class="modx-combo-title">{key}</h4>'
            ,'</div></tpl>')
    });
    MODx.combo.Context.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Context,MODx.combo.ComboBox);
Ext.reg('modx-combo-context',MODx.combo.Context);