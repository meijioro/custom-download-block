<?php

/**
 * ==============================
 * CDM custom file save render
 * ==============================
 */


class CustomizeFileBlockTemplate {

	function __construct() {
		add_filter( 'render_block', array($this,'file_block_template'), 10, 2 );
	}

	public function file_block_template($block_content, $block) {
		if ( $block['blockName'] !== 'core/file' ) {
			return $block_content;
		}

		if ($block && $block['attrs'] && isset($block['attrs']['href']) ) {
			$url = trim($block['attrs']['href']);
			$caption = wp_get_attachment_caption($block['attrs']['id']);
		}

		if ($block && $block['innerHTML']) {
			$title = strip_tags($block['innerHTML']);
		}

		ob_start();
	?>

		<?php if ($block_content) : ?>
			<li role="listitem">
				<a href="<?php echo $url; ?>" class="download-list-block__link" download>

					<i class="far fa-file-<?php echo $this->get_file_extension_icon($url); ?> fa-2x"></i>

					<div class="download-list-block__content">
						<span class="download-list-block__filename"><?php echo $title; ?></span>
						<?php if ($caption) : ?><br><span class="download-list-block__desc"><?php echo $caption; ?></span><?php endif; ?>
					</div>
				</a>
			</li>
		<?php endif; ?>

	<?php
		$output = $block_content ? ob_get_contents() : '';
		ob_end_clean();
		return $output;
	}

	/**
	 * File extension icon
	 *
	 * @param   string  attachment absolute file path
	 * @return  string  url path for file type icon
	 *
	 */
	public function get_file_extension_icon($url) {

		$extension = pathinfo($url)['extension'];

		//icon logic
		if (isset($extension)) {
			if ($extension == 'zip') {
				$icon = 'archive';
			} else if ($extension == 'pdf') {
				$icon = 'pdf';
			} else if ($extension == 'ppt' || $extension == 'pptx') {
				$icon = 'powerpoint';
			} else if ($extension == 'mp3') {
				$icon = 'audio';
			} else if ($extension == 'mp4' || $extension == 'avi' || $extension == 'mov') {
				$icon = 'pdf';
			} else if ($extension == 'doc' || $extension == 'docx' || $extension == 'rtf') {
				$icon = 'word';
			} else if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'png') {
				$icon = 'image';
			}
		}

		return $icon;
	}

} // close class

new CustomizeFileBlockTemplate();

?>
