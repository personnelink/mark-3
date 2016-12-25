/*
 * timeout-dialog.js v1.0.1, 01-03-2012
 * 
 * @author: Rodrigo Neri (@rigoneri)
 * 
 * (The MIT License)
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE. 
 */


/* String formatting, you might want to remove this if you already use it.
 * Example:
 * 
 * var location = 'World';
 * alert('Hello {0}'.format(location));
 * Modified by Personnelink to remove reference to Jquery UI dialog.  kendoUI window widget used instead
 */
String.prototype.format = function() {
  var s = this,
      i = arguments.length;

  while (i--) {
    s = s.replace(new RegExp('\\{' + i + '\\}', 'gm'), arguments[i]);
  }
  return s;
};

!function($) {
  $.timeoutDialog = function(options) {

    var timeoutWindow;
    var settings = {
      timeout: 1200,
      countdown: 60,
      title : 'Your session is about to expire!',
      message : 'You will be logged out in {0} seconds.',
      question: 'Do you want to stay signed in?',
      keep_alive_button_text: 'Yes, Keep me signed in',
      sign_out_button_text: 'No, Sign me out',
      keep_alive_url: '/keep-alive',
      logout_url: null,
      logout_redirect_url: '/',
      restart_on_yes: true,
      startTime: 0,
      dialog_width: 350,
      mobile_popup: false,
      cookie_name: 'Ticket'
    };

    $.extend(settings, options);

    var TimeoutDialog = {
      init: function () {

        this.setupDialogTimer();
      }, 

      setupDialogTimer: function(sessTimeout) {
        var self = this;
        if (typeof(sessTimeout) === 'undefined') sessTimeout = (settings.timeout - settings.countdown);

        settings.startTime = this.returnTime();        
        window.setTimeout(function() {
          var extendTimer = false;

          if (settings.startTime !== 0) {
            var t0 = self.returnTime();
            if ((t0 !== settings.startTime) && (t0 - settings.startTime) > 0) {
              extendTimer = true;
              extendTime = (t0 - settings.startTime);

              self.setupDialogTimer(extendTime);

            }
          }

          if (!extendTimer) {
            self.setupDialog();
          }
        }, (sessTimeout * 1000));
      },
      setupDialog: function() {
        var self = this;

        self.destroyDialog();
        if (settings.mobile_popup) {
          if ($("#timeout-dialog").length === 0) {
            // Create the popup

            $('<div id="timeout-dialog" data-overlay-theme="c" data-theme="b" data-dismissible="false" data-role="popup">' +
              '<div class="timeout-popup-container ui-bar ui-bar-b ui-corner-all">' +
                '<div class="ui-bar ui-bar-a">' + settings.title +'</div>' +
                '<div class="ui-content">' +
                  '<div id="timeout-message">' + settings.message.format('<span id="timeout-countdown">' + settings.countdown + '</span>') + '</div>' +
                  '<div id="timeout-question">' + settings.question + '</div><hr>' +
                  '<button type="button" id="timeout-continue-button" class="ui-btn ui-btn-a">' + settings.keep_alive_button_text + '</button>' +
                  '<button type="button" id="timeout-cancel-button" class="ui-btn ui-btn-a">' + settings.sign_out_button_text + '</button>' +
                '</div>' +
              '</div>' +              
            '</div>').appendTo('body');

            timeoutWindow = $("#timeout-dialog");
            timeoutWindow.popup();
          }
          
          timeoutWindow.popup('open'); 
        } else {
          if ($("#timeout-dialog").length === 0) {
            $('<div class="time-wrapper" style="display: none;" class="k-content">' +
                '<div id="timeout-dialog">' +
                  '<p id="timeout-message"><i class="fa fa-exclamation-triangle fa-3x " style="float:left; color: rgba(236, 0, 0, .7)" aria-hidden="true"></i><h3 style="margin-left:50px">' + settings.message.format('<span id="timeout-countdown">' + settings.countdown + '</span>') + '</h3></p>' +
                  '<div style="clear: both;"></div>' +
                  '<p id="timeout-question"><h4>' + settings.question + '</h4></p><hr>' +
                    '<div class="hcu-bs-no-padding">' +
                      '<div class="">' +
                        '<div id="timeout-cancel-wrapper" class="col-xs-12 col-sm-5 col-md-5 col-lg-5">' +
                          '<button type="button" id="timeout-cancel-button" class="k-button hcu-all-100 hcu-xs-btn-margin-top hcu-xs-btn-pad">' + settings.sign_out_button_text + '</button>' +
                        '</div>' +
                        '<div id="timeout-continue-wrapper" class="col-xs-12 col-sm-offset-2 col-sm-5 col-md-5 col-lg-5" style="text-align:right">' +
                          '<button type="button" id="timeout-continue-button" class="k-button k-primary hcu-all-100 hcu-xs-btn-margin-top hcu-xs-btn-pad">' + settings.keep_alive_button_text + '</button>' +
                        '</div>' +
                      '</div>' +
                    '</div>' +
                '</div>' +
              '</div>').appendTo('body');
          }
              
          timeoutWindow = $("#timeout-dialog").kendoWindow({
                          minWidth: function() {
                            // FOR desktop size, set the minimum to be greater than 300 (mobile size)
                            if ($(window).width() > 767) {
                              return "500px";
                            } else {
                              return "300px";
                            }
                          },
                          position: {
                            left: '20%'
                          },
                          title: settings.title,
                          actions: [],
                          modal: true,
                          resizable: true 
                      }).data("kendoWindow").center().open();
          timeoutWindow.wrapper.addClass('personnelink-timeout');

        }                      

        $(document).on("click", '#timeout-continue-button', function() {self.keepAlive();});
        $(document).on("click", '#timeout-cancel-button', function() {self.signOut(true);});

        self.startCountdown();
      },

      destroyDialog: function() {
        if ($("#timeout-dialog").length) {

        
          $(document).off("click", '#timeout-continue-button');
          $(document).off("click", '#timeout-cancel-button');
          if (settings.mobile_popup) {
            timeoutWindow.popup('close');
          } else {
            if (timeoutWindow) {
              timeoutWindow.close();
            }
          }
        }  
      },

      startCountdown: function() {
        var self = this,
            counter = settings.countdown;

        this.countdown = window.setInterval(function() {
          counter -= 1;
          $("#timeout-countdown").html(counter);

          if (counter <= 0) {
            window.clearInterval(self.countdown);
            self.signOut(false);
          }

        }, 1000);
      },

      keepAlive: function() {
        var self = this;
        this.destroyDialog();
        var dataSuccess = false;
        window.clearInterval(this.countdown);

        $.get(settings.keep_alive_url, function(data) {
          try {
            if (settings.mobile_popup) {
              dataSuccess = (data.status.code === '000');
            } else {
              dataSuccess = (data === 'OK');
            }
            if (dataSuccess) {
              if (settings.restart_on_yes) {
                settings.startTime = self.returnTime();
                self.setupDialogTimer();
              }                
            } else {
              throw('Response Error');
            }
          } catch(err) {
            self.signOut(false);
          }
        });
      },

      signOut: function(is_forced) {
        var self = this;
        this.destroyDialog();
        if (settings.logout_url !== null) {
            $.post(settings.logout_url, function(data){
                self.redirectLogout(is_forced);
            });
        }
        else {
            self.redirectLogout(is_forced);
        }
      }, 

      redirectLogout: function(is_forced){
        var target = settings.logout_redirect_url;
        
        window.location = target;
      },
      returnTime: function() {
        var retVal = 0;
        
        if ($.cookie !== undefined) {          
          if ($.cookie(settings.cookie_name) !== undefined) {
            var t1 = $.cookie(settings.cookie_name);
            var e1 = t1.match(new RegExp("Ctime=(.*?)($|\&)", "i"));
            if (e1[1] !== undefined) {
              retVal = e1[1];
            }
          }
        } 
        return retVal;
      }
    };

    TimeoutDialog.init();
  };
}(window.jQuery);
