jQuery(document).ready(function($) {
    var BodyloomToggles = {
        init: function() {
            this.bindEvents();
            this.enforceState();
            this.checkHash();
        },

        enforceState: function() {
            $('.bodyloom-toggles-item').each(function() {
                var $this = $(this);
                if ($this.hasClass('active')) {
                    $this.find('.bodyloom-toggles-content').show();
                } else {
                    $this.find('.bodyloom-toggles-content').hide();
                }
            });
        },

        bindEvents: function() {
            $(document).on('click', '.bodyloom-toggles-title', function(e) {
                e.preventDefault();
                var $title = $(this);
                var $wrapper = $title.closest('.bodyloom-toggles-wrapper');
                var type = $wrapper.data('type');
                
                if ($title.parent().hasClass('active')) {
                    if (type === 'toggles') {
                        BodyloomToggles.closeTab($title);
                    } else {
                        // Accordion: clicking active tab closes it (optional, usually keeps one open or allows closing)
                        BodyloomToggles.closeTab($title); 
                    }
                } else {
                    if (type === 'accordion') {
                        $wrapper.find('.bodyloom-toggles-item.active .bodyloom-toggles-title').each(function() {
                            BodyloomToggles.closeTab($(this));
                        });
                    }
                    BodyloomToggles.openTab($title);
                }
            });

            // Keyboard accessibility
            $(document).on('keydown', '.bodyloom-toggles-title', function(e) {
                if (e.which === 13 || e.which === 32) { // Enter or Space
                    e.preventDefault();
                    $(this).click();
                }
            });
        },

        openTab: function($title) {
            var $item = $title.parent();
            var $content = $item.find('.bodyloom-toggles-content');
            
            $item.addClass('active');
            $content.slideDown(300);
        },

        closeTab: function($title) {
            var $item = $title.parent();
            var $content = $item.find('.bodyloom-toggles-content');
            
            $item.removeClass('active');
            $content.slideUp(300);
        },

        checkHash: function() {
            var hash = window.location.hash;
            if (hash) {
                var $target = $(hash);
                if ($target.length && $target.hasClass('bodyloom-toggles-item')) {
                    var $title = $target.find('.bodyloom-toggles-title');
                    // Scroll to item
                    $('html, body').animate({
                        scrollTop: $target.offset().top - 100
                    }, 500);
                    // Open item
                    if (!$target.hasClass('active')) {
                        $title.click();
                    }
                }
            }
        }
    };

    BodyloomToggles.init();
});
