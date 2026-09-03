<?php
/***
*
*Found actions: 2
*Found functions:2
*Extracted functions:2
*Total parameter names extracted: 2
*Overview: {'recordPluginsSearchTerms': {'search-install-plugins'}, 'recordThemesSearchTerms': {'query-themes'}}
*
***/

/** Function recordPluginsSearchTerms() called by wp_ajax hooks: {'search-install-plugins'} **/
/** Parameters found in function recordPluginsSearchTerms(): {"post": ["s"]} **/
function recordPluginsSearchTerms()
    {
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.NonceVerification.Missing
        $searchTerm = isset($_POST['s']) ? \sanitize_text_field(\wp_unslash(urldecode($_POST['s']))) : '';
        if (empty($searchTerm)) {
            return;
        }

        $searchTerms = \get_option('extendify_plugin_search_terms', []);
        $searchTerms[] = $searchTerm;
        $searchTerms = array_unique($searchTerms);

        \update_option('extendify_plugin_search_terms', $searchTerms);
    }


/** Function recordThemesSearchTerms() called by wp_ajax hooks: {'query-themes'} **/
/** Parameters found in function recordThemesSearchTerms(): {"post": ["request"]} **/
function recordThemesSearchTerms()
    {
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.NonceVerification.Missing
        $searchTerm = \sanitize_text_field(\wp_unslash(urldecode(($_POST['request']['search'] ?? ''))));
        if (empty($searchTerm)) {
            return;
        }

        $searchTerms = \get_option('extendify_theme_search_terms', []);
        $searchTerms[] = $searchTerm;
        $searchTerms = array_unique($searchTerms);

        \update_option('extendify_theme_search_terms', $searchTerms);
    }


