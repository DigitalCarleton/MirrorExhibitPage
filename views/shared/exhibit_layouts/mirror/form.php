<?php // This code chunk creates the mirror exhibit page selection menu ?>
<div class="selected-items">
    <h4><?php echo __('Select Page to Mirror'); ?></h4>

    <?php
    $formStem = $block->getFormStem();
    $db = get_db();
    $pagesTable = $db->getTable("ExhibitPage");
    $pages = $pagesTable->fetchObjects("SELECT * FROM omeka_exhibit_pages");

    $html = "<div id='exhibit-page-display' class='selected-item-list'>";

    foreach ($pages as $index => $exhibitPage) {
        // Get page data for menu
        $pageTitle = $exhibitPage->title;
        $pageID = $exhibitPage->id;
        $imageURL = "";

        $pageAttachments = $exhibitPage->getAllAttachments();
        if (count($pageAttachments) > 0) {
            $fileAttachments = $pageAttachments[0]->getItem()->getFiles();
            if ($fileAttachments[0]->hasThumbnail()) {
                $imageURL = $fileAttachments[0]->getWebPath();
            }
        }

        // Create menu element with page data
        $html .= "
        <div class='attachment' data-attachment-index='{$index}' onclick=\"document.getElementsByName('{$formStem}[text]')[0].value = {$pageID};\">
            <div class='attachment-body'>
                <div class='attachment-background' style=\"background: url('{$imageURL}') center / cover\"></div>
                <h5>{$pageTitle}<br>Page ID: {$pageID}</h5>
            </div>
        </div>
        ";
    }

    // Styles for page selection menu
    $html .= "</div>
    <style>
    #exhibit-page-display {
        overflow-y: scroll;
        height: 30vh;;
        margin-bottom: 1vh;
    }
    </style>
    ";

    echo $html;
    ?>
</div>



<?php // This section creates the Mirror Page ID text box, which is how the page ID gets saved to the database.
    $blocksTable = $db->getTable('ExhibitPageBlock');
    $page = $block->getPage(0);
    $currentPageID = (int)$page['id'];
    if (count($blocksTable->fetchObjects("SELECT text FROM omeka_exhibit_page_blocks WHERE page_id = {$currentPageID}")) > 0) {
        $blocksData = $blocksTable->fetchObjects("SELECT text FROM omeka_exhibit_page_blocks WHERE page_id = {$currentPageID}");
        $mirroredPageID = (int)$blocksData[0]['text'];
    } else {
        $mirroredPageID = "";
    }
?>

<div>
    <h4><?php echo __('Mirror Page ID'); ?></h4>
    <?php 
        $idStem = str_replace('[', '-', str_replace(']', '-', $formStem));
        echo $this->formText($formStem . '[text]', $mirroredPageID);
    ?>
</div>