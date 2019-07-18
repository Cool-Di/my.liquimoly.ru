$(function () {

    function getRandomizer(bottom, top) {
        return Math.floor(Math.random() * ( 1 + top - bottom )) + bottom;
    }

    function add_item() {
        var i_arr = [
            '<div style="top: 20px; left: 0;" class="item"><img src="/images/planogram/135b9c3b-9a7a-11e5-83f7-0015175582ed.png" height="80" border="0"></div>',
            '<div style="top: 40px; left: 0;" class="item"><img src="/images/planogram/bb9e10a8-655e-11da-acfb-0040f46c7325.png" height="60" border="0"></div>',
            '<div style="top: 0; left: 0;" class="item"><img src="/images/planogram/33981863-a302-11da-babc-505054503030.png" height="100" border="0"></div>'
        ];

        $('#p_top').append(i_arr[getRandomizer(0, 2)]);
        var item = $(".item");
        item.draggable({containment: ".stelaj", snap: ".polka", snapTolerance: 5});
        item.dblclick(function () {
            $(this).detach();
        })
    }

    var item = $(".item");
    item.draggable({containment: ".stelaj", snap: ".polka", snapTolerance: 5});
    item.dblclick(function () {
        $(this).detach();
    });
    $('#add_item').click(function(){add_item();});
});