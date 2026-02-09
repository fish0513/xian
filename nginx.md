location = /cms { return 301 /cms/; }
location = /food { return 301 /food/; }
location = /travel { return 301 /travel/; }

location /cms/ {
try_files $uri $uri/ /cms/index.php?$args;
}

location ^~ /food/assets/ {
expires 7d;
add_header Cache-Control "public, immutable";
try_files $uri =404;
}

location ^~ /food/ {
try_files $uri $uri/ /food/index.html;
}

location ^~ /travel/assets/ {
expires 7d;
add_header Cache-Control "public, immutable";
try_files $uri =404;
}

location ^~ /travel/ {
try_files $uri $uri/ /travel/index.html;
}
