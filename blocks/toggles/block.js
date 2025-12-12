(function (blocks, element, components, blockEditor, i18n) {
    var el = element.createElement;
    var registerBlockType = blocks.registerBlockType;
    var TextControl = components.TextControl;
    var SelectControl = components.SelectControl;
    var ToggleControl = components.ToggleControl;
    var PanelBody = components.PanelBody;
    var InspectorControls = blockEditor.InspectorControls;
    var ServerSideRender = components.ServerSideRender || wp.serverSideRender;
    var __ = i18n.__;

    registerBlockType('bodyloom/dynamic-toggles', {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            return [
                el(InspectorControls, { key: 'inspector' },
                    el(PanelBody, { title: __('Settings', 'bodyloom-dynamic-toggles'), initialOpen: true },
                        el(TextControl, {
                            label: __('Repeater Field Name', 'bodyloom-dynamic-toggles'),
                            value: attributes.repeater_field,
                            onChange: function (val) { setAttributes({ repeater_field: val }); }
                        }),
                        el(TextControl, {
                            label: __('Title Sub-Field', 'bodyloom-dynamic-toggles'),
                            value: attributes.title_field,
                            onChange: function (val) { setAttributes({ title_field: val }); }
                        }),
                        el(TextControl, {
                            label: __('Content Sub-Field', 'bodyloom-dynamic-toggles'),
                            value: attributes.content_field,
                            onChange: function (val) { setAttributes({ content_field: val }); }
                        }),
                        el(SelectControl, {
                            label: __('Type', 'bodyloom-dynamic-toggles'),
                            value: attributes.type,
                            options: [
                                { label: __('Toggles', 'bodyloom-dynamic-toggles'), value: 'toggles' },
                                { label: __('Accordion', 'bodyloom-dynamic-toggles'), value: 'accordion' }
                            ],
                            onChange: function (val) { setAttributes({ type: val }); }
                        }),
                        el(SelectControl, {
                            label: __('Style', 'bodyloom-dynamic-toggles'),
                            value: attributes.style,
                            options: [
                                { label: __('Default (Arrow)', 'bodyloom-dynamic-toggles'), value: 'default' },
                                { label: __('Plus/Minus', 'bodyloom-dynamic-toggles'), value: 'plus-minus' },
                                { label: __('Chevron', 'bodyloom-dynamic-toggles'), value: 'chevron' }
                            ],
                            onChange: function (val) { setAttributes({ style: val }); }
                        }),
                        el(ToggleControl, {
                            label: __('Open First Item', 'bodyloom-dynamic-toggles'),
                            checked: attributes.open_first,
                            onChange: function (val) { setAttributes({ open_first: val }); }
                        }),
                        el(ToggleControl, {
                            label: __('Enable FAQ Schema', 'bodyloom-dynamic-toggles'),
                            checked: attributes.faq_schema,
                            onChange: function (val) { setAttributes({ faq_schema: val }); }
                        })
                    )
                ),
                el('div', { className: props.className },
                    el(ServerSideRender, {
                        block: 'bodyloom/dynamic-toggles',
                        attributes: attributes
                    })
                )
            ];
        },
        save: function () {
            return null; // Rendered via PHP
        }
    });
})(
    window.wp.blocks,
    window.wp.element,
    window.wp.components,
    window.wp.blockEditor,
    window.wp.i18n
);
