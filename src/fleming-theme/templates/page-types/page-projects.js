var maxPages = 0;
var lastLoadedPage = 1;

function loadMore(loadMoreButton) {
    loadMoreButton = $(loadMoreButton);
    var pageToLoad = lastLoadedPage + 1;

    loadMoreButton.prop('disabled', true);

    $.ajax({
        url: "?ajaxLoadPage&paged=" + pageToLoad
    }).done(function (data) {
        loadMoreButton.parent().parent().children('.card-container').append(data);
        lastLoadedPage = pageToLoad;
        if (lastLoadedPage >= maxPages) {
            loadMoreButton.hide();
        } else {
            loadMoreButton.prop('disabled', false);
        }
    }).fail(function() {
        loadMoreButton.prop('disabled', false);
    });
}

function init(pageLimit) {
    maxPages = pageLimit;
    if (maxPages > 1) {
        $('.pagination-links')
            .html('')
            .append(
                $('<button class="load-more" onclick="Fleming.projects.loadMore(this)">Load more</button>')
            );
    }
}

module.exports = {
    init,
    loadMore
};
