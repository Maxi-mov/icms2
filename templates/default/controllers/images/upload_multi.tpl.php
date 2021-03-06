<?php

	$this->addJSFromContext( $this->getJavascriptFileName('fileuploader') );
	$this->addJSFromContext( $this->getJavascriptFileName('images-upload') );
    $this->addJS($this->getJavascriptFileName('jquery-ui'));
    $this->addCSS('templates/default/css/jquery-ui.css');

	$config = cmsConfig::getInstance();

    $dom_id = str_replace(array('[',']'), array('_l_', '_r_'), $name);

	$upload_url = $this->href_to('upload', $dom_id);

	if (is_array($sizes)) {
		$upload_url .= '?sizes=' . implode(',', $sizes);
	}

?>

<div id="widget_image_<?php echo $dom_id; ?>" class="widget_image_multi">

    <div class="data" style="display:none">
        <?php if ($images){ ?>
            <?php foreach($images as $idx => $paths){ ?>
                <?php foreach($paths as $path_name => $path){ ?>
                    <input type="hidden" name="<?php echo $name; ?>[<?php echo $idx; ?>][<?php echo $path_name; ?>]" value="<?php echo $path; ?>" rel="<?php echo $idx; ?>"/>
                <?php } ?>
            <?php } ?>
        <?php } ?>
    </div>

    <div class="previews_list">
        <?php if ($images){ ?>
            <?php foreach($images as $idx => $paths){ ?>
                <div class="preview block" rel="<?php echo $idx; ?>" data-paths="<?php html(json_encode($paths)); ?>">
					<?php  $is_image_exists = !empty($paths); ?>
					<?php if ($is_image_exists) { ?><img src="<?php echo $config->upload_host . '/' . end($paths); ?>" /><?php } ?>
                    <a href="javascript:" onclick="icms.images.removeOne('<?php echo $dom_id; ?>', <?php echo $idx; ?>)"><?php echo LANG_DELETE; ?></a>
                </div>
            <?php } ?>
        <?php } ?>
    </div>

    <div class="preview_template block" style="display:none">
        <img src="" />
        <a href="javascript:"><?php echo LANG_DELETE; ?></a>
    </div>

    <div class="upload block">
        <div id="file-uploader-<?php echo $dom_id; ?>"></div>
    </div>

    <?php if($allow_import_link){ ?>
        <div class="image_link upload block">
            <span><?php echo LANG_OR; ?></span> <a class="input_link_block" href="#"><?php echo LANG_PARSER_ADD_FROM_LINK; ?></a>
        </div>
    <?php } ?>
    <?php if($max_photos){ ?>
        <div class="upload block photo_limit_hint">
            <?php echo sprintf(LANG_PARSER_IMAGE_MAX_COUNT_HINT, html_spellcount($max_photos, LANG_PARSER_IMAGE_SPELL)); ?>
        </div>
    <?php } ?>

    <div class="loading block" style="display:none">
        <?php echo LANG_LOADING; ?>
    </div>

    <script type="text/javascript">
        <?php echo $this->getLangJS('LANG_SELECT_UPLOAD', 'LANG_DROP_TO_UPLOAD', 'LANG_CANCEL', 'LANG_ERROR'); ?>
        var LANG_UPLOAD_ERR_MAX_IMAGES = '<?php echo sprintf(LANG_PARSER_IMAGE_MAX_COUNT_HINT, html_spellcount($max_photos, LANG_PARSER_IMAGE_SPELL)); ?>';
        <?php if($max_photos && $images && count($images)){ ?>
            icms.images.uploaded_count = <?php echo count($images); ?>;
        <?php } ?>
        icms.images.createUploader('<?php echo $dom_id; ?>', '<?php echo $upload_url; ?>', <?php echo $max_photos; ?>);
        <?php if($allow_import_link){ ?>
            $(function(){
                $('#widget_image_<?php echo $dom_id; ?> .image_link a').on('click', function (){
                    link = prompt('<?php echo LANG_PARSER_ENTER_IMAGE_LINK; ?>');
                    if(link){
                        icms.images.uploadMultyByLink('<?php echo $dom_id; ?>', '<?php echo $upload_url; ?>', link, <?php echo $max_photos; ?>);
                    }
                    return false;
                });
            });
        <?php } ?>
        $(function(){
            icms.images.initSortable('<?php echo $dom_id; ?>');
        });
    </script>

</div>
