jQuery.each(param_obj, function (index, value) {
    if (!isNaN(value)) {
        param_obj[index] = parseInt(value);
    }
});
function Gallery_Img_Blog_Style_Gallery(id) {
    var _this = this;
    _this.body = jQuery('body');
    _this.container = jQuery('#' + id + '.view-blog-style-gallery');
    _this.content = _this.container.parent();
    _this.ratingType = _this.content.data('rating-type');
    _this.likeContent = jQuery('.huge_it_gallery_like_cont');
    _this.likeCountContainer = jQuery('.huge_it_like_count');
    _this.loadMoreBtn = _this.content.find('.load_more_button');
    _this.loadingIcon = _this.content.find('.loading');
    _this.documentReady = function () {
        ratingCountsOptimize(_this.container,_this.ratingType);
    };
    _this.addEventListeners = function () {
        _this.loadMoreBtn.on('click', _this.loadMoreBtnClick);
    };
    _this.loadMoreBtnClick = function () {
        if (parseInt(_this.content.find(".pagenum:last").val()) < parseInt(_this.container.find("#total").val())) {
            var pagenum = parseInt(_this.content.find(".pagenum:last").val()) + 1;
            var perpage = gallery_obj[0].content_per_page;
            var galleryid = gallery_obj[0].id;
            var pID = postID;
            var likeStyle = _this.ratingType;
            var ratingCount = param_obj.ht_lightbox_rating_count;
            _this.getResult(pagenum, perpage, galleryid, pID, likeStyle, ratingCount);
        } else {
            _this.loadMoreBtn.hide();
        }
        return false;
    };
    _this.getResult = function (pagenum, perpage, galleryid, pID, likeStyle, ratingCount) {
        var data = {
            action: "huge_it_gallery_ajax",
            task: 'load_blog_view',
            page: pagenum,
            perpage: perpage,
            galleryid: galleryid,
            pID: pID,
            likeStyle: likeStyle,
            ratingCount: ratingCount
        };
        _this.loadingIcon.show();
        _this.loadMoreBtn.hide();
        jQuery.post(adminUrl, data, function (response) {
                if (response.success) {
                    var $objnewitems = jQuery(response.success);
                    _this.container.append($objnewitems);
                    setTimeout(function(){
                    },100);
                    _this.loadMoreBtn.show();
                    _this.loadingIcon.hide();
                    if (_this.content.find(".pagenum:last").val() == _this.content.find("#total").val()) {
                        _this.loadMoreBtn.hide();
                    }
                    ratingCountsOptimize(_this.container,_this.ratingType);
                } else {
                    alert("no");
                }
            }
            , "json");
    };
    _this.init = function () {
        _this.documentReady();
        _this.addEventListeners();
    };

    this.init();
}
var galleries = [];
jQuery(document).ready(function () {
    jQuery(".video_view9_cont_list.view-blog-style-gallery").each(function (i) {
        var id = jQuery(this).attr('id');
        galleries[i] = new Gallery_Img_Blog_Style_Gallery(id);
    });
});

