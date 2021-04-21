console.log('Ma base javascript');

function autoPlayYouTubeModal(){
    let trigger = $("body").find('[data-bs-toggle="modal"]');
    trigger.click(function() {
        let theModal = $(this).data("target"),
            videoSRC = $(this).attr("data-video"),
            videoSRCauto = videoSRC + "?autoplay=1";
        $(theModal+' iframe').attr('src', videoSRCauto);
        $(theModal+' button.close').click(function () {
            $(theModal+' iframe').attr('src', videoSRC);
        });
    });
}

$(document).ready(function(){
    autoPlayYouTubeModal();
});