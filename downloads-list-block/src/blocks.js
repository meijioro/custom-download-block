/**
 * Register block
 * Blocks use React
 *
 * https://developer.wordpress.org/block-editor/tutorials/block-tutorial/nested-blocks-inner-blocks/
 */

//  Import CSS for build to work
import "./styles/editor.scss";
import "./styles/style.scss";

// Import customized File block update
import "./file-block.js";

const ALLOWED_BLOCKS = ["core/file"];
const TEMPLATE = [["core/file"], ["core/file"]];

import { InnerBlocks } from "@wordpress/block-editor";
import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

registerBlockType("downloads-list-block/block", {
	title: __("Download List"),
	category: "common",
	icon: "download",
	keywords: [
		__("attachements"),
		__("list"),
		__("files"),
		__("pdf"),
		__("doc"),
		__("zip"),
		__("ppt"),
		__("download"),
	],
	edit: (props) => {
		return (
			<div className="components-placeholder wp-block-embed is-large">
				<div className="components-placeholder__label">Download List</div>
				<InnerBlocks
					allowedBlocks={ALLOWED_BLOCKS}
					template={TEMPLATE}
					orientation="vertical"
				/>
			</div>
		);
	},

	save: (props) => {
		return <InnerBlocks.Content />;
	},
});
