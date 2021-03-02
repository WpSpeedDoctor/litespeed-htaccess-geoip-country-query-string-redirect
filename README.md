# Add in .htaccess query string to the page URL based on visitor's country

Here is an example snippet, it will be triggered for users NOT from Singapore and Japan:
```
### add USD  query string ###
<IfModule LiteSpeed>
RewriteEngine on
RewriteCond %{QUERY_STRING} !^(.*)wmc-currency(.*)$
RewriteCond %{ENV:GEOIP_COUNTRY_CODE} !^(SG|JP)$

## not redirect google bot
RewriteCond %{HTTP_USER_AGENT} !^(.*)Google(.*)

##only for pages containing word 'product' and 'store'
RewriteCond %{REQUEST_URI} (product) [NC,OR]
RewriteCond %{REQUEST_URI} (store) [NC]
RewriteRule ^(.*)$ https://yourdomain.com/$1?wmc-currency=USD [L,NE,R=301]
</IfModule>
```
Check with your hosting if this functionality is activated.

I hope it will help.
