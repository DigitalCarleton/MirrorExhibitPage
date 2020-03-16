<?php
// Get current page ID and mirrored page ID
$db = get_db();
$table = $db->getTable('ExhibitPage');
$blocksTable = $db->getTable('ExhibitPageBlock');
$currentPageID = (int)$block->getPage(0)['id'];
$mirroredPageID = (int)$blocksTable->fetchObjects("SELECT text FROM omeka_exhibit_page_blocks WHERE page_id = {$currentPageID}")[0]['text'];


# GET PAGE OBJECT FOR exhibit_builder_render_exhibit_page
$mirroredContent = $table->fetchObject("SELECT * FROM omeka_exhibit_pages WHERE id = {$mirroredPageID}");


# RENDER CONTENT FOR MIRRORED PAGE
echo exhibit_builder_render_exhibit_page($exhibitPage = $mirroredContent);