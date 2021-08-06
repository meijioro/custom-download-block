/**
 * External dependencies
 */
import classnames from "classnames";

/**
 * WordPress dependencies
 */
import { addFilter } from "@wordpress/hooks";
import { getBlobByURL, isBlobURL, revokeBlobURL } from "@wordpress/blob";
import { __unstableGetAnimateClassName as getAnimateClassName } from "@wordpress/components";
import { useSelect } from "@wordpress/data";
import {
	BlockControls,
	BlockIcon,
	MediaPlaceholder,
	MediaReplaceFlow,
	RichText,
	useBlockProps,
	store as blockEditorStore,
} from "@wordpress/block-editor";
import { useEffect, useState, Fragment } from "@wordpress/element";
import { __, _x } from "@wordpress/i18n";
import { file as icon } from "@wordpress/icons";
import { store as coreStore } from "@wordpress/core-data";

/**
 * Internal dependencies
 */

function CustomizeFileBlockEdit({
	attributes,
	setAttributes,
	noticeUI,
	noticeOperations,
}) {
	const { id, fileName, href, textLinkHref } = attributes;
	const [hasError, setHasError] = useState(false);
	const { media, mediaUpload } = useSelect(
		(select) => ({
			media: id === undefined ? undefined : select(coreStore).getMedia(id),
			mediaUpload: select(blockEditorStore).getSettings().mediaUpload,
		}),
		[id]
	);

	useEffect(() => {
		// Upload a file drag-and-dropped into the editor
		if (isBlobURL(href)) {
			const file = getBlobByURL(href);

			mediaUpload({
				filesList: [file],
				onFileChange: ([newMedia]) => onSelectFile(newMedia),
				onError: (message) => {
					setHasError(true);
					noticeOperations.createErrorNotice(message);
				},
			});

			revokeBlobURL(href);
		}
	}, []);

	function onSelectFile(newMedia) {
		if (newMedia && newMedia.url) {
			setHasError(false);
			setAttributes({
				href: newMedia.url,
				fileName: newMedia.title,
				textLinkHref: newMedia.url,
				id: newMedia.id,
			});
		}
	}

	function onUploadError(message) {
		setHasError(true);
		noticeOperations.removeAllNotices();
		noticeOperations.createErrorNotice(message);
	}

	const blockProps = useBlockProps({
		className: classnames(
			isBlobURL(href) && getAnimateClassName({ type: "loading" }),
			{
				"is-transient": isBlobURL(href),
			}
		),
	});

	if (!href || hasError) {
		return (
			<div {...blockProps}>
				<MediaPlaceholder
					icon={<BlockIcon icon={icon} />}
					labels={{
						title: __("File"),
						instructions: __(
							"Upload a file or pick one from your media library."
						),
					}}
					onSelect={onSelectFile}
					notices={noticeUI}
					onError={onUploadError}
					accept="*"
				/>
			</div>
		);
	}

	return (
		<Fragment>
			<BlockControls group="other">
				<MediaReplaceFlow
					mediaId={id}
					mediaURL={href}
					accept="*"
					onSelect={onSelectFile}
					onError={onUploadError}
				/>
			</BlockControls>
			<div {...blockProps}>
				<div className={"wp-block-file__content-wrapper"}>
					<RichText
						style={{ display: "inline-block" }}
						tagName="a" // must be block-level or else cursor disappears
						value={fileName}
						placeholder={__("Write file name…")}
						withoutInteractiveFormatting
						allowedFormats={[]}
						onChange={(text) => setAttributes({ fileName: text })}
						href={textLinkHref}
					/>
				</div>
			</div>
		</Fragment>
	);
}

/**
 * Update attributes and edit
 *
 * @param {object} settings Current block settings.
 * @param {string} name Name of block.
 *
 * @returns {object} Modified block settings.
 */
import lodash from "lodash";

addFilter(
	"blocks.registerBlockType",
	"download-list-block/file-edit-settings-block",
	function (settings) {
		if (settings.name !== "core/file") {
			return settings;
		}

		// file block only allowed inside download list block
		settings = lodash.assign({}, settings, {
			parent: ["downloads-list-block/block"],
		});

		settings = lodash.assign({}, settings, {
			attributes: lodash.assign({}, settings.attributes, {
				showDownloadButton: {
					default: false,
					type: "boolean",
				},
			}),
			supports: lodash.assign({}, settings.supports, {
				align: false,
			}),
		});

		const newSettings = {
			...settings,
			edit: CustomizeFileBlockEdit,
		};

		return newSettings;
	}
);
