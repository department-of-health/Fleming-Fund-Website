var mapConfig;
var highlightedRegion;
var focusedRegion;

var map;

function getCountryFillMap() {
    var fillMap = {};
    $.each(mapConfig.countries, function (code, country) {
        var region = mapConfig.regions[country.region];
        fillMap[code] = region.colourScheme;
    });
    return fillMap;
}

function getCountryFillOpacityMap() {
    var fillOpacityMap = {};
    $.each(mapConfig.countries, function (code, country) {
        fillOpacityMap[code] = (country.isPartner ? '0.5' : '1');
    });
    return fillOpacityMap;
}

function getCountryStyleMap() {
    var styleMap = {};
    $.each(mapConfig.countries, function (code, country) {
        styleMap[code] = 'cursor: pointer';
    });
    return styleMap;
}

function getCountryClassMap() {
    var classMap = {};
    $.each(mapConfig.countries, function (code, country) {
        var region = mapConfig.regions[country.region];
        classMap[code]
            = 'jvectormap-region jvectormap-element '
            + (country.isPartner ? 'partner ' : '')
            + region.colourScheme
            + ((highlightedRegion === 'all') || (highlightedRegion === country.region) ? '' : ' not-highlighted');
    });
    return classMap;
}

function refocusMap() {
    if (map !== undefined && focusedRegion !== undefined) {
        map.updateSize();
        map.setFocus({
            regions: mapConfig.regions[focusedRegion].countries
        });
    }
}

function focusMapOn(region) {
    focusedRegion = region;
    if (mapConfig.regions[region] === undefined || mapConfig.regions[region].countries.length === 0) {
        focusedRegion = 'all';
    } else {
        focusedRegion = region;
    }
    refocusMap();

}

function init(config, mapElementID) {
    mapConfig = config;
    highlightedRegion = config.currentRegion;
    focusedRegion = config.currentRegion;

    $(function () { // initialise map only after the DOM is ready and inline scripts finished
        $('.map-container').show();
        map = $('#' + mapElementID).vectorMap({
            map: 'world_mill',
            backgroundColor: '#e5e5e5',
            regionStyle: {
                initial: {
                    fill: 'white',
                    "fill-opacity": 1,
                    stroke: 'none',
                    "stroke-width": 0,
                    "stroke-opacity": 1
                },
                hover: {
                    cursor: 'normal'
                }
            },
            focusOn: {
                regions: mapConfig.regions[focusedRegion].countries
            },
            series: {
                regions: [
                    {attribute: 'class', values: getCountryClassMap()},
                    {attribute: 'fill', values: getCountryFillMap()},
                    {attribute: 'fill-opacity', values: getCountryFillOpacityMap()},
                    {attribute: 'style', values: getCountryStyleMap()},
                ]
            },
            zoomOnScroll: false,
            panOnDrag: config.interactive,
            zoomMax: 10,
            onRegionTipShow: function (e, tip, code) {
                var country = mapConfig.countries[code];
                if (country) {
                    tip.html(country.name);
                } else {
                    e.preventDefault();
                }
            },
            onRegionClick: function (e, code) {
                var country = mapConfig.countries[code];
                if (country) {
                    window.location.href = country.URL;
                }
            }
        }).vectorMap('get', 'mapObject');

        $('#' + mapElementID + '-zoom-in').click(
            function () {
                map.setScale(map.scale * map.params.zoomStep, map.width / 2, map.height / 2, !1, map.params.zoomAnimate)
            }
        );
        $('#' + mapElementID + '-zoom-out').click(
            function () {
                map.setScale(map.scale / map.params.zoomStep, map.width / 2, map.height / 2, !1, map.params.zoomAnimate)
            }
        );

        $(window).resize(function () {
            refocusMap();
        });

    });
}

module.exports = {
    focusMapOn,
    init,
    refocusMap,
};