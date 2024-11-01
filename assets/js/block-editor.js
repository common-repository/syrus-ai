(function (wp) {
    var registerPlugin = wp.plugins.registerPlugin;
    var PluginPostStatusInfo = wp.editPost.PluginPostStatusInfo;
    var el = wp.element.createElement;
    var CheckboxControl = wp.components.CheckboxControl;
    var withSelect = wp.data.withSelect;
    var withDispatch = wp.data.withDispatch;
    var compose = wp.compose.compose;

    var CustomCheckboxControl = compose(
        withSelect(function (select) {
            return {
                customCheckbox: select('core/editor').getEditedPostAttribute('meta')['custom_checkbox_name']
            };
        }),
        withDispatch(function (dispatch) {
            return {
                updateCustomCheckbox: function (customCheckbox) {
                    dispatch('core/editor').editPost({ meta: { custom_checkbox_name: customCheckbox } });
                }
            };
        })
    )(function (_ref) {
        var customCheckbox = _ref.customCheckbox,
            updateCustomCheckbox = _ref.updateCustomCheckbox;

        var onChangeCustomCheckbox = function (newValue) {
            updateCustomCheckbox(newValue);
        };

        return el(CheckboxControl, {
            name: 'syrus_ai_share_chk',
            value: 1,
            label: el('span', null, [
                el('img', { src: args_block_editor.icon_url, alt: args_block_editor.label }),
                args_block_editor.label,
            ]),
            defaultChecked: true,
            checked: customCheckbox,
            onChange: onChangeCustomCheckbox
        });
    });

    registerPlugin('syrus-ai-plugin', {
        render: function render() {
            return el(PluginPostStatusInfo, {
                className: 'syrus-ai-plugin',
                icon: 'admin-plugins',
                title: 'Syrus AI Plugin'
            }, el(CustomCheckboxControl, null));
        }
    });
})(window.wp);