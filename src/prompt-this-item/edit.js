import { _x } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, PanelColorSettings } from '@wordpress/block-editor';
import { PanelBody, SelectControl, TextControl, TextareaControl } from '@wordpress/components';
import './editor.scss';
import { getPlatformConfig, getPlatformOptions, getSvgString } from './icons-colour';

export default function Edit({ attributes, setAttributes, context }) {
    const { platform, customLabel, promptText, iconColor } = attributes;

    const resolvedIconColor = iconColor || context['zp/ai-prompt-this/iconColor'] || '';
    const iconOnly = context['zp/ai-prompt-this/iconOnly'] ?? false;

    const platformKey = platform.key;
    const platformConfig = getPlatformConfig(platformKey);
    const label = customLabel || platformConfig.name;
    const PlatformIcon = resolvedIconColor && platformConfig.iconSolid ? platformConfig.iconSolid : platformConfig.icon;

    const iconSpanStyle = resolvedIconColor ? { color: resolvedIconColor } : {};

    const linkClasses = `platform-${platformKey}`;
    const blockProps = useBlockProps({
        className: linkClasses,
    });

    return (
        <>
            <InspectorControls>
                <PanelColorSettings
                    title={_x('Icon Color', 'Block prompt-this-item - panelcolorsettings title', 'ai-prompt-this')}
                    initialOpen={true}
                    colorSettings={[
                        {
                            value: iconColor,
                            onChange: (color) => setAttributes({ iconColor: color || '' }),
                            label: _x('Color', 'Block prompt-this-item - color setting label', 'ai-prompt-this'),
                        }
                    ]}
                />
                <PanelBody title={_x('Platform Settings', 'Block prompt-this-item - inspector panel title', 'ai-prompt-this')}>
                    <SelectControl
                        label={_x('AI Platform', 'Block prompt-this-item - selectcontrol label for AI platform', 'ai-prompt-this')}
                        value={platformKey}
                        options={getPlatformOptions()}
                        onChange={(value) => {
                            const selectedPlatform = getPlatformConfig(value);
                            setAttributes({
                                platform: {
                                    key: value,
                                    name: selectedPlatform.name,
                                    baseUrl: selectedPlatform.baseUrl,
                                    svg: getSvgString(selectedPlatform.icon),
                                    svgSolid: selectedPlatform.iconSolid ? getSvgString(selectedPlatform.iconSolid) : '',
                                }
                            });
                        }}
                    />
                    <TextControl
                        label={_x('Custom Label', 'Block prompt-this-item - textcontrol label for custom label', 'ai-prompt-this')}
                        value={customLabel}
                        onChange={(value) =>
                            setAttributes({ customLabel: value })
                        }
                        help={_x(
                            'Leave empty to use the platform name.',
                            'Block prompt-this-item - textcontrol help text for custom label',
                            'ai-prompt-this'
                        )}
                    />
                    <TextareaControl
                        __nextHasNoMarginBottom
                        label={_x('Custom Prompt', 'Block prompt-this-item - textareacontrol label for custom prompt', 'ai-prompt-this')}
                        value={promptText}
                        onChange={(value) =>
                            setAttributes({ promptText: value })
                        }
                        help={_x(
                            "Leave empty to use the parent block's prompt text.",
                            'Block prompt-this-item - textareacontrol help text for custom prompt',
                            'ai-prompt-this'
                        )}
                        rows={2}
                    />
                </PanelBody>
            </InspectorControls>
            <li {...blockProps}>
                {/* eslint-disable-next-line jsx-a11y/anchor-is-valid -- Editor preview; href is generated server-side in render.php. */}
                <a className={linkClasses}>
                    <span className="prompt-this-icon" style={iconSpanStyle}>
                        <PlatformIcon />
                    </span>
                    <span className={iconOnly ? 'screen-reader-text' : 'prompt-this-label'}>{label}</span>
                </a>
            </li>
        </>
    );
}
