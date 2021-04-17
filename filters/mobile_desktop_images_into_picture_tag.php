function mobile_desktop_images_into_picture_tag($content){
    $dom = new \DOMDocument();
    $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
    $xpath = new \DOMXPath($dom);
    $desktop_images = $xpath->query('//img[contains(@class,"img-desktop")]');
    $mobile_images = $xpath->query('//img[contains(@class,"img-mobile")]');
    $mobile_size = '(max-width:640px)';
    // putting img-desktop and img-mobile into new picture tag
    foreach($mobile_images as $index => $mobile_image){
        $picture_elemnt = $dom->createElement('picture');
        $desktop_images->item($index)->parentNode->insertBefore($picture_elemnt,$desktop_images->item($index));
        $source_elemnt = $dom->createElement('source');
        $picture_elemnt->appendChild($source_elemnt);
        $picture_elemnt->appendChild($desktop_images->item($index));
        $source_elemnt->setAttribute('srcset', $mobile_image->getAttribute('src'));
        $source_elemnt->setAttribute('media',  $mobile_size);
    }
    // removing mobile images duplication
    $length = $mobile_images->length;
    for ($i=0; $i < $length; $i++) { 
        $mobile_images->item($i)->parentNode->parentNode->removeChild($mobile_images->item($i)->parentNode);
    }

    return $dom->saveHTML();
}
add_filter( 'the_content', 'App\Theme\Setup\mobile_desktop_images_into_picture_tag');
