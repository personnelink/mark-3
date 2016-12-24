/*
 * Add custom behaviour to the Kendo UI
 */

  kendo.ui.Window.prototype.centerTop = function() {
      var wrapper = this.wrapper,
          documentWindow = $(window);
      wrapper.css({
          left: documentWindow.scrollLeft() + Math.max(0, (documentWindow.width() - wrapper.width()) / 2),
          top: 0
      });
      return this;
    };

  kendo.ui.Window.prototype.placeWindow = function() {
      var wrapper = this.wrapper,
          documentWindow = $(window);
      var screenHeight = documentWindow.height();
      var topPos = documentWindow.scrollTop() + Math.max(0, (documentWindow.height() - wrapper.height()) / 4);
      if (screenHeight < 650 && topPos > 50) {
        topPos = 0;
      }
      wrapper.css({
          left: documentWindow.scrollLeft() + Math.max(0, (documentWindow.width() - wrapper.width()) / 2),
          top: topPos
      });
      return this;
    };


    /*
    * Create a default custom tooltip object to use later for binding kendoTooltip objects to data
    */
    var personnelinkTooltip = {
        defaults: {
            content: "",
            position: "top",
            callout: false,
            showafter: 300
        },
        bind: function(tooltips) {
            var that=this;
            $.each( tooltips, function( key, value ) {
                that.reset();
                switch (typeof value) {
                    case "number":
                    case "string":
                        that.custom.content=value;
                        break;

                    case "object":
                        that.custom=value;
                        break;
                };
                $("#"+key).kendoTooltip(that.defaults);
            });
            return false;
        },
        reset: function() {
            this.custom=this.defaults;
            return false;
        }
    };

