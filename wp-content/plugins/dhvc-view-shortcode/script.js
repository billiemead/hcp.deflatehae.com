
/* ========================================================================
 * Bootstrap: transition.js v3.3.4
 * http://getbootstrap.com/javascript/#transitions
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */

+function(n){"use strict";function t(){var n=document.createElement("bootstrap"),t={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd otransitionend",transition:"transitionend"};for(var i in t)if(void 0!==n.style[i])return{end:t[i]};return!1}n.fn.emulateTransitionEnd=function(t){var i=!1,r=this;n(this).one("bsTransitionEnd",function(){i=!0});var e=function(){i||n(r).trigger(n.support.transition.end)};return setTimeout(e,t),this},n(function(){n.support.transition=t(),n.support.transition&&(n.event.special.bsTransitionEnd={bindType:n.support.transition.end,delegateType:n.support.transition.end,handle:function(t){return n(t.target).is(this)?t.handleObj.handler.apply(this,arguments):void 0}})})}(jQuery);
/* ========================================================================
 * Bootstrap: modal.js v3.1.1
 * http://getbootstrap.com/javascript/#modals
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */
+function(t){"use strict";var e=function(e,o){this.options=o,this.$element=t(e),this.$backdrop="",this.isShown=null,this.options.remote&&this.$element.find(".modal-content").load(this.options.remote,t.proxy(function(){this.$element.trigger("loaded.bs.modal")},this))};e.DEFAULTS={backdrop:!0,keyboard:!0,show:!0},e.prototype.toggle=function(t){return this[this.isShown?"hide":"show"](t)},e.prototype.show=function(e){var o=this,s=t.Event("show.bs.modal",{relatedTarget:e});this.$element.trigger(s),this.isShown||s.isDefaultPrevented()||(this.isShown=!0,this.escape(),this.$element.on("click.dismiss.bs.modal",'[data-dismiss="modal"]',t.proxy(this.hide,this)),this.backdrop(function(){var s=t.support.transition&&o.$element.hasClass("fade");o.$element.parent().length||o.$element.appendTo(document.body),o.$element.show().scrollTop(0),s&&o.$element[0].offsetWidth,o.$element.addClass("in").attr("aria-hidden",!1),o.enforceFocus();var i=t.Event("shown.bs.modal",{relatedTarget:e});s?o.$element.find(".modal-dialog").one(t.support.transition.end,function(){o.$element.focus().trigger(i)}).emulateTransitionEnd(300):o.$element.focus().trigger(i)}))},e.prototype.hide=function(e){e&&e.preventDefault(),e=t.Event("hide.bs.modal"),this.$element.trigger(e),this.isShown&&!e.isDefaultPrevented()&&(this.isShown=!1,this.escape(),t(document).off("focusin.bs.modal"),this.$element.removeClass("in").attr("aria-hidden",!0).off("click.dismiss.bs.modal"),t.support.transition&&this.$element.hasClass("fade")?this.$element.one(t.support.transition.end,t.proxy(this.hideModal,this)).emulateTransitionEnd(300):this.hideModal())},e.prototype.enforceFocus=function(){t(document).off("focusin.bs.modal").on("focusin.bs.modal",t.proxy(function(t){this.$element[0]===t.target||this.$element.has(t.target).length||this.$element.focus()},this))},e.prototype.escape=function(){this.isShown&&this.options.keyboard?this.$element.on("keyup.dismiss.bs.modal",t.proxy(function(t){27==t.which&&this.hide()},this)):this.isShown||this.$element.off("keyup.dismiss.bs.modal")},e.prototype.hideModal=function(){var t=this;this.$element.hide(),this.backdrop(function(){t.removeBackdrop(),t.$element.trigger("hidden.bs.modal")})},e.prototype.removeBackdrop=function(){this.$backdrop&&this.$backdrop.remove(),this.$backdrop=null},e.prototype.backdrop=function(e){var o=this.$element.hasClass("fade")?"fade":"";if(this.isShown&&this.options.backdrop){var s=t.support.transition&&o;if(this.$backdrop=t('<div class="modal-backdrop '+o+'" />').appendTo(document.body),this.$element.on("click.dismiss.bs.modal",t.proxy(function(t){t.target===t.currentTarget&&("static"==this.options.backdrop?this.$element[0].focus.call(this.$element[0]):this.hide.call(this))},this)),s&&this.$backdrop[0].offsetWidth,this.$backdrop.addClass("in"),!e)return;s?this.$backdrop.one(t.support.transition.end,e).emulateTransitionEnd(150):e()}else!this.isShown&&this.$backdrop?(this.$backdrop.removeClass("in"),t.support.transition&&this.$element.hasClass("fade")?this.$backdrop.one(t.support.transition.end,e).emulateTransitionEnd(150):e()):e&&e()};var o=t.fn.modal;t.fn.modal=function(o,s){return this.each(function(){var i=t(this),n=i.data("bs.modal"),a=t.extend({},e.DEFAULTS,i.data(),"object"==typeof o&&o);n||i.data("bs.modal",n=new e(this,a)),"string"==typeof o?n[o](s):a.show&&n.show(s)})},t.fn.modal.Constructor=e,t.fn.modal.noConflict=function(){return t.fn.modal=o,this},t(document).on("click.bs.modal.data-api",'[data-toggle="modal"]',function(e){var o=t(this),s=o.attr("href"),i=t(o.attr("data-target")||s&&s.replace(/.*(?=#[^\s]+$)/,"")),n=i.data("bs.modal")?"toggle":t.extend({remote:!/#/.test(s)&&s},i.data(),o.data());o.is("a")&&e.preventDefault(),i.modal(n,this).one("hide",function(){o.is(":visible")&&o.focus()})}),t(document).on("show.bs.modal",".modal",function(){t(document.body).addClass("modal-open")}).on("hidden.bs.modal",".modal",function(){t(document.body).removeClass("modal-open")})}(jQuery);

;(function ($) {
	"use strict";
	if(_.isUndefined(vc))
		return ;
	
	var DHVCViewShortcode = Backbone.View.extend({
		 initialize: function() {
			 vc.shortcodes.on('add', this.addShortcode, this);
			 vc.shortcodes.on('reset', this.resetShortcode, this);
			 this.modal_template = $('<div id="dhvc-view-view-shortcode" class="vc_modal fade" style="display:none">\
					 	<div class="vc_modal-dialog modal-dialog" style="width:600px;margin:10% auto 0">\
					 		<div class="vc_modal-content">\
					 			<div class="vc_modal-header">\
					 				<a class="vc_close" aria-hidden="true" data-dismiss="modal" href="#">\
					 					<i class="vc_icon"></i>\
					 				</a>\
					 				<h3 id="dhvc-view-view-shortcode-dialog-title" class="vc_modal-title">Shortcode</h3>\
					 			</div>\
					 			<div class="vc_modal-body">\
		 							<textarea style="width:100%;min-height:200px;resize: none;" onfocus="this.select();" readonly="readonly"></textarea>\
		 						</div>\
					 		</div>\
					 	</div>');
			 if($('#wpb-element-settings-modal-template').length){
					this.modal_template = $('<div id="dhvc-view-view-shortcode" class="modal" style="display:none">\
									            <div class="modal-header">\
									                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>\
									                <h3>Shortcode</h3>\
									            </div>\
									            <div class="modal-body">\
									                <div class="vc_row-fluid">\
														<textarea style="width:100%;min-height:200px;resize: none;" onfocus="this.select();" readonly="readonly"></textarea>\
									                </div>\
									            </div>\
									            <div class="modal-footer text-center">\
									                <button class="button-primary wpb_save_edit_form" data-dismiss="modal" aria-hidden="true">Save</button>\
									                <button class="button" data-dismiss="modal" aria-hidden="true">Cancel</button>\
									          </div>\
									        </div>');
			 }
			 $('body').append(this.modal_template);
		 },
		 escapeParam:function (value) {
			 return _.isString(value) ? value.replace(/"/g, "``") : _.isUndefined(value) || _.isNull(value) || !value.toString ? "" : value.toString().replace(/"/g, "``");
		 },
		 unescapeParam:function (value) {
			 return value.replace(/(\`{2})/g, '"');
		 },
		 addShortcode: function(model){
			 this.addButton(model);
		 },
		 resetShortcode: function(shortcodes){
			 var that = this;
			 _.each(shortcodes.models,function(model){
				 that.addButton(model);
			 });
		 },
		 addButton: function(model){
			 var that = this;
			 var el = $('[data-model-id="'+model.get('id')+'"]');
				el.find('.controls,.vc_controls').each(function(){
					if(!$(this).find('.dhvc-view-shortcode').length){
						$('<a class="vc_control dhvc-view-shortcode column_shortcode" href="#" title="View Shortcode"><i class="vc_icon"></i></a>').insertBefore($(this).find('.column_edit'));
					}
				});
				el.find('.vc_controls').each(function(){
					if(!$(this).find('.dhvc-view-shortcode').length){
						$('<a class="vc_control-btn dhvc-view-shortcode vc_control-btn-shortcode" href="#" title="View Shortcode"><span class="vc_btn-content"><span class="icon-white"></span></span></a>').insertBefore($(this).find('.vc_control-btn-edit'));
					}
				 });
			 $('.dhvc-view-shortcode').on('click',function(e){
				 e.stopPropagation();
				 e.preventDefault();
				
				var parent = $(this).closest('[data-model-id]').data('model');
				
				var models = _.filter(_.values(vc.storage.data), function (model) {
					return model.id === parent.id;
	            });
				
				models = _.sortBy(models, function (model) {
	                return model.order;
	            });
				
				var content = _.reduce(models, function (memo, model) {
	                model.html = that.createShortcodeString(model);
	                return memo + model.html;
	            }, '', this);
				
				$(that.modal_template).find('textarea').text(content);
				$(that.modal_template).modal('show');
			 });
		 },
		createShortcodeString:function (model) {
            var params = _.extend({}, model.params),
                params_to_string = {};
            _.each(params, function (value, key) {
                if (key !== 'content' && !_.isEmpty(value)) params_to_string[key] = this.escapeParam(value);
            }, this);
            
            var content = this._getShortcodeContent(model),
                is_container = _.isObject(vc.map[model.shortcode]) && _.isBoolean(vc.map[model.shortcode].is_container) && vc.map[model.shortcode].is_container === true;
            if(!is_container  && _.isObject(vc.map[model.shortcode]) && !_.isEmpty(vc.map[model.shortcode].as_parent)) is_container = true;
           
            return wp.shortcode.string({
                tag:model.shortcode,
                attrs:params_to_string,
                content:content,
                type:!is_container && _.isUndefined(params.content) ? 'single' : ''
            });
        },
		_getShortcodeContent:function (parent) {
		    var that = this,
		        models = _.sortBy(_.filter(vc.storage.data, function (model) {
		            // Filter children
		            return model.parent_id === parent.id;
		        }), function (model) {
		            // Sort by `order` field
		            return model.order;
		        }),
		
		        params = {};
		    _.extend(params, parent.params);
		 
		    if (!models.length) {
		
		        if(!_.isUndefined(window.switchEditors) && _.isString(params.content) && window.switchEditors.wpautop(params.content)===params.content) {
		            params.content = window.vc_wpautop(params.content);
		        }
		
		        return _.isUndefined(params.content) ? '' : params.content;
		    }
		    return _.reduce(models, function (memo, model) {
		        return memo + that.createShortcodeString(model);
		    }, '');
		}
	 });
	 $(function(){
		 var dhvcviewshortcode = new DHVCViewShortcode();
	 });
})(window.jQuery);