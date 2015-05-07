reLinked.panel.Import = function (config) {
    config = config || {};
    if (!config.id) {
		config.id = 'relinked-import-panel';
	}
    Ext.apply(config, {
		baseCls: 'modx-formpanel',
		layout: 'anchor',
		hideMode: 'offsets',
		items: [{
            xtype: 'textarea',
            fieldLabel: _('relinked_csv'), 
            name: 'csv',
            id: config.id + '-import-csv',
            labelSeparator: '',
            anchor: '100%',
            height: 150,
            allowBlank: false,
            blankText: _('relinked_nocsv')
        }, {
            xtype: 'textfield',
            fieldLabel: _('relinked_csvfile'),
            name: 'csv-file',
            id: config.id + '-csv-file',
            inputType: 'file',
            style: {display: 'none'},
			listeners: {
				afterrender: {fn: this._fileInputAfterRender, scope: this}
			}
        }, {
            xtype: 'button',
            text: _('relinked_import_start'),
            fieldLabel: _('relinked_import_start'),
            name: 'start-import',
            id: config.id + '-start-import',
            style: {margin: '15px 0 15px 15px', float: 'right'},
            cls: 'primary-button',
			listeners: {
				click: {fn: this._startImport, scope: this}
			}
        }, {
            xtype: 'button',
            text: _('relinked_import_file_select'),
            fieldLabel: _('relinked_import_file_select'),
            name: 'csv-file-btn',
            id: config.id + '-csv-file-btn',
            style: {margin: '15px 15px 15px 0', display: 'inline-block'},
			listeners: {
				click: {fn: this._selectCSV, scope: this}
			}
        }, {
			id: config.id + '-csv-filename-holder',
			anchor: '50%',
			style: {margin: '15px 15px 15px 0', display: 'inline-block', padding: '12px 10px', 'vertical-align': 'top'},
		}]
	});
	reLinked.panel.Import.superclass.constructor.call(this, config);
};
Ext.extend(reLinked.panel.Import, MODx.Panel, {
    _selectCSV: function() {
        document.getElementById(this.config.id + '-csv-file').click();
    },
    
    _fileInputAfterRender: function() {
        document.getElementById(this.config.id + '-csv-file').addEventListener('change', this._showFileName, false);
    },
    
    _showFileName: function(e) {
        document.getElementById(e.target.id + 'name-holder').innerHTML = this.files[0].name;
        document.getElementById(e.target.id + '-btn').classList.add('x-item-disabled');
        e.target.setAttribute("disabled", "disabled");
    },
    
    _startImport: function() {
        alert("startuyu");
    }
    
});
Ext.reg('relinked-import-panel', reLinked.panel.Import);