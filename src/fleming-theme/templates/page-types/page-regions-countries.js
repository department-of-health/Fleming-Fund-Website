var currentActiveRegionCardID = 'all';
function setActiveRegionCard(cardID) {
    if (currentActiveRegionCardID !== cardID) {
        Fleming.map.focusMapOn(cardID);
        $('#region-cards').children('.card').each(function (i, card) {
            card = $(card);
            if (card.attr('id') === cardID) {
                card.removeClass('inactive');
                card.focus();
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

    Fleming.map.setRightBound(regionCardsContainer.offset().left);

    if (mapElement.offset().left < regionCardsContainer.offset().left) {

        var newMapElementClass = '';

        var topOfViewport = $(window).scrollTop();
        var topOfMapElementContainer = mapElement.parent().offset().top;

        if (topOfMapElementContainer - topOfViewport > 0) {
            newMapElementClass = 'top';
        } else {
            var mapElementContainerHeight = mapElement.parent().height();
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

function init() {
    $('#map-element')
        .show()
        .after(
            $('#region-cards')
                .removeClass('two-max')
                .addClass('cover four columns')
                .wrap('<div class="container" style="height: 1px; overflow: visible;"></div>')
                .before(
                    $('<div class="eight columns">&nbsp;</div>')
                )
                .parent()
        );

    $('h1').siblings('.card-container.link-collection').first().click(function(e) {
        e.preventDefault();
        var cardIdToJumpTo = e.target.href.split('#')[1];
        if (cardIdToJumpTo) {
            var cardToJumpTo = $('#' + cardIdToJumpTo);
            if (cardToJumpTo) {
                var positionOfCardWithinDocument = cardToJumpTo.offset().top;
                var heightOfViewport = $(window).height();
                var heightOfCard = cardToJumpTo.outerHeight(false);
                var topOfViewportShouldBeAt = positionOfCardWithinDocument - heightOfViewport/2 + heightOfCard/2;
                window.scrollTo(0, topOfViewportShouldBeAt);
            }
        }
    });

    $(window).scroll(handleScrollForMap);
    var updateMapAfterResizeTimeout;
    $(window).resize(function () {
        clearTimeout(updateMapAfterResizeTimeout);
        updateMapAfterResizeTimeout = setTimeout(handleScrollForMap, 50);
    });

    handleScrollForMap();
}

module.exports = {
    init,
};
