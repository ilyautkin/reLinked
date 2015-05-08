reLinked.panel.Import = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'relinked-import-panel';
    }
    Ext.apply(config, {
    	baseCls: 'modx-formpanel',
        url: reLinked.config.connector_url,
        config: config,
		layout: 'anchor',
		hideMode: 'offsets',
		fileUpload: true,
		baseParams: {
			action: 'mgr/link/import'
		},

		items: [/*{
            xtype: 'textarea',
            fieldLabel: _('relinked_csv'), 
            name: 'csv',
            id: config.id + '-import-csv',
            labelSeparator: '',
            anchor: '100%',
            autoHeight: false,
            height: 250,
            allowBlank: true,
            blankText: _('relinked_nocsv')
        }, */{
            xtype: 'fileuploadfield',
            fieldLabel: _('relinked_csvfile'),
            name: 'csv-file',
            id: config.id + '-csv-file',
            //inputType: 'file',
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
			id: config.id + '-csv-file-filename-holder',
			anchor: '50%',
			style: {margin: '15px 15px 15px 0', display: 'inline-block', padding: '12px 10px', 'vertical-align': 'top'},
		}]
	});
	reLinked.panel.Import.superclass.constructor.call(this, config);
};
Ext.extend(reLinked.panel.Import, MODx.FormPanel, {
    _selectCSV: function() {
        document.getElementById(this.config.id + '-csv-file-file').click();
    },
    
    _fileInputAfterRender: function() {
        document.getElementById(this.config.id + '-csv-file-file').addEventListener('change', this._showFileName, false);
        document.getElementById(this.config.id + '-csv-file-file').style.display = "none";
        document.getElementById(this.config.id + '-csv-file-file').nextSibling.style.display = "none";
    },
    
    _showFileName: function(e) {
        document.getElementById(e.target.id + 'name-holder').innerHTML = this.files[0].name;
        document.getElementById(e.target.id + '-btn').classList.add('x-item-disabled');
        e.target.setAttribute("disabled", "disabled");
    },
    
    _startImport: function() {
        Ext.getCmp(this.config.id).form.submit({
            url: reLinked.config.connector_url,
            success: function(form, response){
                //console.log(response.result);
			    Ext.getCmp(form.config.id + '-import-csv').setValue(JSON.stringify(response.result));
            },
            failure: function(form, response){
                for (i=0;i<response.result.errors.length;i++) {
                    //console.log(response.result.errors[i]);
                    if (response.result.errors[i].id == 'csv-file-btn') {
                        document.getElementById(form.config.id + '-csv-file-filename-holder').innerHTML =
                            '<span class="red">' + response.result.errors[i].msg + '</span>';
                        document.getElementById(form.config.id + '-csv-file-file-btn').classList.remove('x-item-disabled');
                        document.getElementById(form.config.id + '-csv-file-file').removeAttribute("disabled");
                    }
                }
                //Ext.MessageBox.alert('Ошибка авторизации. ',response.result.message);
            }
        });
    }
    
});
Ext.reg('relinked-import-panel', reLinked.panel.Import);