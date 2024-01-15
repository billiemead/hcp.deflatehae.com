(function($){
	"use strict";
	
	var EdgeSidebar = function(){

		this.is_block_widget = ! $( '.widget-liquid-right' ).length;
		this.widget_wrap     = $('.widget-liquid-right, .block-editor-writing-flow');
		this.widget_area     = $('#widgets-right');
		this.widget_add      = $('#edgt-add-widget');

		this.create_form();
		if ( this.is_block_widget ) {
			this.add_del_button();
		} else {
			this.add_legacy_del_button();
		}
		this.bind_events();
	};
	
	EdgeSidebar.prototype = {

		create_form: function(){
			this.widget_wrap.append(this.widget_add.html());
			this.widget_name = this.widget_wrap.find('input[name="edgt-sidebar-widgets"]');
			this.nonce = this.widget_wrap.find('input[name="edgt-delete-sidebar"]').val();
		},

		add_legacy_del_button: function() {
			this.widget_area.find('.sidebar-edgt-custom').append('<span class="edgt-delete-button"></span>');
		},

		add_del_button: function() {
			this.widget_area.find('.sidebar-edgt-custom').append('<span class="edgt-delete-button"></span>');

			var $gutenbergWidgetsArea = $( '.block-editor-writing-flow' );

			if ( $gutenbergWidgetsArea.length ) {
				var customSidebars = typeof edgtf === 'object' && typeof edgtf.customSidebars !== 'undefined' ? edgtf.customSidebars : [];

				if ( customSidebars.length ) {
					for ( const customSidebar of customSidebars ) {
						// Timeout is set in order to panel loaded
						setTimeout(
							function () {
								var customWidgetArea = $gutenbergWidgetsArea.find( '[data-widget-area-id="' + customSidebar.toLowerCase().replaceAll(' ', '-') + '"]' );

								if ( customWidgetArea.length ) {
									customWidgetArea.parents( '.components-panel__body' ).children( '.components-panel__body-title' ).append( '<span class="edgt-delete-button"></span>' );
								}
							},
							3000
						);
					}
				}
			}
		},

		bind_events: function(){
			this.widget_wrap.on('click', '.edgt-delete-button', $.proxy( this.delete_sidebar, this));
		},

		delete_sidebar: function(e){
			var widget = $(e.currentTarget).parents('.widgets-holder-wrap:eq(0)'),
			title = widget.find('.sidebar-name h2'),
			spinner = title.find('.spinner'),
			widget_name = $.trim(title.text()),
			obj = this;

			if( this.is_block_widget ) {
				widget      = $( e.currentTarget ).parents( '.block-editor-block-list__block' );
				var $sidebarName = widget.find( '.components-panel__body-title' );
				widget_name  = $.trim( $sidebarName.text() );
			} else {
				widget = $(e.currentTarget).parents('.widgets-holder-wrap:eq(0)');
				title = widget.find('.sidebar-name h2');
			}

			$.ajax({
				type: "POST",
				url: window.ajaxurl,
				data: {
					action: 'edgt_ajax_delete_custom_sidebar',
					name: widget_name,
					_wpnonce: obj.nonce
				},

				beforeSend: function(){
					spinner.addClass('activate_spinner');
				},
				success: function(response){     
					if(response === 'sidebar-deleted'){
						widget.slideUp(200, function(){

						$('.widget-control-remove', widget).trigger('click'); //delete all widgets inside
							widget.remove();
							wpWidgets.saveOrder();
						});
					}
				}
			});
		}
	};
	
	$(function() {
		setTimeout(() => {
			new EdgeSidebar();
		}, 3000);

 	});
	
})(jQuery);	 