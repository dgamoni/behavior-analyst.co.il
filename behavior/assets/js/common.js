'use strict';

(function ($) {
    $( document ).ready( function() {
        var search = location.search.substring(1);
        var get_parameters = {};
        search.replace(/([^=&]+)=([^&]*)/g, function(m, key, value) {
            get_parameters[decodeURIComponent(key)] = decodeURIComponent(value);
        });

        var redirect_to_field = $('input[name="redirect_to"]');

        if (redirect_to_field.length > 0 && get_parameters["redirect-to"]) {
            if (get_parameters["check-membership"]) {
                redirect_to_field.val(get_parameters["redirect-to"] + '?check-membership=' + get_parameters["check-membership"]);
            } else {
                redirect_to_field.val(get_parameters["redirect-to"]);
            }
        }

        loadForm();

        $(document).on('submit', '#pelepay-form', function () {
            var firstname = $(this).find('#first-name').val();
            var lastname = $(this).find('#last-name').val();
            var email = $(this).find('#user-email').val();
            var phone_number = $(this).find('#cell-phone').val();

            $('#pelepay-firstname').val(firstname);
            $('#pelepay-lastname').val(lastname);
            $('#pelepay-email').val(email);
            $('#pelepay-phone').val(phone_number);

            var data = new FormData($(this)[0]);

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: window.wpAjaxURL + '?action=save-draft-member-data',
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                success: function (data) {
                    console.log("SUCCESS : ", data);
                },
                error: function (e) {
                    console.log("ERROR : ", e);
                }
            });

            //console.log($(this).serialize());
            return true;
        });

        $(document).on('submit', '#free-form', function () {
            var data = new FormData($(this)[0]);

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: window.wpAjaxURL + '?action=register-new-free-member',
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                success: function (data) {
                    console.log("SUCCESS : ", data);
                    $('.account-registration-wrapper').html(data);
                },
                error: function (e) {
                    console.log("ERROR : ", e);
                }
            });

            //console.log($(this).serialize());
            return false;
        });

        $(document).on('click', '.apply-coupon', function () {
            var self = $(this);
            var coupon_field = $('#coupon');
            var data = {
                coupon_name: coupon_field.val(),
                price: $('#pelepay-membership-amount').val()
            };

            //console.log(data);

            $.ajax({
                type: "POST",
                url: window.wpAjaxURL + '?action=get-discount-price',
                data: data,
                success: function (data) {
                    //console.log(data);
                    var message = self.parent().find('.message');

                    if (data.status > 0 && parseFloat(data.price) > 0) {
                        message.text(data.message);
                        message.removeClass('error');
                        $('#pelepay-membership-amount').val(data.price);
                        coupon_field.prop('disabled', true);
                        self.off('click');
                    } else {
                        message.text(data.message);
                        message.addClass('error');
                    }
                },
                error: function (e) {
                    console.log("ERROR : ", e);
                }
            });
        });

        $('.member-types .type').on('click', function (event) {
            event.preventDefault();

            $('.member-types .type.active').removeClass('active');
            $(this).addClass('active');

            loadForm();
        });

        $(document).on('change', '#other_accreditation', function() {
            $(this).parent().find('input[type="radio"]').val($(this).val());
        });

    });

    function loadForm() {
        var form_name = $('.member-types .type.active').data('form-name');
        var form_ajax_container = $('.form-ajax');
        var data = {
            'action': 'get-ajax-form',
            'form_name': form_name
        };

        form_ajax_container.load(window.wpAjaxURL + ' .form-wrapper', data, function () {
            var friends_form = $("form.friends");
            var students_form = $("form.students");
            var colleagues_form = $("form.colleagues");
            var fellow_form = $("form.fellow");

            $.validator.addMethod("validateNullOrWhiteSpace", function (value, element) {
                return !isNullOrWhitespace(value);
            }, "Blanks are not allowed!");

            $.validator.addMethod('filesize', function (value, element, param) {
                console.log(element.files);
                var valid_size = true;

                $.each(element.files, function (index, file) {
                    if (file.size > param) {
                        valid_size = false;
                        return;
                    }
                });

                return this.optional(element) || valid_size;
            }, 'File size must be less than 300Kb');

            $.validator.addMethod("extension", function(value, element, param) {
                param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
                return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
            }, "Please enter a value with a valid extension.");

            if (friends_form.length > 0) {
                friends_form.validate({
                    rules: {
                        'last-name': 'required',
                        'first-name': 'required',
                        'cell-phone': 'required',
                        'user-email': 'required',
                        'id-number': 'required',
                        'accreditation': {
                            required: true,
                            validateNullOrWhiteSpace: true
                        },
                        'certification-type': 'required',
                        'certification-number': 'required',
                        'speciality': 'required',
                    },
                    messages: {
                        'last-name': 'נא למלא את כל השדות המסומנים כחובה',
                        'first-name': 'נא למלא את כל השדות המסומנים כחובה',
                        'cell-phone': 'נא למלא את כל השדות המסומנים כחובה',
                        'user-email': 'נא למלא את כל השדות המסומנים כחובה',
                        'id-number': 'נא למלא את כל השדות המסומנים כחובה',
                        'accreditation': 'נא למלא את כל השדות המסומנים כחובה',
                        'certification-type': 'נא למלא את כל השדות המסומנים כחובה',
                        'certification-number': 'נא למלא את כל השדות המסומנים כחובה',
                        'speciality': 'נא למלא את כל השדות המסומנים כחובה',
                    }
                });
            }

            if (students_form.length > 0) {
                students_form.validate({
                    rules: {
                        'last-name': 'required',
                        'first-name': 'required',
                        'cell-phone': 'required',
                        'user-email': 'required',
                        'id-number': 'required',
                        'accreditation': {
                            required: true,
                            validateNullOrWhiteSpace: true
                        },
                        'student-certificate': {
                            required: false,
                            extension: "jpg|jpeg|png|pdf",
                            filesize: 1048576
                        },
                        'speciality': 'required',
                    },
                    messages: {
                        'last-name': 'נא למלא את כל השדות המסומנים כחובה',
                        'first-name': 'נא למלא את כל השדות המסומנים כחובה',
                        'cell-phone': 'נא למלא את כל השדות המסומנים כחובה',
                        'user-email': 'נא למלא את כל השדות המסומנים כחובה',
                        'id-number': 'נא למלא את כל השדות המסומנים כחובה',
                        'accreditation': 'נא למלא את כל השדות המסומנים כחובה',
                        'student-certificate': {
                            required: 'נא למלא את כל השדות המסומנים כחובה',
                            extension: 'valid extensions - .jpg, .jpeg, .png, .pdf'
                        },
                        'speciality': 'נא למלא את כל השדות המסומנים כחובה',
                    }
                });
            }

            if (colleagues_form.length > 0) {
                colleagues_form.validate({
                    rules: {
                        'last-name': 'required',
                        'first-name': 'required',
                        'cell-phone': 'required',
                        'user-email': 'required',
                        'id-number': 'required',
                        'finish-date': 'required',
                        'accreditation': {
                            required: true,
                            validateNullOrWhiteSpace: true
                        },
                        'occupation' : 'required',
                        'student-certificate[]': {
                            required: false,
                            extension: "jpg|jpeg|png|pdf",
                            filesize: 1048576
                        },
                        'speciality': 'required',
                    },
                    messages: {
                        'last-name': 'נא למלא את כל השדות המסומנים כחובה',
                        'first-name': 'נא למלא את כל השדות המסומנים כחובה',
                        'cell-phone': 'נא למלא את כל השדות המסומנים כחובה',
                        'user-email': 'נא למלא את כל השדות המסומנים כחובה',
                        'id-number': 'נא למלא את כל השדות המסומנים כחובה',
                        'accreditation': 'נא למלא את כל השדות המסומנים כחובה',
                        'student-certificate[]': {
                            required: 'נא למלא את כל השדות המסומנים כחובה',
                            extension: 'valid extensions - .jpg, .jpeg, .png, .pdf'
                        },
                        'speciality': 'נא למלא את כל השדות המסומנים כחובה',
                        'finish-date': 'נא למלא את כל השדות המסומנים כחובה',
                        'occupation': 'נא למלא את כל השדות המסומנים כחובה',
                    }
                });
            }

            if (fellow_form.length > 0) {
                fellow_form.validate({
                    rules: {
                        'last-name': 'required',
                        'first-name': 'required',
                        'cell-phone': 'required',
                        'user-email': 'required'
                    },
                    messages: {
                        'last-name': 'נא למלא את כל השדות המסומנים כחובה',
                        'first-name': 'נא למלא את כל השדות המסומנים כחובה',
                        'cell-phone': 'נא למלא את כל השדות המסומנים כחובה',
                        'user-email': 'נא למלא את כל השדות המסומנים כחובה'
                    }
                });
            }
        });
    }

    function isNullOrWhitespace(input) {
        if (typeof input === 'undefined' || input == null)
            return true;
        return input.replace(/\s/g, '').length < 1;
    }
})(jQuery);