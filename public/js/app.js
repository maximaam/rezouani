$(document).ready(function() {
    let $list   = $('.navbar-subcategory'),
        $select = $('<select name="subcat-list" id="subcat-list" size="1" />');

    $list.children('li').each(function(index) {
        let $link = $(this).find('a'),
            url = $link.attr('href'),
            selected = location.pathname === url ? 'selected' : '';
        $select.append($('<option '+selected+'/>').attr('value', url).html($link.text()));
    });

    $list.after($select);

    $select.on('change', function (){
       location.href = $(this).val();
    });
});