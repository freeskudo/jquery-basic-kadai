
$(function(){
    $(window).on('scroll',function(){
        console.log('スクロール')
    });
});


$(function(){
    $(window).on('load',function(){
        $(this).load()
    });
    console.log('ロードしました')
});