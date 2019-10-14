/*eslint-disable */
define(["jquery", "knockout", "Magento_PageBuilder/js/events", "mageUtils", "underscore", "Magento_PageBuilder/js/config", "Magento_PageBuilder/js/content-type-factory", "Magento_PageBuilder/js/panel", "Magento_PageBuilder/js/stage",'Magento_Ui/js/modal/modal','mage/url',"Symphisys_ContentMigration/js/html2canvas"], function (_jquery, _knockout, _events, _mageUtils, _underscore, _config, _contentTypeFactory, _panel, _stage,modal,urlBuilder,Html2Canvas) {
  /**
   * Copyright © Magento, Inc. All rights reserved.
   * See COPYING.txt for license details.
   */
  var PageBuilder =
  /*#__PURE__*/
  function () {
    "use strict";

    function PageBuilder(config, initialValue) {
      var _this = this;

      this.template = "Magento_PageBuilder/page-builder";
      this.isStageReady = _knockout.observable(false);
      this.id = _mageUtils.uniqueid();
      this.originalScrollTop = 0;
      this.isFullScreen = _knockout.observable(false);
      this.loading = _knockout.observable(true);
      this.wrapperStyles = _knockout.observable({});
      this.previousWrapperStyles = {};

      _config.setConfig(config);

      this.initialValue = initialValue;
      this.isFullScreen(config.isFullScreen);
      this.config = config; // Create the required root container for the stage

      (0, _contentTypeFactory)(_config.getContentTypeConfig(_stage.rootContainerName), null, this.id).then(function (rootContainer) {
        _this.stage = new _stage(_this, rootContainer);

        _this.isStageReady(true);
      });
      this.panel = new _panel(this);
      this.initListeners();
    }
    /**
     * Init listeners.
     */


    var _proto = PageBuilder.prototype;

    _proto.initListeners = function initListeners() {
      var _this2 = this;

      _events.on("stage:" + this.id + ":toggleFullscreen", this.toggleFullScreen.bind(this));

      this.isFullScreen.subscribe(function () {
        return _this2.onFullScreenChange();
      });
    }
    /**
     * Tells the stage wrapper to expand to fullScreen
     *
     * @param {StageToggleFullScreenParamsInterface} args
     */
    ;

    _proto.toggleFullScreen = function toggleFullScreen(args) {
      var _this3 = this;

      if (args.animate === false) {
        this.isFullScreen(!this.isFullScreen());
        return;
      }

      var stageWrapper = (0, _jquery)("#" + this.stage.id).parent();
      var pageBuilderWrapper = stageWrapper.parents(".pagebuilder-wysiwyg-wrapper");
      var panel = stageWrapper.find(".pagebuilder-panel");

      if (!this.isFullScreen()) {
        pageBuilderWrapper.css("height", pageBuilderWrapper.outerHeight());
        this.previousPanelHeight = panel.outerHeight();
        panel.css("height", this.previousPanelHeight + "px");
        /**
         * Fix the stage in the exact place it is when it's part of the content and allow it to transition to full
         * screen.
         */

        var xPosition = parseInt(stageWrapper.offset().top.toString(), 10) - parseInt((0, _jquery)(window).scrollTop().toString(), 10);
        var yPosition = stageWrapper.offset().left;
        this.previousWrapperStyles = {
          position: "fixed",
          top: xPosition + "px",
          left: yPosition + "px",
          zIndex: "800",
          width: stageWrapper.outerWidth().toString() + "px"
        };
        this.wrapperStyles(this.previousWrapperStyles);
        this.isFullScreen(true);

        _underscore.defer(function () {
          // Remove all styles we applied to fix the position once we're transitioning
          panel.css("height", "");

          _this3.wrapperStyles(Object.keys(_this3.previousWrapperStyles).reduce(function (object, styleName) {
            var _Object$assign;

            return Object.assign(object, (_Object$assign = {}, _Object$assign[styleName] = "", _Object$assign));
          }, {}));
        });
      } else {
        // When leaving full screen mode just transition back to the original state
        this.wrapperStyles(this.previousWrapperStyles);
        this.isFullScreen(false);
        panel.css("height", this.previousPanelHeight + "px"); // Wait for the 350ms animation to complete before changing these properties back

        _underscore.delay(function () {
          panel.css("height", "");
          pageBuilderWrapper.css("height", "");

          _this3.wrapperStyles(Object.keys(_this3.previousWrapperStyles).reduce(function (object, styleName) {
            var _Object$assign2;

            return Object.assign(object, (_Object$assign2 = {}, _Object$assign2[styleName] = "", _Object$assign2));
          }, {}));

          _this3.previousWrapperStyles = {};
          _this3.previousPanelHeight = null;
        }, 350);
      }
    }
    /**
     * Change window scroll base on full screen mode.
     */
    ;

    _proto.onFullScreenChange = function onFullScreenChange() {
      if (this.isFullScreen()) {
        (0, _jquery)("body").css("overflow", "hidden");
      } else {
        (0, _jquery)("body").css("overflow", "");
      }

      _events.trigger("stage:" + this.id + ":fullScreenModeChangeAfter", {
        fullScreen: this.isFullScreen()
      });
    };
	/**
		Custom Popup : Symphisys
	
	*/
	
	_proto.popupFunction = function popupFunction() {      
      var options = {
        type: 'popup',
        responsive: true,
        innerScroll: true,
        title: 'Available Content Layouts',
		buttons: []
      };	  
	 var popup = modal(options, _jquery('#popup-modal'));
	  _jquery("#popup-modal").modal("openModal");
	  
		var logoHrefUrl = _jquery('.menu-wrapper a.logo').attr('href');
		var parts = logoHrefUrl.split("/");
		var last_part = parts[parts.length-2];
		var front_part = parts[parts.length-3];
		var res = logoHrefUrl.replace(last_part+'/', '');
		var resBase = res.replace(front_part+'/', '');
		urlBuilder.setBaseUrl(resBase);//window.location.hostname
		var url = urlBuilder.build(front_part+'/loadcontent/loadcontent/index');
	  
	  _jquery.ajax({
			url:url,
			type:'POST',
			data:{form_key: window.FORM_KEY,store_id:1,search_type:'cms'},
			showLoader: true,
			dataType:'json',			
			complete: function(response) {
				_jquery("#popup-modal").html(response.responseText);
			}
		});
    };
	
	_proto.saveFunction = function saveFunction() { 
		var logoHrefUrl = _jquery('.menu-wrapper a.logo').attr('href');
		var parts = logoHrefUrl.split("/");
		var last_part = parts[parts.length-2];
		var front_part = parts[parts.length-3];
		var res = logoHrefUrl.replace(last_part+'/', '');
		var resBase = res.replace(front_part+'/', '');		
		var options = {
        type: 'popup',
        responsive: true,
        innerScroll: true,
        title: 'Save This Template',
		buttons: [{
          text: _jquery.mage.__('Save'),
          class: '',
          click: function () {
             _jquery('div[data-content-type="row"]').each(function(){
				alert(_jquery(this).val());
				//console.log(this.val());
			});
			var dom = _jquery('.root-container-container');
			var templatePreview = '';
			html2canvas(dom, {
				useCORS: true,
				onrendered: function(canvas) {

					//Shrink the canvas a bit to save space.
					var scaleCan = document.createElement('canvas');
					var w = canvas.width,
						h = canvas.height;
					scaleCan.width = w/2.5;
					scaleCan.height = h/2.5;

					var ctx = scaleCan.getContext('2d');
					ctx.drawImage(canvas, 0, 0, canvas.width/2.5, canvas.height/2.5);
					templatePreview = scaleCan.toDataURL();
					alert(templatePreview);

				}
			});
			var refType = "";
			var refId = "";
			var path = window.location.href;
			var split = path.split('/');
			if(_jquery("body#html-body").hasClass("adminhtml-cms_page-edit")){
			var refId=split[8];
			var refType= "cms";
			}else if(_jquery("body#html-body").hasClass("catalog-product-edit")){
			var refId=split[8];
			var refType= "product";
			}else if(_jquery("body#html-body").hasClass("catalog-category-edit")){
			var refId=split[10];
			var refType= "category";
			}else if(_jquery("body#html-body").hasClass("aw_blog_admin-post-edit")){
			var refId=split[8];
			var refType= "post";
			}else if(_jquery("body#html-body").hasClass("cms-block-edit")){
			var refId=split[8];
			var refType= "block";
			}else{
			var refId = "";
			var refType= "general";
			}
			var version = _jquery("input#version_name").val();
			var storeId = _jquery("select#store_view").val();
			var content = "content";
			var refType = refType;
			urlBuilder.setBaseUrl(resBase);//window.location.hostname
			var url = urlBuilder.build(front_part+'/loadcontent/savecontent/index');	 
			_jquery.ajax({
			url:url,
			type:'POST',
			data:{version:version,storeId:storeId,content:content,refId:refId, refType:refType},
			showLoader: true,
			dataType:'json', 
			success: function(data){
			//jQuery('#ajax_loader1').hide();
			console.log("save data");
			},
			error: function(result){
			//console.log('no response !');
			}
			});
          }
        }]
      };
      var popup2 = modal(options, _jquery('#popup-modal-save'));
      _jquery("#popup-modal-save").modal("openModal");
	    urlBuilder.setBaseUrl(resBase);//window.location.hostname
		var url = urlBuilder.build(front_part+'/loadcontent/loadcontent/loadForm');	 
	  _jquery.ajax({
			url:url,
			type:'POST',
			data:{form_key: window.FORM_KEY},
			showLoader: true,
			dataType:'json',			
			complete: function(response) {
				_jquery("#popup-modal-save").html(response.responseText);
			}
		});
	};
	
	
    /**
     * Get template.
     *
     * @returns {string}
     */
    

    _proto.getTemplate = function getTemplate() {
      return this.template;
    };

    return PageBuilder;
  }();

  return PageBuilder;
});
//# sourceMappingURL=page-builder.js.map