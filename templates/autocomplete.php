<script type="text/html" id="tmpl-autocomplete-header">
    <#
    var typeClass = "tag";
    if(data.label == "Produkter") {
        var typeClass = "product";
    }
    #>
<div class="autocomplete-header {{{ typeClass }}}">

    <#
    var gridlist = "";
    jQuery(data.wrapperClass).addClass(data.view);
    <!-- console.log(data.postType); -->
    if (data.postType === "product") {
        if (data.view=='grid') {
            jQuery("body").addClass("algoliasearch-grid");
        } else {
            jQuery("body").removeClass("algoliasearch-grid");
        }
    }
    if(data.postType === "product" && data.count != 0) {
        gridlist = '<div class="wrapper-toggle-view-buttons"><div class="button-list-view ' + ((data.view=='list') ? 'active' : '') + '"><img class="active" src="/wp-content/plugins/algolia/assets/img/list-icon-active.svg"><img class="not-active" src="/wp-content/plugins/algolia/assets/img/list-icon-not-active.svg"></div><div class="button-grid-view ' + ((data.view=='grid') ? 'active' : '') + '"><img class="active" src="/wp-content/plugins/algolia/assets/img/grid-icon-active.svg"><img class="not-active" src="/wp-content/plugins/algolia/assets/img/grid-icon-not-active.svg"></div></div>';
    }
#>
{{{ gridlist }}}
<div><h2 class="autocomplete-header-title {{{ typeClass }}}" data-more-url="{{{ data.moreUrl }}}"><?php esc_html_e( 'Results for', 'driv_algolia' ); ?> <span class="title-element">{{{ data.label }}}</span> <span class="thinner">{{{ data.count }}}<span></h2></div>
<div class="clear"></div>
</div>
</script>

<script type="text/html" id="tmpl-autocomplete-post-suggestion">
    <a class="suggestion-link {{ data.post_type }}" href="{{ data.permalink }}" title="{{ data.post_title }}">
        <# if ( typeof data.images !== 'undefined' && typeof data.images.thumbnail !== 'undefined' && typeof data.images.thumbnail.url !== 'undefined' ) { #>
        <img class="suggestion-post-thumbnail" src="{{ data.images.thumbnail.url }}" alt="{{ data.post_title }}">
        <# } #>
        <div class="suggestion-post-attributes">
            <h2 class="suggestion-post-title suggestion-product-title">{{{ data._highlightResult.post_title.value }}}</h2>

            <#
            var attributes = ['content', 'title6', 'title5', 'title4', 'title3', 'title2', 'title1'];
            var attribute_name;
            var relevant_content = '';
            var price_content = '';
            for ( var index in attributes ) {
                attribute_name = attributes[ index ];
                if ( data._highlightResult[ attribute_name ].matchedWords.length > 0 ) {
                    relevant_content = data._snippetResult[ attribute_name ].value;
                    break;
                } else if( data._snippetResult[ attribute_name ].value !== '' ) {
                    relevant_content = data._snippetResult[ attribute_name ].value;
                    }
                }
                if(data.post_type == "product") {
                price_content = '<p class="suggestion-post-price">' + data.price2 + '</p>';
            }
            #>
            <p class="suggestion-post-content">{{{ relevant_content }}}</p>
            {{{ price_content }}}
        </div>
    </a>
</script>

<script type="text/html" id="tmpl-autocomplete-more">
    <div class="autocomplete-more">****
        <#
        var gridlist = "";
        if(data.label == "Produkter") {
        gridlist = '<div class="wrapper-toggle-view-buttons"><div class="button-list-view active"><img class="active" src="/wp-content/plugins/algolia/assets/img/list-icon-active.svg"><img class="not-active" src="/wp-content/plugins/algolia/assets/img/list-icon-not-active.svg"></div><div class="button-grid-view"><img class="active" src="/wp-content/plugins/algolia/assets/img/grid-icon-active.svg"><img class="not-active" src="/wp-content/plugins/algolia/assets/img/grid-icon-not-active.svg"></div></div>';
    }
    #>
    {{{ gridlist }}}
    <div class="clear"></div>
</div>
</script>


<script type="text/html" id="tmpl-autocomplete-term-suggestion">
    <div class="wrapper-suggestion-link {{{ data.taxonomy }}}">
        <a class="suggestion-link" href="{{ data.permalink }}"  title="{{ data.name }}">
            <p class="suggestion-post-title suggestion-tag-title">{{{ data._highlightResult.name.value }}}</p>
        </a>
    </div>
</script>

<script type="text/html" id="tmpl-autocomplete-user-suggestion">
    <a class="suggestion-link user-suggestion-link" href="{{ data.posts_url }}"  title="{{ data.display_name }}">
        <# if ( data.avatar_url ) { #>
        <img class="suggestion-user-thumbnail" src="{{ data.avatar_url }}" alt="{{ data.display_name }}">
        <# } #>

        <span class="suggestion-post-title">{{{ data._highlightResult.display_name.value }}}</span>
    </a>
</script>

<script type="text/html" id="tmpl-autocomplete-footer">
    <div class="autocomplete-footer">
        <div class="autocomplete-footer-branding">
            <?php esc_html_e( 'Powered by', 'algolia' ); ?>
            <a href="#" class="algolia-powered-by-link" title="Algolia">
                <img class="algolia-logo" src="https://www.algolia.com/assets/algolia128x40.png" alt="Algolia" />
            </a>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-autocomplete-empty">
    <div class="autocomplete-empty">
        <?php esc_html_e( 'No results matched your query ', 'algolia' ); ?>
        <span class="empty-query">{{ data.query }}"</span>
    </div>
</script>

<script type="text/html" id="tmpl-autocomplete-empty-product">
    <div class="autocomplete-empty-product">
        <div class="panel search borders">
            <div class="row collapse">
                <div class="columns small-12">
                    <h2><?php esc_html_e( 'No Products Found', 'driv_algolia' ); ?></h2>
                    <p><?php esc_html_e( 'Try to edit your search term to find what you are looking for.', 'driv_algolia' ); ?></p>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/javascript">
    jQuery(function () {
        /* init Algolia client */
        var client = algoliasearch(algolia.application_id, algolia.search_api_key);

        <?php drivAlgolia::driv_algolia_settings_js_var(); ?>

        var defaultAlgoliaView = (typeof drivAlgoliaSettings['posts_product'] !== 'undefined') ? drivAlgoliaSettings['posts_product'].view : 'list';
        var toggleDefaultAlgoliaView = function() {
            defaultAlgoliaView = (defaultAlgoliaView=='grid') ? 'list' : 'grid';
        }
        var getDefaultAlgoliaSuggestionView = function(indexId) {
            return ((indexId=='posts_product') ? defaultAlgoliaView : ((typeof drivAlgoliaSettings[indexId] !== 'undefined') ? drivAlgoliaSettings[indexId].view : 'list'));
        }
        var getDefaultAlgoliaSuggestionTemplateName = function(args) {
            return ((typeof args.hits[0].post_type !== 'undefined') ? 'autocomplete-post-suggestion' : 'autocomplete-term-suggestion');
        }

        /* setup default sources */
        var sources = [];
        jQuery.each(algolia.autocomplete.sources, function(i, config) {
            sources.push({
                source: autocomplete.sources.hits(client.initIndex(config['index_name']), {
                    hitsPerPage: config['max_suggestions'],
                    attributesToSnippet: [
                        'content:10',
                        'title1:10',
                        'title2:10',
                        'title3:10',
                        'title4:10',
                        'title5:10',
                        'title6:10'
                    ]
                }),
                templates: {
                    header: function(query, args) {
                        if (args.nbHits==0 && (typeof drivAlgoliaSettings[config['index_id']] === 'undefined' || drivAlgoliaSettings[config['index_id']].empty_view!=='product')) return;
                        type = config['index_id'].replace('posts_', '').replace('terms_', '');
                        moreUrl = (parseInt(args.nbHits) > parseInt(args.hitsPerPage)) ? '/?s=' + query.query + '&post_types=' + type : '';
                        view = getDefaultAlgoliaSuggestionView(config['index_id']);
                        postType = (args.nbHits>0 && typeof args.hits[0].post_type !== 'undefined') ? args.hits[0].post_type : false;
                        return wp.template('autocomplete-header')({
                            label: config['label'],
                            count: args.nbHits,
                            moreUrl: moreUrl,
                            view: view,
                            postType: postType,
                            wrapperClass: '.aa-dataset-' + i,
                        });
                    },
                    suggestion: wp.template(config['tmpl_suggestion']),
                    more: wp.template(config['tmpl_more']),
                    empty: function(query, args) {
                        if (typeof drivAlgoliaSettings[config['index_id']] === 'undefined' || drivAlgoliaSettings[config['index_id']].empty_view!=='product') return;
                        return wp.template('autocomplete-empty-product')({
                            query: args.query,
                        });
                    },
                }
            });
        });

        /* Setup dropdown menus */
        jQuery("input[name='s']:not('.no-autocomplete')").each(function(i) {
            var $searchInput = jQuery(this);


            var config = {
                debug: algolia.debug,
                hint: false,
                openOnFocus: true,
                templates: {}
            };
            /* Todo: Add empty template when we fixed https://github.com/algolia/autocomplete.js/issues/109 */

            if(algolia.powered_by_enabled) {
                //config.templates.footer = wp.template('autocomplete-footer');
            }

            //config.templates.more = wp.template('autocomplete-more');

            /* Instantiate autocomplete.js */
            autocomplete($searchInput[0], config, sources)
            .on('autocomplete:selected', function(e, suggestion, datasetName) {
                /* Redirect the user when we detect a suggestion selection. */
                window.location.href = suggestion.permalink;
            });

            var $autocomplete = $searchInput.parent();

            /* Remove autocomplete.js default inline input search styles. */
            $autocomplete.removeAttr('style');

            /* Configure tether */
            var $menu = $autocomplete.find('.aa-dropdown-menu');

            var config = {
                element: $menu,
                target: this,
                attachment: 'top left',
                targetAttachment: 'bottom left',
                offset: '-25px 0px',
            };

            /* This will make sure the dropdown is no longer part of the same container as */
            /* the search input container. */
            /* It ensures styles are not overridden and limits theme breaking. */
            var tether = new Tether(config);
            tether.on('update', function(item) {
                /* todo: fix the inverse of this: https://github.com/HubSpot/tether/issues/182 */
                if (item.attachment.left == 'right' && item.attachment.top == 'top' && item.targetAttachment.left == 'left' && item.targetAttachment.top == 'bottom') {
                    config.attachment = 'top right';
                    config.targetAttachment = 'bottom right';

                    tether.setOptions(config, false);
                }
            });

            $searchInput.on('autocomplete:updated', function() {
                tether.position();
                jQuery('.searchsubmit').addClass('closeable');
            });
            $searchInput.on('autocomplete:empty', function() {
                tether.position();
            });
            $searchInput.on('autocomplete:opened', function() {
                updateDropdownWidth();
            });
            $searchInput.on('autocomplete:closed', function() {
                jQuery('.searchsubmit').removeClass('closeable');
            });

            jQuery(document)
            .on('click', '.searchsubmit.closeable', function(e) {
                e.preventDefault();
                jQuery('.searchsubmit').removeClass('closeable');
                $menu.css('display', 'none');
            });

            /* Trick to ensure the autocomplete is always above all. */
            $menu.css('z-index', '99999');

            /* Makes dropdown match the input size. */
            var dropdownMinWidth = 200;
            function updateDropdownWidth() {
                var inputWidth = $searchInput.outerWidth();
                if (inputWidth >= dropdownMinWidth) {
                    $menu.css('width', $searchInput.outerWidth());
                } else {
                    $menu.css('width', dropdownMinWidth);
                }
                tether.position();
            }
            jQuery(window).on('resize', updateDropdownWidth);

        });

        /* This ensures that when the dropdown overflows the window, Thether can reposition it. */
        jQuery('body').css('overflow-x', 'hidden');

        jQuery(document).on("click", ".button-list-view", function() {
            jQuery(this).toggleClass("active");
            jQuery(".button-grid-view").toggleClass("active");
            jQuery('.aa-dropdown-menu-inner > [class^="aa-dataset"]').toggleClass("grid");
            jQuery("body").toggleClass("algoliasearch-grid");
            toggleDefaultAlgoliaView();
        });
        jQuery(document).on("click", ".button-grid-view", function() {
            jQuery(this).toggleClass("active");
            jQuery(".button-list-view").toggleClass("active");
            jQuery('.aa-dropdown-menu-inner > [class^="aa-dataset"]').toggleClass("grid");
            jQuery("body").toggleClass("algoliasearch-grid");
            toggleDefaultAlgoliaView();
        });

        jQuery(document).on("click", ".algolia-powered-by-link", function(e) {
            e.preventDefault();
            window.location = "https://www.algolia.com/?utm_source=WordPress&utm_medium=extension&utm_content=" + window.location.hostname + "&utm_campaign=poweredby";
        });

        /* Find top position of element next after header. */
        var p = jQuery( ".header:first" );
        var position = p.nextAll("div").position();
        jQuery(".aa-dropdown-menu").css("top",position.top);
        //console.log("bottom: " + position.top + p.nextAll("div").attr("class"));
        jQuery( "p:last" ).text( "left: " + position.left + ", top: " + position.top );
        jQuery(".aa-dropdown-menu").each(function() {
            jQuery(this).find("div").not(":first").wrapAll('<div class="r-wrapper"></div>');
        });

        // Find the more button on change of dropdown
        jQuery(".aa-dataset-0").on('rendered', function() {
            jQuery(".algoli-more").html('<a class="button-more-products" href="#"><?php _e( 'See more products', 'algoliasearch' ); ?> &#187;</a>');
        });

        /* Make dropdown-menu an overlay */
        //jQuery( ".aa-dropdown-menu" ).css( "transform","translateY(" + position.top + "px) !important" );
        jQuery( ".aa-dropdown-menu" ).wrapInner( "<div class='aa-dropdown-menu-inner'></div>" );
    });
</script>
