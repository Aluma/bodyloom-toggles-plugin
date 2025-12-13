jQuery(window).on('elementor/frontend/init', function () {

    var BodyloomTogglesHandler = elementorModules.frontend.handlers.Base.extend({

        getDefaultSettings: function () {
            return {
                selectors: {
                    toggleTitle: '.bodyloom-toggles__title',
                    toggleContent: '.bodyloom-toggles__content',
                    wrapper: '.bodyloom-toggles__list'
                },
                classes: {
                    active: 'active-toggle'
                },
                showTabFn: 'slideDown',
                hideTabFn: 'slideUp'
            };
        },

        getDefaultElements: function () {
            var selectors = this.getSettings('selectors');
            return {
                $wrapper: this.$element.find(selectors.wrapper),
                $toggleTitles: this.$element.find(selectors.toggleTitle),
                $toggleContents: this.$element.find(selectors.toggleContent)
            };
        },

        bindEvents: function () {
            this.elements.$toggleTitles.on('click', this.onTitleClick.bind(this));
            this.elements.$toggleTitles.on('keydown', this.onTitleKeyPress.bind(this));
        },

        onInit: function () {
            elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

            // Check for Hash
            this.checkHash();

            // Default Active Tab if specified (and no hash overriding)
            if (!location.hash) {
                var defaultActive = this.getElementSettings('default_toggle');
                // Check if any tab is already active (rendered by PHP)
                var $activeTitle = this.elements.$toggleTitles.filter('.' + this.getSettings('classes.active'));

                if (!$activeTitle.length && defaultActive > 0) {
                    this.activateTab(defaultActive);
                }
            }
        },

        onTitleClick: function (event) {
            event.preventDefault();
            var $clickedTitle = jQuery(event.currentTarget);
            var tabIndex = $clickedTitle.data('tab');
            this.changeTab(tabIndex, $clickedTitle);
        },

        onTitleKeyPress: function (event) {
            if (event.which === 13 || event.which === 32) { // Enter or Space
                event.preventDefault();
                this.elements.$toggleTitles.filter(event.currentTarget).trigger('click');
            }
        },

        changeTab: function (tabIndex, $clickedTitle) {
            var settings = this.getSettings();
            var isActive = $clickedTitle.hasClass(settings.classes.active);
            var isAccordion = (this.getElementSettings('type') === 'accordion');

            if (isActive) {
                // If it's a toggle, or accordion allowing closure (usually standard behavior)
                // Reference accordion logic might force one open? The reference logic allows closing if toggleSelf is true or not strict accordion.
                // We will implement standard toggle behavior: click active to close.
                this.deactivateTab($clickedTitle);
                return;
            }

            if (isAccordion) {
                this.deactivateAllTabs();
            }

            this.activateTab(tabIndex, $clickedTitle);
        },

        activateTab: function (tabIndex, $title) {
            var settings = this.getSettings();

            if (!$title) {
                $title = this.elements.$toggleTitles.filter('[data-tab="' + tabIndex + '"]');
            }

            var $content = this.elements.$toggleContents.filter('[data-tab="' + tabIndex + '"]');

            $title.addClass(settings.classes.active).attr('aria-expanded', 'true');
            $content.addClass(settings.classes.active);

            // If animation needed
            $content[settings.showTabFn](300);
        },

        deactivateTab: function ($title) {
            var settings = this.getSettings();
            var tabIndex = $title.data('tab');
            var $content = this.elements.$toggleContents.filter('[data-tab="' + tabIndex + '"]');

            $title.removeClass(settings.classes.active).attr('aria-expanded', 'false');
            $content.removeClass(settings.classes.active);

            $content[settings.hideTabFn](300);
        },

        deactivateAllTabs: function () {
            var self = this;
            this.elements.$toggleTitles.each(function () {
                var $title = jQuery(this);
                if ($title.hasClass(self.getSettings('classes.active'))) {
                    self.deactivateTab($title);
                }
            });
        },

        checkHash: function () {
            var hash = location.hash;
            // Support custom ID (item level) or default anchor behavior
            if (hash) {
                // Check if hash matches a toggle_custom_id
                var $targetItem = this.$element.find('[toggle_custom_id="' + hash + '"], [toggle_custom_id="' + hash.replace('#', '') + '"]');

                if ($targetItem.length) {
                    var $title = $targetItem.find(this.getSettings('selectors.toggleTitle'));
                    this.activateTab($title.data('tab'), $title);

                    // Scroll
                    jQuery('html, body').animate({
                        scrollTop: $targetItem.offset().top - 100
                    }, 500);
                }
            }
        }
    });

    elementorFrontend.hooks.addAction('frontend/element_ready/bodyloom-toggles.default', function ($scope) {
        new BodyloomTogglesHandler({ $element: $scope });
    });
});
