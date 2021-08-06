<?php

class DownloadListTemplate {

	function __construct() {
		register_block_type('downloads-list-block/block', array( 'render_callback' => array($this,'downloadlist_template')) );
	}

	function downloadlist_template($attributes, $content) {
		ob_start();
	?>

		<section class="download-list-block">
			<h2 class="sr-only">List of file attachments to download</h2>
			<ul class="download-list-block__list" role="list">
				<?php echo $content; ?>
			</ul>
		</section>

	<?php
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

}

new DownloadListTemplate();
