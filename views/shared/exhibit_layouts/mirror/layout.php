<?php
// Get current page ID and mirrored page ID
$db = get_db();
$table = $db->getTable('ExhibitPage');
$blocksTable = $db->getTable('ExhibitPageBlock');
$currentPage = $block->getPage(0);
$currentPageID = (int)$currentPage['id'];
$blocksData = $blocksTable->fetchObjects("SELECT layout, text FROM omeka_exhibit_page_blocks WHERE page_id = {$currentPageID}");
foreach ($blocksData as $index => $block) {
    if ($block['layout'] == 'mirror') {
        $mirroredPageID = (int)$block['text'];
    }
}


# GET PAGE OBJECT FOR exhibit_builder_render_exhibit_page
$mirroredContent = $table->fetchObject("SELECT * FROM omeka_exhibit_pages WHERE id = {$mirroredPageID}");


# RENDER CONTENT FOR MIRRORED PAGE
echo "</div>"; # This end tag escapes the automatic mirror-layout page block that the content gets put into, so pages can be mirrored without nested blocks
echo exhibit_builder_render_exhibit_page($exhibitPage = $mirroredContent);
echo "<div>";