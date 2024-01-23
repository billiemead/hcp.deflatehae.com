(function ( $ ) {
	"use strict";

	$(
		function () {
				// Backbone view for inner element settings.
				window.vc.innerElementView = window.vc.PanelView.vcExtendUI( vc.HelperAjax ).vcExtendUI( vc.ExtendPresets ).vcExtendUI( vc.ExtendTemplates ).vcExtendUI( vc.HelperPrompts ).extend(
					{
						panelName: 'edit_element',
						el: '#vc_properties-panel',
						contentSelector: '.vc_ui-panel-content.vc_properties-list',
						minimizeButtonSelector: '[data-vc-ui-element="button-minimize"]',
						closeButtonSelector: '[data-vc-ui-element="button-close"]',
						titleSelector: '.vc_panel-title',
						tabsInit: ! 1,
						doCheckTabs: ! 0,
						$tabsMenu: ! 1,
						dependent_elements: {},
						mapped_params: {},
						draggable: ! 1,
						panelInit: ! 1,
						$spinner: ! 1,
						active_tab_index: 0,
						buttonMessageTimeout: ! 1,
						notRequestTemplate: ! 1,
						requiredParamsInitialized: ! 1,
						currentModelParams: ! 1,
						customButtonMessageTimeout: ! 1,
						events: {
							'click [data-vc-ui-element="panel-tab-control"]': 'changeTab'
						},
						initialize: function() {
							_.bindAll( this, 'setSize', 'setTabsSize', 'fixElContainment', 'hookDependent', 'resetAjax', 'removeAllPrompts' ), this.on( 'setSize', this.setResize, this ), this.on( 'render', this.resetMinimize, this ), this.on( 'render', this.setTitle, this ), this.on( 'render', this.prepareContentBlock, this )
						},
						setCustomButtonMessage: function( $btn, message, type, showInBackend ) {
							var currentTextHtml;
							return void 0 === $btn && ($btn = this.$el.find( '[data-vc-ui-element="button-save"]' )), void 0 === showInBackend && (showInBackend = ! 1), this.clearCustomButtonMessage = _.bind( this.clearCustomButtonMessage, this ), ! showInBackend && ! vc.frame_window || this.customButtonMessageTimeout || (void 0 === message && (message = window.i18nLocale.ui_saved), void 0 === type && (type = "success"), currentTextHtml = $btn.html(), $btn.addClass( "vc_ui-button-" + type + " vc_ui-button-undisabled" ).removeClass( "vc_ui-button-action" ).data( "vcCurrentTextHtml", currentTextHtml ).data( "vcCurrentTextType", type ).html( message ), _.delay( this.clearCustomButtonMessage.bind( this, $btn ), 5e3 ), this.customButtonMessageTimeout = ! 0), this
						},
						clearCustomButtonMessage: function($btn) {
							var type, currentTextHtml;
							this.customButtonMessageTimeout && (window.clearTimeout( this.customButtonMessageTimeout ), currentTextHtml = $btn.data( "vcCurrentTextHtml" ) || "Save", type = $btn.data( "vcCurrentTextType" ), $btn.html( currentTextHtml ).removeClass( "vc_ui-button-" + type + " vc_ui-button-undisabled" ).addClass( "vc_ui-button-action" ), this.customButtonMessageTimeout = ! 1)
						},
						render: function(model, not_request_template) {
							var params;
							return this.$el.is( ":hidden" ), not_request_template && (this.notRequestTemplate = ! 0), this.model = model, this.currentModelParams = this.model.get( "params" ), (vc.active_panel = this).resetMinimize(), this.clicked = ! 1, this.$el.css( "height", "auto" ), this.$el.css( "maxHeight", "75vh" ), params = this.model.setting( "params" ) || [], this.$el.attr( "data-vc-shortcode", this.model.get( "shortcode" ) ), this.tabsInit = ! 1, this.panelInit = ! 1, this.active_tab_index = 0, this.requiredParamsInitialized = ! 1, this.mapped_params = {}, this.dependent_elements = {}, _.each(
								params, function(param) {
									this.mapped_params[param.param_name] = param
								}, this
							), this.trigger( "render" ),this.checkAjax(), this.ajax                           = $.ajax(
								{
									type: "POST",
									url: window.ajaxurl,
									data: this.ajaxData(),
									context: this
								}
							).done( this.buildParamsContent ).always( this.resetAjax ), this.$el
						},
						prepareContentBlock: function() {
							this.$content = this.notRequestTemplate ? this.$el : this.$el.find( this.contentSelector ).removeClass( "vc_with-tabs" ), this.$content.empty(), this.$spinner = $( '<span class="vc_ui-wp-spinner vc_ui-wp-spinner-lg"></span>' ), this.$content.prepend( this.$spinner )
						},
						buildParamsContent: function(data) {
							var $data, $tabs, $panelHeader;
							($tabs = ($data = $( data )).find( '[data-vc-ui-element="panel-tabs-controls"]' )).find( ".vc_edit-form-tab-control:first-child" ).addClass( "vc_active" ), $panelHeader = this.$el.find( '[data-vc-ui-element="panel-header-content"]' ), $tabs.prependTo( $panelHeader ), this.$content.html( $data ), this.$content.removeAttr( "data-vc-param-initialized" ), this.active_tab_index = 0, this.tabsInit = ! 1, this.panelInit = ! 1, this.dependent_elements = {}, this.requiredParamsInitialized = ! 1, this.$content.find( "[data-vc-param-initialized]" ).removeAttr( "data-vc-param-initialized" ), this.init(), this.$content.parent().scrollTop( 1 ).scrollTop( 0 ), this.$content.removeClass( "vc_properties-list-init" ), this.$el.trigger( "vcPanel.shown" ), this.trigger( "afterRender" );

							var activeTab = this.$el.find( '.vc_active > button' ).attr( 'data-vc-ui-element-target' );
							this.$content.find( activeTab ).addClass( 'vc_active' );
						},
						resetMinimize: function() {
							this.$el.removeClass( "vc_panel-opacity" )
						},
						ajaxData: function() {
							var parent_tag, parent_id, params, mergedParams;
							return parent_tag = (parent_id = this.model.get( "parent_id" )) ? this.model.collection.get( parent_id ).get( "shortcode" ) : null, params = this.model.get( "params" ), mergedParams = vc.getMergedParams( this.model.get( "shortcode" ), _.extend( {}, vc.getDefaults( this.model.get( "shortcode" ) ), params ) ), _.isUndefined( params.content ) || (mergedParams.content = params.content), {
								action: "vc_edit_form",
								tag: this.model.get( "shortcode" ),
								parent_tag: parent_tag,
								post_id: vc_post_id,
								params: mergedParams,
								_vcnonce: window.vcAdminNonce
							}
						},
						init: function() {
							vc.EditElementPanelView.__super__.init.call( this ), this.initParams(), this.initDependency();
							$( ".wpb_edit_form_elements .textarea_html" ).each(
								function() {
									window.init_textarea_html( $( this ) )
								}
							), this.trigger( "init" ), this.panelInit = ! 0
						},
						initParams: function() {
							var _this                    = this,
							$content                     = this.content().find( '#vc_edit-form-tabs [data-vc-ui-element="panel-edit-element-tab"]:eq(' + this.active_tab_index + ")" );
							$content.length || ($content = this.content()), $content.attr( "data-vc-param-initialized" ) || ($( '[data-vc-ui-element="panel-shortcode-param"]', $content ).each(
								function() {
									var $field, param;
									($field = $( this )).data( "vcInitParam" ) || (param = $field.data( "param_settings" ), vc.atts.init.call( _this, param, $field ), $field.data( "vcInitParam", ! 0 ))
								}
							), $content.attr( "data-vc-param-initialized", ! 0 )), this.requiredParamsInitialized || _.isUndefined( vc.required_params_to_init ) || ($( '[data-vc-ui-element="panel-shortcode-param"]', this.content() ).each(
								function() {
									var $field, param;
									! ($field = $( this )).data( "vcInitParam" ) && -1 < _.indexOf( vc.required_params_to_init, $field.data( "param_type" ) ) && (param = $field.data( "param_settings" ), vc.atts.init.call( _this, param, $field ), $field.data( "vcInitParam", ! 0 ))
								}
							), this.requiredParamsInitialized = ! 0)
						},
						initDependency: function() {
							var callDependencies = {};
							_.each(
								this.mapped_params, function(param) {
									if (_.isObject( param ) && _.isObject( param.dependency )) {
										  var $masters, $slave, rules = param.dependency;
										if (_.isString( param.dependency.element )) {
											$masters = $( "[name=" + param.dependency.element + "].wpb_vc_param_value", this.$content ), $slave = $( "[name= " + param.param_name + "].wpb_vc_param_value", this.$content ), _.each(
												$masters, function(master) {
													var $master, name;
													name = ($master = $( master )).attr( "name" ), _.isArray( this.dependent_elements[$master.attr( "name" )] ) || (this.dependent_elements[$master.attr( "name" )] = []), this.dependent_elements[$master.attr( "name" )].push( $slave ), $master.data( "dependentSet" ) || ($master.attr( "data-dependent-set", "true" ), $master.off( "keyup change", this.hookDependent ).on( "keyup change", this.hookDependent )), callDependencies[name] || (callDependencies[name] = $master)
												}, this
											);
										}
										_.isString( rules.callback ) && window[rules.callback].call( this )
									}
								}, this
							), this.doCheckTabs  = ! 1, _.each(
								callDependencies, function(obj) {
									this.hookDependent(
										{
											currentTarget: obj
										}
									)
								}, this
							), this.doCheckTabs  = ! 0, this.checkTabs(), callDependencies = null
						},
						hookDependent: function(e) {
							var $master, $master_container, is_empty, dependent_elements, master_value, checkTabs;
							return $master_container                            = ($master = $( e.currentTarget )).closest( ".vc_column" ), dependent_elements = this.dependent_elements[$master.attr( "name" )], master_value = $master.is( ":checkbox" ) ? _.map(
								this.$content.find( "[name=" + $( e.currentTarget ).attr( "name" ) + "].wpb_vc_param_value:checked" ), function(element) {
									return $( element ).val()
								}
							) : $master.val(), checkTabs                        = this.doCheckTabs, this.doCheckTabs = ! 1, is_empty = $master.is( ":checkbox" ) ? ! this.$content.find( "[name=" + $master.attr( "name" ) + "].wpb_vc_param_value:checked" ).length : ! master_value.length, $master_container.hasClass( "vc_dependent-hidden" ) ? _.each(
								dependent_elements, function($element) {
									var event        = jQuery.Event( "change" );
									event.extra_type = "vcHookDepended", $element.closest( ".vc_column" ).addClass( "vc_dependent-hidden" ), $element.trigger( event )
								}
							) : _.each(
								dependent_elements, function($element) {
									var param_name = $element.attr( "name" ),
									rules          = _.isObject( this.mapped_params[param_name] ) && _.isObject( this.mapped_params[param_name].dependency ) ? this.mapped_params[param_name].dependency : {},
									$param_block   = $element.closest( ".vc_column" );
									_.isBoolean( rules.not_empty ) && ! 0 === rules.not_empty && ! is_empty || _.isBoolean( rules.is_empty ) && ! 0 === rules.is_empty && is_empty || rules.value && _.intersection( _.isArray( rules.value ) ? rules.value : [rules.value], _.isArray( master_value ) ? master_value : [master_value] ).length || rules.value_not_equal_to && ! _.intersection( _.isArray( rules.value_not_equal_to ) ? rules.value_not_equal_to : [rules.value_not_equal_to], _.isArray( master_value ) ? master_value : [master_value] ).length ? $param_block.removeClass( "vc_dependent-hidden" ) : $param_block.addClass( "vc_dependent-hidden" );
									var event        = jQuery.Event( "change" );
									event.extra_type = "vcHookDepended", $element.trigger( event )
								}, this
							), checkTabs && (this.checkTabs(), this.doCheckTabs = ! 0), this
						},
						checkTabs: function() {
							var that                                = this;
							! 1 === this.tabsInit && (this.tabsInit = ! 0, this.$content.hasClass( "vc_with-tabs" ) && (this.$tabsMenu = this.$content.find( ".vc_edit-form-tabs-menu" ))), this.$tabsMenu && (this.$content.find( '[data-vc-ui-element="panel-edit-element-tab"]' ).each(
								function(index) {
									var $tabControl = that.$tabsMenu.find( '> [data-tab-index="' + index + '"]' );
									$( this ).find( '[data-vc-ui-element="panel-shortcode-param"]:not(".vc_dependent-hidden")' ).length ? $tabControl.hasClass( "vc_dependent-hidden" ) && ($tabControl.removeClass( "vc_dependent-hidden" ).removeClass( "vc_tab-color-animated" ).addClass( "vc_tab-color-animated" ), window.setTimeout(
										function() {
											$tabControl.removeClass( "vc_tab-color-animated" )
										}, 200
									)) : $tabControl.addClass( "vc_dependent-hidden" )
								}
							), window.setTimeout( this.setTabsSize, 100 ))
						},
						changeTab: function(e) {
							var $view = this,
							$tab;
							e && e.preventDefault && e.preventDefault();
							$tab = $( e.currentTarget );
							jQuery( '[data-vc-ui-element="panel-shortcode-param"]', this.content() ).each(
								function() {
									var $field, param;
									($field = jQuery( this )).data( "vcInitParam" ) || (param = $field.data( "param_settings" ), vc.atts.init.call( $view, param, $field ), $field.data( "vcInitParam", ! 0 ));
								}
							);
							$tab.parent().hasClass( "vc_active" ) || (this.$el.find( '[data-vc-ui-element="panel-tabs-controls"] .vc_active:not([data-vc-ui-element="panel-tabs-line-dropdown"])' ).removeClass( "vc_active" ), $tab.parent().addClass( "vc_active" ), this.$el.find( '[data-vc-ui-element="panel-edit-element-tab"].vc_active' ).removeClass( "vc_active" ), this.$el.find( $tab.data( "vcUiElementTarget" ) ).addClass( "vc_active" ), this.$tabsMenu && this.$tabsMenu.vcTabsLine( "checkDropdownContainerActive" ))
						},
						setTabsSize: function() {
							this.$tabsMenu.parents( ".vc_with-tabs.vc_panel-body" ).css( "margin-top", this.$tabsMenu.outerHeight() )
						},
						setActive: function() {
							this.$el.prev().addClass( "active" )
						},
						window: function() {
							return window
						},
						getParams: function() {
							var paramsSettings;
							return paramsSettings = this.mapped_params, this.params = _.extend( {}, this.model.get( "params" ) ), _.each(
								paramsSettings, function(param) {
									var value;
									value = vc.atts.parseFrame.call( this, param ), this.params[param.param_name] = value
								}, this
							), _.each(
								vc.edit_form_callbacks, function(callback) {
									callback.call( this )
								}, this
							), this.params
						},
						content: function() {
							return this.$content
						},
						save: function() {
							if (this.panelInit) {
								var shortcode = this.model.get( "shortcode" ),
								params        = this.getParams(),
								mergedParams  = _.extend( {}, vc.getDefaults( shortcode ), vc.getMergedParams( shortcode, params ) );
								_.isUndefined( params.content ) || (mergedParams.content = params.content), this.model.save(
									{
										params: mergedParams
									  }
								), this.showMessage( window.sprintf( window.i18nLocale.inline_element_saved, vc.getMapped( shortcode ).name ), "success" ), vc.frame_window || this.hide(), this.trigger( "save" )
							}
						},
						show: function() {
							this.$el.hasClass( "vc_active" ) || (this.$el.addClass( "vc_active" ), this.draggable || this.initDraggable(), this.fixElContainment(), this.trigger( "show" ))
						},
						hide: function(e) {
							e && e.preventDefault && e.preventDefault(), this.checkAjax(), this.ajax = ! 1, this.model && (this.model = null), vc.active_panel = ! 1, this.currentModelParams = ! 1, this._killEditor(), this.$el.removeClass( "vc_active" ), this.$el.find( ".vc_properties-list" ).removeClass( "vc_with-tabs" ).css( "margin-top", "auto" ), this.$content.empty(), this.trigger( "hide" )
						},
						setTitle: function() {
							return this.$el.find( this.titleSelector ).text( vc.getMapped( this.model.get( "shortcode" ) ).name + " " + window.i18nLocale.settings ), this
						},
						_killEditor: function() {
							_.isUndefined( window.tinyMCE ) || $( "textarea.textarea_html", this.$el ).each(
								function() {
									var id = $( this ).attr( "id" );
									"4" === tinymce.majorVersion ? window.tinyMCE.execCommand( "mceRemoveEditor", ! 0, id ) : window.tinyMCE.execCommand( "mceRemoveControl", ! 0, id )
								}
							), jQuery( "body" ).off( "click.wpcolorpicker" )
						}
					}
				);

				/*
				Class used in edit form and editor models to save/render param type
				*/
				window.vc.atts.ee_inner_element = {
					init: function() {
						var elementSettings = '',
							$view           = this;

						jQuery( 'body' ).find( '.elegant-elements-add-shortcode' ).unbind().on(
							'click', function( event ) {
								var tag        = jQuery( this ).data( 'tag' ),
								editTitle      = jQuery( this ).data( 'edit-title' ),
								editorID       = jQuery( this ).data( 'editor-id' ),
								editorContent  = $view.$el.find( '#' + editorID ).val(),
								settingsHolder = jQuery( '.elegant-element-shortcode-generator' ),
								elementSettings,
								options,
								shortcodeRegex = wp.shortcode.regexp( tag ),
								matches        = shortcodeRegex.exec( editorContent ),
								namedAttrs,
								modalID;

								event.preventDefault();

								var modalHTML = '';
								modalHTML    += '<div class="elegant-inner-element-editor-dialog-overlay"></div>';
								modalHTML    += '<div class="elegant-inner-element-editor-dialog vc_ui-panel vc_active">';
								modalHTML    += '<div class="vc_ui-panel-window-inner">';
								modalHTML    += '<div class="vc_ui-post-settings-header-container elegant-inner-panel-heading vc_ui-panel-header-container vc_ui-panel-header-o-stacked-bottom">'
								modalHTML    += '<div class="vc_ui-panel-header">';
								modalHTML    += '<div class="vc_ui-panel-header-controls">';
								modalHTML    += '<button type="button" class="vc_general vc_ui-control-button vc_ui-close-button elegant-inner-element-dialog-close" data-vc-ui-element="button-close">';
								modalHTML    += '<i class="vc-composer-icon vc-c-icon-close"></i></button>';
								modalHTML    += '</div>'; // panel-header-controls
								modalHTML    += '<div class="vc_ui-panel-header-header vc_ui-grid-gap" data-vc-panel-container=".vc_ui-panel-header-container">';
								modalHTML    += '<h3 class="vc_ui-panel-header-heading" data-vc-ui-element="panel-title">' + editTitle + '</h3>';
								modalHTML    += '</div>'; // panel-header-header
								modalHTML    += '<div class="vc_ui-panel-header-content" data-vc-ui-element="panel-header-content"></div>';
								modalHTML    += '</div>'; // panel-header-container
								modalHTML    += '</div>'; // Panel-header
								modalHTML    += '<div class="vc_ui-panel-content-container"> <div class="vc_ui-panel-content vc_properties-list vc_edit_form_elements elegant-inner-element-settings"><div style="width:100%;display: flex;align-items: center;justify-content: center;height: 100%;"><span class="spinner" style="visibility:visible;"></span></div></div></div>';
								modalHTML    += '<div class="vc_ui-panel-footer-container">';
								modalHTML    += '<div class="vc_ui-panel-footer">';
								modalHTML    += '<div class="vc_ui-button-group">';
								modalHTML    += '<span class="vc_general vc_ui-button vc_ui-button-default vc_ui-button-shape-rounded vc_ui-button-fw elegant-inner-element-dialog-close" data-vc-ui-element="button-close">Close</span>';
								modalHTML    += '<span class="vc_general vc_ui-button vc_ui-button-action vc_ui-button-shape-rounded vc_ui-button-fw elegant-inner-element-dialog-save" data-vc-ui-element="button-save">Save changes</span>';
								modalHTML    += '</div>';
								modalHTML    += '</div>';
								modalHTML    += '</div>';
								modalHTML    += '</div>'; // panel-window-inner
								modalHTML    += '</div>'; // editor-dialog

								// Insert the modal.
								if ( '' === settingsHolder.html() ) {
									settingsHolder.html( modalHTML );
								} else {
									return false;
								}

								// Set shortcode attributes for VC.
								options = {
									shortcode: tag,
									parent_id: null,
									startTab: 0
								};

								if ( '' !== editorContent ) {
									shortcodeRegex = wp.shortcode.regexp( tag );
									matches        = shortcodeRegex.exec( editorContent );
									namedAttrs     = '';

									if ( matches ) {
										namedAttrs     = wp.shortcode.attrs( matches[3] ).named;
										options.params = namedAttrs;
									}
								}

									// Create shortcode object.
									elementSettings = vc.shortcodes.create( options );

								if ( '' !== editorContent ) {
									shortcodeRegex = wp.shortcode.regexp( tag );
									matches        = shortcodeRegex.exec( editorContent );
									namedAttrs     = '';

									if ( matches ) {
										namedAttrs                        = wp.shortcode.attrs( matches[3] ).named;
										elementSettings.attributes.params = namedAttrs;
									}
								}

									// Generate VC backbone view model.
									vc.elemSettingsView = new vc.innerElementView(
										{
											el: ".elegant-inner-element-editor-dialog"
										}
									);

								// Render the settings with params and their dependencies.
								vc.elemSettingsView.render( elementSettings );

								// Remove the element from builder.
								modalID = vc.elemSettingsView.model.get( 'id' );
								jQuery( '[data-model-id="' + modalID + '"]' ).remove();

								// Close inner element settings dialog.
								jQuery( 'body' ).find( '.elegant-inner-element-dialog-close' ).on(
									'click', function() {
										jQuery( this ).parents( '.elegant-inner-element-editor-dialog' ).remove();
										jQuery( '.elegant-inner-element-editor-dialog-overlay' ).remove();
									}
								);

								// Generate and save element shortcode.
								jQuery( 'body' ).find( '.elegant-inner-element-dialog-save' ).on(
									'click', function() {
										var elementSettings = jQuery( this ).parents( '.vc_ui-panel-window-inner' ),
										formElements        = elementSettings.find( '.wpb_vc_param_value' ),
										shortcode           = '';

										// Loop through all the element params and generate shortcode string.
										jQuery.each(
											formElements, function( index, element ) {
												var name = jQuery( this ).attr( 'name' ),
												value    = jQuery( this ).val();

												shortcode += ' ' + name + '="' + value + '"';
											}
										);

										// Generate shortcode and insert into the shortcode field.
										shortcode = '[' + tag + shortcode + ']'
										jQuery( '#' + editorID ).val( shortcode );

										// Close the dialog after shortcode insert.
										jQuery( this ).parents( '.elegant-inner-element-editor-dialog' ).remove();
										jQuery( '.elegant-inner-element-editor-dialog-overlay' ).remove();
									}
								);
							}
						);
					},

					/**
					 * Parse value before saving to shortcode.
					 * @param param
					 * @returns {string}
					 */
					parse: function(param) {
						var new_value;
						return new_value = this.content().find( ".wpb_vc_param_value[name=" + param.param_name + "]" ).val(), base64_encode( rawurlencode( new_value ) );
					},

					/**
					 * Used in shortcode saving
					 * Default: '' empty (unchecked)
					 * Can be overwritten by 'std'
					 * @param param
					 * @returns {string}
					 */
					defaults: function ( param ) {
						return ''; // needed for saving - without this default value for param will be first value in array
					}
			};
		}
	);
} )( window.jQuery );
