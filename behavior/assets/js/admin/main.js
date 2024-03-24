'use strict';

(function ($) {
    $(document).ready(function () {
        $('.user_nicename').on('click', function () {
            var user_id = parseInt($(this).parent().find('td.ID').text());
            var data = {
                user_id: user_id
            };

            $.ajax({
                type: "GET",
                url: window.ajaxurl + '?action=get-member-data',
                data: data,
                success: function (data) {
                    console.log("SUCCESS : ", data);
                    renderMemberData(data.member_data);
                },
                error: function (e) {
                    console.log("ERROR : ", e);
                }
            });
        });

        function renderMemberData(data) {
            var modal = $('<div class="member-data"></div>');
            var close_icon = $('<button class="close">Close</button>  ');
            modal.append(close_icon);

            $.each(data, function (index, item) {
                var row = $('<div class="row"></div>');
                var key = $('<div class="name">' + index + '</div>');
                var value = $('<div class="value"></div>');

                if ('file_path' === index) {
                    if ($.isArray(item)) {
                        $.each(item, function (ind, path) {
                            var img = null;

                            if (path.indexOf('.pdf') >= 0) {
                                img = $('<embed src="' + path + '"><a href="' + path + '" target="_blank">Open</a>');
                            } else {
                                img = $('<img src="' + path + '" alt=""><a href="' + path + '" target="_blank">Open</a>');
                            }

                            value.append(img);
                        });
                    } else {
                        var img = null;
                        if (item.indexOf('.pdf') >= 0) {
                            img = $('<embed src="' + item + '"><a href="' + item + '" target="_blank">Open</a>');
                        } else {
                            img = $('<img src="' + item + '" alt=""><a href="' + item + '" target="_blank">Open</a>');
                        }
                        value.append(img);
                    }
                } else {
                    value.text(item);
                }

                row.append(key, value);
                modal.append(row);
            });

            modal.css({
                width: '600px',
                maxHeight: '75%',
                padding: '15px',
                border: '1px solid #000',
                backgroundColor: '#fff',
                position: 'relative',
                overflow: 'scroll'
            });

            modal.find('.row').css({
                display: 'flex',
                flexDirection: 'row',
                alignItems: 'center',
                justifyContent: 'flex-start',
                margin: '15px 0',
                fontSize: '15px'
            });

            modal.find('.name').css({
                fontWeight: '700',
                marginRight: '15px'
            });

            modal.find('.value > img').css({
                width: '300px'
            });

            $(document.body).append(modal);
            center(modal);
            modal.show();

            close_icon.on('click', function () {
                modal.remove();
            });
        }

        function center(element) {
            element.css("position", "absolute");
            element.css("top", ($(window).height() - element.height()) / 2 + $(window).scrollTop() + "px");
            element.css("left", ($(window).width() - element.width()) / 2 + $(window).scrollLeft() + "px");
            return this;
        }
    });
})(jQuery);