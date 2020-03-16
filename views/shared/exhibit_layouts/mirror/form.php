<?php
//Gets the unique form name, the “stem”, from the block object
$formStem = $block->getFormStem();
//Gets the pre-existing options from the block object
$options = $block->getOptions();
?>

<?php //The attachment interface is added by a simple helper ?>
<div class="selected-items">
    <h4><?php echo __('Pages'); ?></h4>
    <?php echo $this->exhibitFormAttachments($block); ?>
    <?php
        /*$attachments = $block->ExhibitBlockAttachment;

        $html = '<div class="selected-item-list">';

        foreach ($attachments as $index => $attachment) {
            $html .= $this->view->partial('exhibits/attachment.php',
                array(
                    'attachment' => $attachment,
                    'block' => $block,
                    'index' => $index
                )
            );
        }
        $html .= '<div class="add-item button">Add Page</div>';
        $html .= '</div>';
        echo $html; //return $html;*/
    ?>
</div>


<div class="block-text">
    <h4><?php echo __('Text'); ?></h4>
    <?php echo $this->exhibitFormText($block); ?>
</div>




<!-- SET UP MIRROR PAGE SELECTOR -->

<?php 
    $db = get_db();
    $blocksTable = $db->getTable('ExhibitPageBlock');
    $currentPageID = (int)$block->getPage(0)['id'];
    $mirroredPageID = (int)$blocksTable->fetchObjects("SELECT text FROM omeka_exhibit_page_blocks WHERE page_id = {$currentPageID}")[0]['text'];
?>

<div>
    <h4><?php echo __('Mirror Page ID'); ?></h4>
    <?php 
        // NEED TO FIGURE OUT HOW TO GET THIS VALUE TO SAVE TO THE DATABASE
        echo $this->formText($formStem, $mirroredPageID);
    ?>
</div>



<!-- -- -->



<div class="layout-options">
    <div class="block-header">
        <h4><?php echo __('Layout Options'); ?></h4>
        <div class="drawer"></div>
    </div>

    <div class="file-position">
        <?php /* The FormLabel view helper is used to render a <label> HTML element and its
        attributes. If you have a Zend\\I18n\\Translator\\Translator attached, FormLabel will
        translate the label contents during it’s rendering. */
        //Creates a label for the 'File Position' option
        echo $this->formLabel($formStem . '[options][file-position]', __('File position')); ?>
        <?php /* formSelect($name, $value, $attribs, $options): Creates a <select>...</select>
        block, with one <option>one for each of the $options elements. In the $options array,
        the element key is the option value, and the element value is the option label. The
        $value option(s) will be preselected for you.
        The <select> element is used to create a drop-down list.
        The <option> tags inside the <select> element define the available options in the list.
        */
        //Creates a drop-down for the 'File Position' option in the form
        echo $this->formSelect($formStem . '[options][file-position]',
            @$options['file-position'], array(),
            array('left' => __('Left'), 'right' => __('Right')));
        ?>
    </div>

    <div class="file-size">
        <?php //creates a form Label for the 'File Size' option
        echo $this->formLabel($formStem . '[options][file-size]', __('File size')); ?>
        <?php //creates a form drop-down for the 'File Size' option
        echo $this->formSelect($formStem . '[options][file-size]',
            @$options['file-size'], array(),
            array( //the different options for the drop-down
                'fullsize' => __('Fullsize'),
                'thumbnail' => __('Thumbnail'),
                'square_thumbnail' => __('Square Thumbnail')
            ));
        ?>
    </div>

    <div class="captions-position">
        <?php //creates a form Label for the 'Captions position' option
        echo $this->formLabel($formStem . '[options][captions-position]', __('Captions position')); ?>
        <?php //creates a form drop-down for the 'Captions position' option
        echo $this->formSelect($formStem . '[options][captions-position]',
            @$options['captions-position'], array(),
            array(
                'center' => __('Center'),
                'left' => __('Left'),
                'right' => __('Right')
            ));
        ?>
    </div>

</div>
