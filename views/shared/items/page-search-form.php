<?php
/* From page-form.php
    $action = url(array('module' => 'exhibit-builder',
      'controller' => 'items', 'action' => 'browse'), 'default', array(), true);
    $props = array('id' => 'search');
    $formActionUri = $action;
    $buttonText = null;
*/

if (!empty($formActionUri)):
    $formAttributes['action'] = $formActionUri;
else:
  /*
  url() - Parameters:
  $options (mixed) – If a string is passed it is treated as an Omeka-relative link.
    So, passing ‘items’ would create a link to the items page.
    If an array is passed (or no argument given), it is treated as options to
    be passed to Omeka’s routing system. Note that in the Url Helper, if the first
    argument is a string, the second argument, not the third, is the queryParams
  $route (string) – The route to use if an array is passed in the first argument.
  $queryParams (mixed) – A set of query string parameters to append to the URL
  $reset (bool) – Whether Omeka should discard the current route when generating the URL.
  $encode (bool) – Whether the URL should be URL-encoded
  */
  //Sets the form atribute 'action' to browse items
    $formAttributes['action'] = url(array('controller'=>'items', 'action'=>'browse'));
endif;
//goal of the formAttributes - getting the items
$formAttributes['method'] = 'GET';

?>

<?php
$db = get_db(); //Creates an object to query  Omeka_Db
$pagesTable = $db->getTable('ExhibitPage'); //Gets the table
$exhibitsTable = $db->getTable('Exhibit');
$exhibit_array = $exhibitsTable->fetchObjects('SELECT * FROM omeka_exhibits');
$html = "";

foreach ($exhibit_array as $exhibit_object){
  $exhibit_id = $exhibit_object->getProperty('id');
  $exhibit_pages = $pagesTable->fetchObjects("SELECT * FROM omeka_exhibit_pages WHERE exhibit_id = '$exhibit_id'");
  foreach ($exhibit_pages as $page_called){
    //load the page data
    $id = $page_called->getProperty('id');
    $title = $page_called->getProperty('title');
    $pageHtml = '<li class="page" id="page_' . $id . '">'
            . '<button type="button" class="select-item" id="apply-attachment" formaction="'
            . url(array('module' => 'exhibit-builder',
                'controller' => 'items', 'action' => 'browse'),
                'default', array(), true) . '">' . $title . '</button>';
/*
$pageHtml = '<li class="page" id="page_' . $id . '">'
        . '<div class="sortable-item">'
        //. '<a href="../edit-page/' . $id . '">' . $title . '</a>'
        . '<a href="' . url(array('module' => 'exhibit-builder',
            'controller' => 'items', 'action' => 'browse'),
            'default', array(), true) . '">' . $title . '</a>'
        . '<a class="delete-toggle delete-element" href="#">' . __('Delete') . '</a>'
        . '</div>';
*/

    $html .= $pageHtml;
  }
}
$html .= '</ul>';
echo $html;
//END OF CUSTOMIZATION!!
?>

<?php //The search form menu - allows you to search by words etc. ?>
<form <?php echo tag_attributes($formAttributes); ?>>

</form>

<?php // Activates the "Show Search Form button" in the item form menu
