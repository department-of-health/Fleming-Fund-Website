var searchHidden = true;
var searchIcon;
var closeIcon =
    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">' +
    '<path d="M2 0 L 16 14 L 30 0 L 32 2 L 18 16 L 32 30 L 30 32 L 16 18 L 2 32 L 0 30 L 14 16 L 0 2 Z"/>' +
    '</svg>';

function searchToggleButtonClicked() {
    if (searchHidden) {
        $('.mobile-toggle-search button')
            .attr('aria-label', 'hide search')
            .html(closeIcon);
        $('.search-container')
            .removeClass('mobile-hidden')
            .find('input')
            .focus();
    } else {
        $('.mobile-toggle-search button').attr('aria-label', 'show search').html(searchIcon);
        $('.search-container').addClass('mobile-hidden');
    }
    searchHidden = !searchHidden;
}

function init() {
    searchIcon = $('.search-container button.square-icon').html();
    $('.logo-container').after($(
        '<div class="mobile-toggle-search">' +
        '<button class="square-icon" aria-label="show search" onclick="Fleming.header.searchToggleButtonClicked();">' +
        searchIcon +
        '</button>' +
        '</div>'
    ));
    $('.search-container').addClass('mobile-hidden');
}

module.exports = {
    init,
    searchToggleButtonClicked
};