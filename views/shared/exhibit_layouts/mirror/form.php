<?php // This code chunk creates the mirror exhibit page selection menu
$formStem = $block->getFormStem();
$db = get_db();
// set_time_limit(0);
?>


<?php # SELECT EXHIBIT ?>

<div>
<h4>Select an Exhibit</h4>

<?php
$exhibitsTable = $db->getTable("Exhibit");
$exhibits = $exhibitsTable->fetchObjects("SELECT * FROM omeka_exhibits");

echo "
<input id='exhibit-selector' type='text' list='testlist' style='width:20vw;' onclick='select();' oninput='submenuSelector();'>
<datalist id='testlist'>
";
foreach ($exhibits as $index => $exhibit) {
    $slug = $exhibit['slug'];
    echo "<option value='{$slug}'>";
}
echo "</datalist>";
?>
</div>


<script>
function submenuSelector() {
    var slug = document.getElementById('exhibit-selector').value;
    var submenus = document.getElementsByClassName('pages-sub-menu');
    for (div of submenus) {
        if (div.id == slug) {
            div.hidden = false;
        } else {
            div.hidden = true;
        }
    }
}
</script>





<?php # SELECT PAGE FROM EXHIBIT ?>

<div class='selected-items'><h4>Select a Page to Mirror</h4>
<div id='exhibit-page-display' class='selected-item-list'>

<?php
$pagesTable = $db->getTable("ExhibitPage");
$allPages = $pagesTable->fetchObjects("SELECT * FROM omeka_exhibit_pages");

########################################################## Create sub menu of pages for each exhibit
foreach ($exhibits as $index => $exhibit) {
    $slug = $exhibit['slug'];
    $id = $exhibit['id'];
    echo "<div class='pages-sub-menu' id='{$slug}' hidden>";

    $pages = array();
    foreach($allPages as $index => $page) {
        if ($page['exhibit_id'] == $id) {
            $pages[] = $page;
        }
    }

    foreach ($pages as $index => $exhibitPage) {
        // Get page data for menu
        $pageTitle = $exhibitPage->title;
        $pageID = $exhibitPage->id;
        $imageURL = "";
        
        // try {
        //     $imageURL = "";
        //     $pageAttachments = $exhibitPage->getAllAttachments();
        //     if (count($pageAttachments) > 0) {
        //         $item = $pageAttachments[0]->getItem();
        //         if ($item) {
        //             $fileAttachments = $item->getFiles();
        //             if (count($fileAttachments) > 0 && $fileAttachments[0]->hasThumbnail()) {
        //                 $imageURL = $fileAttachments[0]->getWebPath();
        //             }
        //         }
        //     }
        // } catch (Exception $e) {
        //     $imageURL = "";
        // }

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

    echo "</div>";


}
#################################################################

// Styles for page selection menu
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




<!-- <?php // Temporary help text explaining how to get ID from URL ?>
    <p>Find the ID of the exhibit page you want to mirror by navigating to the exhibit page on the admin side, and taking the number at the end of the URL.</p>
    <p>For example: https://www.myomekasite.com/admin/exhibits/edit-page/6 the ID for this page would be 6.</p>
    <p>Insert this ID in the textbox below to mirror the page.</p> -->





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