function updateMapDimensions() {
    var mapElement = $('#map-element');
    mapElement.css({
        'width': mapElement.parent().width() + 'px'
    });
    Fleming.map.refocusMap();
}

var currentActiveRegionCardID = 'all';
function setActiveRegionCard(cardID) {
    if (currentActiveRegionCardID !== cardID) {
        Fleming.map.focusMapOn(cardID);
        $('#region-cards').children('.card').each(function (i, card) {
            card = $(card);
            if (card.attr('id') === cardID) {
                card.removeClass('inactive');
            } else {
                card.addClass('inactive');
            }
        });
        currentActiveRegionCardID = cardID;
    }
}

function handleScrollForMap() {
    var mapElement = $('#map-element');
    var regionCardsContainer = $('#region-cards');

    if (mapElement.offset().left < regionCardsContainer.offset().left) {

        var newMapElementClass = '';

        var topOfViewport = $(window).scrollTop();
        var topOfMapElementContainer = mapElement.parent().parent().offset().top;

        if (topOfMapElementContainer - topOfViewport > 0) {
            newMapElementClass = 'top';
        } else {
            var mapElementContainerHeight = mapElement.parent().parent().height();
            var spaceAvailableForMapElement =
                topOfMapElementContainer + mapElementContainerHeight - topOfViewport;
            if (spaceAvailableForMapElement > mapElement.height()) {
                newMapElementClass = 'fixed';
            } else {
                newMapElementClass = 'bottom';
            }
        }
        mapElement.removeClass();
        mapElement.addClass(newMapElementClass);

        var activeCardID = 'all';
        regionCardsContainer.children('.card').each(function (i, card) {
            card = $(card);
            var positionInViewport = card.offset().top - topOfViewport;
            if (positionInViewport - $(window).height() / 2 < 0) {
                activeCardID = card.attr('id');
            }
        });
        setActiveRegionCard(activeCardID);
    } else {
        setActiveRegionCard('all');
    }
}

function handleResizeForMap() {
    updateMapDimensions();
    handleScrollForMap();
}

function init() {
    $('#map-element')
        .wrap('<div class="container"></div>')
        .wrap('<div class="eight columns"></div>')
        .show()
        .parent()
        .append('&nbsp;')
        .after(
            $('#region-cards')
                .removeClass('two-max')
                .addClass('cover four columns')
        );

    $(window).scroll(handleScrollForMap);
    var updateMapAfterResizeTimeout;
    $(window).resize(function () {
        clearTimeout(updateMapAfterResizeTimeout);
        updateMapAfterResizeTimeout = setTimeout(handleResizeForMap, 50);
    });

    handleResizeForMap();
}

module.exports = {
    init,
};
