if [ ! -d bin ]; then
    mkdir bin
fi

if [ ! -f bin/wp2md ]; then
    wget https://github.com/wpreadme2markdown/wp-readme-to-markdown/releases/download/2.0.2/wp2md.phar -O bin/wp2md
    chmod a+x bin/wp2md
fi

./bin/wp2md convert < readme.txt > README.md

if [ ! -d /tmp/wp-build/tools/i18n/ ]; then
    svn co http://develop.svn.wordpress.org/trunk/ /tmp/wp-build/
fi

if [ ! -d languages ]; then
    mkdir languages
fi

php /tmp/wp-build/tools/i18n/makepot.php wp-plugin . languages/vendi-wp-markdown.pot
