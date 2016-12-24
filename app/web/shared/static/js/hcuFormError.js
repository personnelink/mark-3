        (function($, undefined) {
          var defaults = {
            debugOn: true,
            formValidate: '',
            formStatusField: 'formStatus',
            formStatusDefaultClass: 'personnelink-formStatus k-block',
            formErrorTitle: 'The following error(s) occurred.',
            formInfoTitle: 'Action completed successfully.',
            validateOnClick: '',
            statusError: 'error',
            statusInfo: 'info',
            validateOnBlur: true,
            errorTemplate: '<span class="k-widget k-tooltip k-tooltip-validation">' + '<i class="fa fa-warning"></i><span class="k-block k-error-colored k-personnelink-msg">#=message#</span></span>',
            elementSupport:[
              ":text",
              ":password",
              "textarea",
              "select",
              ":radio",
              ":checkbox",
              "input[type='hidden']",
              "input[type='tel']",
              "input[type='email']"
            ].join(", "),
            personnelinkCustomRules: {
              personnelinkCustomMatch:function(input, undefined) {
                // ** Use a predefined Regex
                var pattern;
                var retVal = true;
                if (input.attr('personnelink-match') !== undefined) {
                  // ** ONLY validate if text is entered
                  if (input.val().length > 0) {
                    // ** Retrieve the regex
                    pattern = $.personnelinkValidator.patterns[input.attr('personnelink-match')];
                    if (pattern !== undefined) {
                      retVal = pattern.test(input.val());

                    }
                  }
                }
                return retVal;
              },
              personnelinkCustomDistinct:function(input, undefined) {
                if (input.attr('personnelink-distinct') !== undefined) {
                    if (input.attr('personnelink-distinct').length > 0) {

                var transform = function(val) {
                                    return val;
                                },
                valueList = $.map(
                      $('.' + input.attr('personnelink-distinct')).filter($.personnelinkValidator.settings.elementSupport),
                      function(obj) {
                        return transform(obj.value);
                      }
                );

                found = false;
                for (var idx = 0; idx < valueList.length; ++idx) {
                  if (found && input.val() === valueList[idx]) {
                    return false;
                  } else if (input.val() === valueList[idx]) {
                    found = true;
                  }
                }
                // ** Made it
                return true;

                  } else {
                    return true;
                  }
                } else {
                  return true;
                }
              },
              personnelinkCustomEquals:function(input, undefined) {
                if (input.attr('personnelink-equals') !== undefined) {
                  if (input.attr('personnelink-equals').length > 0) {
                    var transform = function(val) {
                                      return val;
                                    },
                    valueList = $.map(
                      $('.' + input.attr('personnelink-equals')).filter($.personnelinkValidator.settings.elementSupport),
                        function(obj) {
                          return transform(obj.value);
                        }
                    );

                    found = false;
                    // * There must be values before validating
                    // ** MWS 1/15/2014 - Removing the length condition, the
                      // Error checking fails to catch mismatched values when the element
                      // being checked is empty
                    // ** MWS 1/15/2014 if (input.val().length > 0) {
                      for (var idx = 0; idx < valueList.length; ++idx) {
                        // ** Removing the length check on
                        // ** MWS 1/15/2014 if (valueList[idx].length > 0) {
                          if (input.val() !== valueList[idx]) {
                            return false;
                          }
                        // ** MWS 1/15/2014 }
                      }
                    // ** MWS 1/15/2014 }
                    // ** Made it
                    return true;

                  } else {
                    return true;
                  }
                } else {
                  return true;
                }
              },
              personnelinkCustomMinLen:function(input, undefined) {
                if (input.attr('personnelink-minlen') !== undefined) {
                  if (input.attr('personnelink-minlen').length > 0 && input.val().length > 0) {
                    // * decipher the actual numeric value that should be given
                      // * this will be the max length we allow for the field input
                    fieldMinLength = input.attr('personnelink-minlen');
                    if (input.val().length < fieldMinLength && fieldMinLength > 0) {
                      return false;
                    }
                  }
                }
                return true;
              },
              personnelinkCustomMaxLen:function(input, undefined) {
                if (input.attr('personnelink-maxlen') !== undefined) {
                  if (input.attr('personnelink-maxlen').length > 0 && input.val().length > 0) {
                    // * decipher the actual numeric value that should be given
                      // * this will be the max length we allow for the field input
                    fieldMaxLength = input.attr('personnelink-maxlen');
                    if (input.val().length > fieldMaxLength && fieldMaxLength > 0) {
                      return false;
                    }
                  }
                }
                return true;
              },
              personnelinkCustomDateGTValue:function(input, undefined) {
                var enteredDate,
                    compareDate,
                    compareType = "string";

                if (input.attr('personnelink-dategtvalue') !== undefined) {
                  if (input.attr('personnelink-dategtvalue').length > 0 && input.val().length > 0) {
                    // * Determine Type
                    if (input.attr('personnelink-dategttype') !== undefined) {
                      if (input.attr('personnelink-dategttype').length > 0) {
                        if (input.attr('personnelink-dategttype') === 'field') {
                          compareType = "field";
                        }
                      }
                    }
                    // * Only validate if value is entered
                    // * date validation should occur before getting here..
                    enteredDate = new Date(input.val());

                    if (compareType === 'string') {
                      compareDate = new Date(input.attr('personnelink-dategtvalue'));
                    } else {
                      compareDate = new Date($('#' + input.attr('personnelink-dategtvalue')).val());
                    }
                    if (isNaN(enteredDate) || isNaN(compareDate)) {
                      return false;
                    } else {
                      if (enteredDate < compareDate) {
                        return false;
                      }
                    }
                  }
                }
                return true;
              }
            },
            personnelinkCustomMessages: {
                personnelinkCustomDistinct: "Selection must be unique",
                personnelinkCustomEquals: "Values must match",
                personnelinkCustomMatch: '{0} is not formatted correctly',
                personnelinkCustomMaxLen: "{0} exceeds maximum length",
                personnelinkCustomMinLen: "{0} does not meet the minimum length requirement",
                personnelinkCustomDateGTValue: "{0} FAILED"
            }
          },
          __private;

          // Static functions and properties:
          ///////////////////////////////////////////////////////////////////////////////
          $.personnelinkValidator = {
            settings: $.extend(defaults, {}),
            patterns:{
                integer:/^\d+$/,

                dateYY:/^((0?\d)|(1[012]))\/([012]?\d|30|31)\/\d{1,4}$/,
                date:/^((0?\d)|(1[012]))\/([012]?\d|30|31)\/\d{4}$/,

                email:/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i,
                usd:/^\$?((\d{1,3}(,\d{3})*)|\d+)(\.(\d{2})?)?$/,
                url:/^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i,

                // Number should accept floats or integers, be they positive or negative.
                // It should also support scientific-notation, written as a lower or capital 'E' followed by the radix.
                // Number assumes base 10.
                // Unlike the native parseFloat or parseInt functions, this should not accept trailing Latin characters.
                number:/^[+-]?(\d+(\.\d*)?|\.\d+)([Ee]-?\d+)?$/,

                zip:/^\d{5}(-\d{4})?$/,
                phone:/^[2-9]\d{2}-\d{3}-\d{4}$/,
                guid:/^(\{?([0-9a-fA-F]){8}-(([0-9a-fA-F]){4}-){3}([0-9a-fA-F]){12}\}?)$/,
                time12:/^((0?\d)|(1[12])):[0-5]\d?\s?[aApP]\.?[mM]\.?$/,

                time24:/^(20|21|22|23|[01]\d|\d)(([:][0-5]\d){1,2})$/,

                nonHtml:/^[^<>]*$/
            },
            personnelinkResetMessage: true,
            personnelinkValidate: false,
            personnelinkKendoValidator: undefined,
            setup:function(options) {
              this.settings = $.extend(this.settings, options);
              // ** If formValidate gets set then turn it ON?
              if (this.settings.formValidate !== '') {
                // ** We would now want to BIND the validate
                this.personnelinkKendoValidator = $("#" + this.settings.formValidate).kendoValidator(
                {
                  // errorTemplate: '<span class="k-widget k-tooltip k-tooltip-validation" title="#=message#"><i class="fa fa-warning "></i></span>',
                  errorTemplate: '<!-- <p class="k-tooltip-validation hcu-validation-message" title="#=message#"><i class="fa fa-warning "></i>&nbsp;#=message#</p> -->',
                  rules: this.settings.personnelinkCustomRules,
                  messages: this.settings.personnelinkCustomMessages,
                  validateOnBlur: this.settings.validateOnBlur
                }).data("kendoValidator");
              }
              if (this.settings.validateOnClick !== '') {
                // * Bind the button

                  $('#' + this.settings.validateOnClick).click(function() {
                    // ** Bind button press to the validator
                    $.personnelinkValidator.personnelinkValidate = $.personnelinkValidator.validate();
                  });

              }
              if (options.formStatusField !== '') {
                this.hideMessage();
              }
              var tooltip = $(document.body).kendoTooltip({
                filter: '.k-invalid-msg',
                width: 120,
                NS: '',
                position: "top"
              }).data("kendoTooltip");
            },
            serverReportStatus: function() {
            },
            hideMessage: function() {
              var $dispMsgField = $('#' + this.settings.formStatusField);
              if ( this.personnelinkKendoValidator !== undefined) {
                this.personnelinkKendoValidator.hideMessages();
              }
              $dispMsgField.hide();
            },
            displayMessage: function(messages, statustype, undefined) {
              // ** messages {array of strings OR array of objects OR string}
              var $dispMsgField = $('#' + this.settings.formStatusField);
              var objP = $('<p></p>');
              var objUl = $('<ul></ul>');

              if ($dispMsgField.length > 0 && messages !== undefined) {
                // Check the size of the class attribute, if there are NONE set, then set the default
                var curClass = $dispMsgField.attr('class');
                if (curClass === '' || curClass === undefined) {
                  // * we need to add the default attributes to the field
                  $dispMsgField.attr('class', this.settings.formStatusDefaultClass);
                  $dispMsgField.css('margin-bottom', '10px');
                }

                // ** Hide any instace of the current message
                // * RESET TO DEFAULT
                if (this.personnelinkResetMessage) {
                  $dispMsgField.hide();
                  $dispMsgField.text('');
                  $dispMsgField.removeClass('k-error-colored');
                  $dispMsgField.removeClass('k-success-colored');
                }
                // ** setup the field layout

                if(statustype === this.settings.statusError) {
                  $dispMsgField.addClass('k-error-colored');
                  if (this.settings.formErrorTitle !== '' && this.personnelinkResetMessage) {
                    objP.text(this.settings.formErrorTitle);
                  }
                } else {
                  $dispMsgField.addClass('k-success-colored');
                  if (this.settings.formInfoTitle !== '' && this.personnelinkResetMessage) {
                    objP.text(this.settings.formInfoTitle);
                  }
                }
                if (typeof(messages) === 'string') {
                  // ** Single Instance of String
                  if (messages !== '') {
                    var objLi = $('<li></li>');
                    objLi.append(messages);
                    objUl.append(objLi);
                  }
                } else if (messages instanceof Object) {
                  // ** Object
                  $.each(messages, function(index, value) {

                    if (value instanceof Object) {
                      if (value !== null) {
                        if (value['message'] !== '') {
                          var objLi = $('<li></li>');
                          objLi.append(value['message']);
                          objUl.append(objLi);
                        }
                      }

                    } else if (typeof(value) === 'string') {
                      if (value !== '') {
                        var objLi = $('<li></li>');
                        objLi.append(value);
                        objUl.append(objLi);
                      }
                    } else {
                      if (value !== null) {
                        if (value['message'] !== '') {
                          var objLi = $('<li></li>');
                          objLi.append(value['message']);
                          objUl.append(objLi);
                        }
                      }
                    }

                  });
                } else if (messages instanceof Array) {
                  // ** Array of messages
                  $.each(messages, function(index, value) {
                    if (value !== '') {
                      var objLi = $('<li></li>');
                      objLi.append(value);
                      objUl.append(objLi);
                    }
                  });
                }

                $dispMsgField.append(objP);
                $dispMsgField.append(objUl);

                if (objUl.children().length > 0) {
                  $dispMsgField.show();
                }

              }

            },
            validate: function() {
              this.hideMessage();
              var _kendoValidate = this.personnelinkKendoValidator.validate();
              if (_kendoValidate === false) {

                //var errors = this.personnelinkKendoValidator.errors();

                this.displayMessage(this.personnelinkKendoValidator.errors(), this.settings.statusError);
              }


              return _kendoValidate;
            },
            __private:undefined

          };

          $.fn.extend({
            personnelinkValidator: function(arg) {
              // ** here is where I want to do some things.
              // * consider this the constructor?

              return this.each(
                function() {
                  if (this.tagName.toLowerCase() === "form") {

                  }
                }
              );
            }
          });
          __private = {
          };

        })(jQuery);