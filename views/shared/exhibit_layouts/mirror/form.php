<?php // This code chunk creates the mirror exhibit page selection menu
$formStem = $block->getFormStem();
$db = get_db();
?>


<?php # SELECT EXHIBIT ?>
<div>
<h4>Select an Exhibit</h4>

<?php
$exhibitsTable = $db->getTable("Exhibit");
$exhibits = $exhibitsTable->fetchObjects("SELECT * FROM `{$db->prefix}exhibits` ");

echo "
<input id='exhibit-selector' type='text' list='testlist' style='width:20vw;' onclick='select();' autocomplete='off'>
<datalist id='testlist'>
";
foreach ($exhibits as $index => $exhibit) {
    $title = htmlspecialchars($exhibit['title'], ENT_QUOTES);
    $slug = $exhibit['slug'];
    echo "<option id='{$slug}' value='{$title}'>";
}
echo "</datalist>";
?>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
$(function() {
  $('#exhibit-selector').on('input',function() {
    var opt = $('option[value="'+$(this).val()+'"]');
    var slug = opt.attr('id');
    var submenus = document.getElementsByClassName('pages-sub-menu');
    for (div of submenus) {
        if (div.id == slug) {
            div.hidden = false;
        } else {
            div.hidden = true;
        }
    }
  });
});
</script>




<?php # SELECT PAGE FROM EXHIBIT ?>
<div class='selected-items'><h4>Select a Page to Mirror</h4>
<div id='exhibit-page-display' class='selected-item-list'>

<?php
$pagesTable = $db->getTable("ExhibitPage");
$allPages = $pagesTable->fetchObjects("SELECT * FROM `{$db->prefix}exhibit_pages` ");

foreach ($exhibits as $index => $exhibit) {
    $slug = $exhibit['slug'];
    $id = $exhibit['id'];
    echo "<div class='pages-sub-menu' id='{$slug}' hidden>";

    foreach($allPages as $index => $page) {
        if ($page['exhibit_id'] == $id) {
            // Get page data for menu
            $pageTitle = $page->title;
            $pageID = $page->id;
            $imageURL = "";
            
            // THIS SECTION FINDS BACKGROUND IMAGES FOR ALL MENU ELEMENTS -- VERY SLOW, CAN BE COMMENTED OUT
            try {
                $pageAttachments = $page->getAllAttachments();
                if (count($pageAttachments) > 0) {
                    $item = $pageAttachments[0]->getItem();
                    if ($item) {
                        $fileAttachments = $item->getFiles();
                        if (count($fileAttachments) > 0 && $fileAttachments[0]->hasThumbnail()) {
                            $imageURL = $fileAttachments[0]->getWebPath();
                        }
                    }
                }
            } catch (Exception $e) {
                $imageURL = "";
            }

            // Create menu element with page data
            echo "
            <div class='attachment' data-attachment-index='{$index}' onclick=\"document.getElementsByName('{$formStem}[text]')[0].value = {$pageID};\">
                <div class='attachment-body'>
                    <div class='attachment-background' style=\"background: url('{$imageURL}') center / cover\"></div>
                    <h5>{$pageTitle}<br>Page ID: {$pageID}</h5>
                </div>
            </div>
            ";
        }
    }
    echo "</div>";
}

// Styles for page selection submenu
echo "</div>
<style>
#exhibit-page-display {
    overflow-y: scroll;
    max-height: 30vh;;
    margin-bottom: 1vh;
}
</style>
";
?>
</div>





<?php // This section creates the Mirror Page ID text box, which is how the page ID gets saved to the database.
    $blocksTable = $db->getTable('ExhibitPageBlock');
    $page = $block->getPage(0);
    $currentPageID = (int)$page['id'];
    if (count($blocksTable->fetchObjects("SELECT layout, text FROM `{$db->prefix}exhibit_page_blocks` WHERE page_id = {$currentPageID}")) > 0) {
        $blocksData = $blocksTable->fetchObjects("SELECT layout, text FROM `{$db->prefix}exhibit_page_blocks` WHERE page_id = {$currentPageID}");
        foreach ($blocksData as $index => $block) {
            if ($block['layout'] == 'mirror') {
                $mirroredPageID = (int)$block['text'];
            }
        }
    } else {
        $mirroredPageID = "";
    }
?>

<div>
    <h4><?php echo __('Mirror Page ID'); ?></h4>
    <?php 
        $idStem = str_replace('[', '-', str_replace(']', '-', $formStem));
        echo $this->formText($formStem . '[text]', $mirroredPageID);
        echo "<i style='opacity: 0.6; margin-left: 1em;'>You can also find the page ID at the end of any edit-page URL, and enter it directly.</i>";
    ?>
</div>