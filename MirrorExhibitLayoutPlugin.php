<?php

/**
 * @package  Omeka Lightbox
 * @copyright Copyright 2015 Ken Albers
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

//extends Omeka_Plugin_AbstractPlugin because it is a plugin
class MirrorExhibitLayoutPlugin extends Omeka_Plugin_AbstractPlugin
{
  // a corresponding key in $_hooks and $_filters will be interpreted as the name of the callback method.
  //Exhibit Builder provides a filter named exhibit_layouts.
  //To add a new layout, you must hook into this filter and add some information about your new layout.
  //You need to decide three things: ID, name, and description
    protected $_filters = array('exhibit_layouts');

    public function filterExhibitLayouts($layouts)
    {
        $layouts['mirror'] = array( //ID: 'mirror'
            'name' => 'Mirror Layout', //name: 'Mirror Layout'
            'description' => 'A mirror layout.' //description
        );
        return $layouts;
    }

    public function mirrorExhibitAttachment($attachment)
    {
      //attachment objects: items the user has attached to this block
        $item = $attachment->getItem();
        $file = $attachment->getFile();

        //if there is a file
        if ($file) {
            //if there is not alternate text, then use the Dublin Core's Title
            if (!isset($fileOptions['imgAttributes']['alt'])) {
                $fileOptions['imgAttributes']['alt'] = metadata($item, array('Dublin Core', 'Title'), array('no_escape' => true));
            }
            //if the file is an image or is to be treated as an image (forceImage == true)
            if ($forceImage) {
              //if imageSize is set, use $fileOptions['imageSize'], else default to 'square_thumbnail'
                $imageSize = isset($fileOptions['imageSize'])
                    ? $fileOptions['imageSize']
                    : 'square_thumbnail';
              //file_image() - Get a customized file image tag.
                $image = file_image($imageSize, $fileOptions['imgAttributes'], $file);
              //returns HTML data for the exhibit item
                $html = exhibit_builder_link_to_exhibit_item($image, $linkProps, $item);
            //if the file is NOT an image, then get the HTML using file_markup
            } else {
                if (!isset($fileOptions['linkAttributes']['href'])) {
                  /* uri: In IT, a Uniform Resource Identifier (URI) is a string of characters
                  used to identify a resource*/
                  //Retrieve the hyper link for the item
                    $fileOptions['linkAttributes']['href'] = exhibit_builder_exhibit_item_uri($item);
                }
                $html = file_markup($file, $fileOptions, null);
            }
        //if a file is not availabe, but an item is, retrieve the HTML for that item
        } else if($item) {
            $html = exhibit_builder_link_to_exhibit_item(null, $linkProps, $item);
        }
        // *Don't show a caption if we couldn't show the Item or File at all*
        if (isset($html)) {
          //if the caption is not a string or the caption is empty, return an empty string for the HTML code
            if (!is_string($attachment['caption']) || $attachment['caption'] == '') {
                return '';
            }
            //.= is the concatenating assignment operator (same as text = text . newtext)
            //add to HTML the code for the caption in a new div
            $html .= '<div class="exhibit-item-caption">'
                  . $attachment['caption']
                  . '</div>';
          /*
          => is the separator for associative arrays. In this case, a new array is constructed
          with 'attachment' being the key for whatever $attachment equals
          Applies the 'exhibit_attachment_caption' filter to $html with {'attachment' => $attachment}
          as the additional argument to pass the filter.
          */
            return apply_filters('exhibit_attachment_caption', $html, array(
                'attachment' => $attachment
            ));
        //if the HTML is not set, set it equal to ''
        } else {
            $html = '';
        }
        //compact() creates an array from variables and their values
        //the filter 'exhibit_attachment_markup' is applied to $html using
        //the array compact() creates
        return apply_filters('exhibit_attachment_markup', $html,
            compact('attachment', 'fileOptions', 'linkProps', 'forceImage')
        );
    }
}
