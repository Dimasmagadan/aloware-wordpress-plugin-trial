#!/bin/bash

# Add official plugins
plugins=(advanced-custom-fields akismet classic-editor contact-form-7 contact-form-7-honeypot cyr2lat dorzki-notifications-to-slack easy-utm-tracking-with-contact-form-7 google-sitemap-plugin polylang post-types-order query-monitor redirection rewrite-rules-inspector rocket-lazy-load smtp-yandex-mail-server string-locator svg-support theme-editor)
for i in "${plugins[@]}"
do
  :
  wp plugin install $i --allow-root --activate
done

# Add vendor plugins
vendor_plugins=(advanced-custom-fields-pro.zip roistat-integration.zip cf7-storage.zip)
for i in "${vendor_plugins[@]}"
do
  :
  wp plugin install /var/www/html/vendor-plugins/$i --allow-root --activate
done
