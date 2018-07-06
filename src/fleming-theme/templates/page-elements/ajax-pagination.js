var maxPages = 0;
var lastLoadedPage = 1;

function loadMore(loadMoreButton) {
    loadMoreButton = $(loadMoreButton);
    var pageToLoad = lastLoadedPage + 1;

    loadMoreButton.prop('disabled', true);

    var currentQueryString = window.location.href.indexOf('?') > -1
    ? '&' + window.location.href.slice(window.location.href.indexOf('?') + 1) : '';

    $.ajax({
        url: "page/" + pageToLoad + "?ajax" + currentQueryString
    }).done(function (data) {
        loadMoreButton.parent().parent().children('.card-container').last().after(data);
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
            .css('border-top', 'none')
            .append(
                $('<button class="load-more" onclick="Fleming.ajaxPagination.loadMore(this)">Load more</button>')
            );
    }
}

module.exports = {
    init,
    loadMore
};
