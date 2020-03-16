<?php
//The title of the page
$title = ($actionName == 'Add') ? __('Add Page') : __('Edit Page "%s"', $exhibit_page->title);
echo head(array('title'=> $title, 'bodyclass'=>'exhibits'));
?>
<?php //creates two URLS that show you what pages you've been from (Exhibits > PageSite > Add Page) ?>
<div id="exhibits-breadcrumb">
    <a href="<?php echo html_escape(url('exhibits')); ?>"><?php echo __('Exhibits'); ?></a> &gt;
    <a href="<?php echo html_escape(url('exhibits/edit/' . $exhibit['id']));?>"><?php echo html_escape($exhibit['title']); ?></a>  &gt;
    <?php echo html_escape($title); ?>
</div>
<?php echo flash(); //Echoes a returned flashed message from the controller. ?>
<form id="exhibit-page-form" method="post">
    <div class="seven columns alpha">
    <fieldset>
        <?php //A label and textbox for the Page Title form box ?>
        <div class="field">
            <div class="two columns alpha">
            <?php echo $this->formLabel('title', __('Page Title')); ?>
            </div>
            <div class="inputs five columns omega">
            <?php echo $this->formText('title', $exhibit_page->title); ?>
            </div>
        </div>
        <?php //A label and textbox for the Page Slug form box ?>
        <div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('slug', __('Page Slug')); ?>
            </div>
            <div class="inputs five columns omega">
                <p class="explanation"><?php echo __('No spaces or special characters allowed'); ?></p>
                <?php echo $this->formText('slug', $exhibit_page->slug); ?>
            </div>
        </div>

    </fieldset>
    <?php //Where you add your content blocks ?>
    <fieldset id="block-container">
        <?php //Collapse and Expand links and gray-text instructions ?>
        <h2><?php echo __('Content'); ?></h2>
        <span class="collapse"><?php echo __('Collapse All'); ?></span>
        <span class="expand"><?php echo __('Expand All'); ?></span>
        <p class="instructions"><?php echo __('To reorder blocks and items, click and drag them to the preferred location.'); ?></p>
        <?php
        /*foreach: echoes all the blocks in a page in order
        - $index being an $exhibit_page
        - $block being the collection of blocks on the page
        - the order of the block is the $index number + 1
        */
        foreach ($exhibit_page->getPageBlocks() as $index => $block):
            $block->order = $index + 1;
            echo $this->partial('exhibits/block-form.php', array('block' => $block));
        endforeach;
        ?>
        <?php //Allows you to add a new block and shows you images with options ?>
        <div class="add-block">
            <h2><?php echo __('New Block'); ?></h2>
            <div class="layout-select">
                <h3><?php echo __('Select a layout'); ?></h3>
                <div class="layout-thumbs">
                <?php
                /*the double colon, is a token that allows access to static, constant, and overridden
                properties or methods of a class. */
                    $layouts = ExhibitLayout::getLayouts();
                    /*For each type of layout, echoe its ID, img icon, name, etc */
                    foreach ($layouts as $layout) {
                        $layout_id = html_escape($layout->id);
                        echo '<div class="layout" id="' . $layout_id . '">';
                        echo '<img src="' . html_escape($layout->getIconUrl()) . '">';
                        echo '<span class="layout-name">' . $layout->name . '</span>';
                        echo '<input type="radio" name="new-block-layout" value="'. $layout_id .'">';
                        echo '</div>';
                    }
                    //for each layout, print its description
                    foreach ($layouts as $layout) {
                        echo '<div class="'.html_escape($layout->id).' layout-description">';
                        echo $layout->description;
                        echo '</div>';
                    }
                ?>
                <?php //a button to add a content block ?>
                <a class="add-link big button" href="#"><?php echo __('Add new content block'); ?></a>
                </div>
            </div>
        </div>
    </fieldset>
    </div>
    <?php echo $csrf;
    //save changes column on the right ?>
    <div class="three columns omega">
        <div id="save" class="panel">
            <?php echo $this->formSubmit('continue', __('Save Changes'), array('class'=>'submit big green button')); ?>
            <?php echo $this->formSubmit('add-another-page', __('Save and Add Another Page'), array('class'=>'submit big green button')); ?>
            <?php if ($exhibit_page->exists()): ?>
                <?php echo exhibit_builder_link_to_exhibit($exhibit, __('View Public Page'), array('class' => 'big blue button', 'target' => '_blank'), $exhibit_page); ?>
            <?php endif; ?>
        </div>
    </div>
</form>
<?php //This item-select div must be outside the <form> tag for this page, b/c IE7 can't handle nested form tags. ?>
<?php //a pop-up panel to attach items to a content block ?>
<div id="attachment-panel" title="<?php echo html_escape(__('Attach an Item')); ?>">
    <div id="item-form">
        <?php //this button is used when you select an item and then change the item ?>
        <button type="button" id="revert-selected-item"><?php echo __('Revert to Selected Item'); ?></button>
        <?php //this button changes the searching from seeing a list of all items to using a form to search ?>
        <button type="button" id="show-or-hide-search" class="show-form blue">
            <span class="show-search-label"><?php echo __('Show Search Form'); ?></span>
            <span class="hide-search-label"><?php echo __('Hide Search Form'); ?></span>
        </button>
        <?php //view all items sends you to page one of the item viewing search mode
//IMPORTANT!!!
//IMPORTANT!!!
//IMPORTANT!!!
        ?>
        <a href="<?php echo url('exhibit-builder/items/browse'); ?>" id="view-all-items" class="green button"><?php echo __('View All Items'); ?></a>
        <?php //url is the URL the form should submit to. ?>

        <div id="page-search-form" class="container-twelve">
        <?php //url() creates the URL the form should submit to.
            /* first param: $options (mixed) – If a string is passed it is treated as an Omeka-relative link.
            So, passing ‘items’ would create a link to the items page. If an array is passed
            (or no argument given), it is treated as options to be passed to Omeka’s routing system.
            second param: $route (string) – The route to use if an array is passed in the first argument. */



            //ECHOES:

            //items_search_form: Return the HTML for an item search form.
            /*second param: $formActionUri (string) – URL the form should submit to.
            If omitted, the form submits to the default items/browse page.*/
            //echo items_search_form(array('id' => 'search'), $action);

//CUSTOMIZATION!!
            $props = array('id' => 'search');
            $action = url(array('module' => 'exhibit-builder',
                'controller' => 'items', 'action' => 'browse'), 'default', array(), true);
            //$action = '/omeka/admin/exhibit-builder/items/browse';
            //url: /omeka/admin/'module'/'controller'/'action'

            $formActionUri = $action;
            $buttonText = null;
            //Think of it as the code from the partial is put directly below as an insert
            //The array passed is a way of passing variables to the view partial

            echo get_view()->partial(
              'items/page-search-form.php',
                array('exhibit' => $exhibit, 'formAttributes' => $props, 'formActionUri' => $formActionUri, 'buttonText' => $buttonText)
            );
//END OF CUSTOMIZATION!!

        ?>
        </div>
        <div id="item-select"></div>
    </div>
    <?php //after selecting an item, options are displayed ?>
    <div id="attachment-options">
        <button type="button" id="change-selected-item"><?php echo __('Change Selected Item'); ?></button>
        <div class="options">
            <div id="attachment-item-options"></div>
            <?php //creates a text box to write a caption ?>
            <div class="item-caption">
                <p class="direction"><?php echo __('Provide a caption.'); ?></p>
                <div class="inputs">
                    <?php echo $this->formTextarea('caption', '', array('rows' => 3, 'id' => 'attachment-caption')); ?>
                </div>
            </div>
        </div>
        <?php //An 'Apply' button to add the attachment ?>
        <div id="attachment-save">
            <button type="submit" id="apply-attachment"><?php echo __('Apply'); ?></button>
        </div>
    </div>
    <div id="attachment-panel-loading"><span class="spinner"></span></div>
</div>
<script type="text/javascript">
jQuery(document).ready(function () {
    <?php /*ALL THESE JAVASCRIPT FUNCTIONS MAKE THE BOXES AND BUTTONS WORK AT THE BOTTOM
    TO SELECT WHAT KIND OF LAYOUT YOU WOULD LIKE
    json_encode: returns the JSON (JavaScript Object Notation) representation of a value */ ?>
    Omeka.ExhibitBuilder.setUpBlocks(<?php echo json_encode(url('exhibits/block-form')); ?>);
    <?php //js_escape — Escape a value for use in javascript. ?>
    Omeka.ExhibitBuilder.setUpItemsSelect(<?php echo js_escape(url('exhibits/attachment-item-options')); ?>);
    Omeka.ExhibitBuilder.setUpAttachments(<?php echo js_escape(url('exhibits/attachment')); ?>, <?php echo js_escape(url('exhibits/attachment-item-options')); ?>);
    <?php //if the exhibit page exists, then get the route to the URL
    /* url — Get a URL given the provided arguments.
      $route (string) – The route to use if an array is passed in the first argument.
      $queryParams (mixed) – A set of query string parameters to append to the URL
      $reset (bool) – Whether Omeka should discard the current route when generating the URL.
      $encode (bool) – Whether the URL should be URL-encoded
    */
    if ($exhibit_page->exists()) {
        $validateUrl = url(
            array('action' => 'validate-page', 'id' => $exhibit_page->id),
            'exhibitStandard', array(), true);
    } else {
        $validateUrl = url(
            array('action' => 'validate-page', 'exhibit_id' => $exhibit_page->exhibit_id,
                'parent_id' => $exhibit_page->parent_id),
            'exhibitAction', array(), true);
    }
    ?>
    Omeka.ExhibitBuilder.setUpPageValidate(<?php echo js_escape($validateUrl); ?>);

    // This block adds custom styles to the text edit form on exhibit pages.
    // Add classes to this menu and put the style declarations in style.css
    // -AM 2/10/17
    Omeka.wysiwyg({
      theme_advanced_buttons2: "forecolor,styleselect",
      style_formats: [{
          title: 'Highlight1 - gray w/bar',
          inline: 'span',
          classes: 'medium-block-1'
        }, {
          title: 'Highlight2 - white w/bar',
          inline: 'span',
          classes: 'medium-block-3'
        }, {
          title: 'Quote1 - double gray',
          inline: 'span',
          classes: 'medium-block-2'
        }, {
          title: 'Quote2 - single green',
          inline: 'span',
          classes: 'medium-block-4'
        }, {
          title: 'Pull quote',
          inline: 'span',
          classes: 'pull-quote'
        }, {
          title: 'Transcription',
          block: 'div',
          classes: 'transcription'
        }, {
          title: 'Transcription link',
          selector: 'a',
          classes: 'show-transcription'
        }],
    });
    jQuery(document).on('exhibit-builder-refresh-wysiwyg', function (event) {
        // Add tinyMCE to all textareas in the div where the item was attached.
        jQuery(event.target).find('textarea').each(function () {
            tinyMCE.execCommand('mceAddControl', false, this.id);
        });
    });
});
</script>
<?php echo foot(); ?>
