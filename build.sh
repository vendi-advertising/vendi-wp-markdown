#!/bin/bash

PLUGIN_FOLDER=vendi-wp-markdown

PLUGIN_DIR=$(pwd)

convert_readme_to_markdown()
{
    BIN_DIR=bin
    WP2MD_URL=https://github.com/wpreadme2markdown/wp-readme-to-markdown/releases/download/2.0.2/wp2md.phar
    WP2MD_COMMAND=./${BIN_DIR}/wp2md

    if [ ! -d ${BIN_DIR} ]; then
        mkdir ${BIN_DIR}
    fi

    if [ ! -f ${WP2MD_COMMAND} ]; then
        printf "Downloading wp2md utility\n"
        wget ${WP2MD_URL} -O ${WP2MD_COMMAND}
        chmod a+x ${WP2MD_COMMAND}
    fi

    printf "Converting readme to markdown\n"
    ${WP2MD_COMMAND} convert < readme.txt > README.md
}

make_translation_file()
{
    LOCAL_WP_BUILD_DIR=`mktemp -d 2>/dev/null || mktemp -d -t 'mytmpdir'`
    LOCAL_WP_BUILD_DIR=${LOCAL_WP_BUILD_DIR}/wp-build

    printf "Checking out WordPress dev tools\n"
    svn co --quiet http://develop.svn.wordpress.org/trunk/ ${LOCAL_WP_BUILD_DIR}

    if [ ! -d languages ]; then
        mkdir languages
    fi

    printf "Creating translation files\n"
    php ${LOCAL_WP_BUILD_DIR}/tools/i18n/makepot.php wp-plugin . languages/${PLUGIN_FOLDER}.pot

    rm -rf ${LOCAL_WP_BUILD_DIR}
}

checkout_plugin_to_temp()
{
    LOCAL_GIT_FOLDER=`mktemp -d 2>/dev/null || mktemp -d -t 'mytmpdir'`
    LOCAL_GIT_FOLDER=${LOCAL_GIT_FOLDER}/${PLUGIN_FOLDER}

    printf "Cloning plugin repo\n"
    git clone --quiet git@github.com:vendi-advertising/${PLUGIN_FOLDER}.git ${LOCAL_GIT_FOLDER}

    cd ${LOCAL_GIT_FOLDER}

    printf "Extracting plugin version\n"
    CURRENT_VERSION_NUMBER="$(cat vendi-wp-markdown.php | grep "Version:" | sed "s|Version:||" |  tr -d '[:space:]')"
    printf "Found version "
    printf "$CURRENT_VERSION_NUMBER"
    printf "\n"

    printf "Updating composer\n"
    composer up --no-dev  --quiet

    printf "Remove dev-only files\n"
    rm build.sh
    rm composer.json
    rm composer.lock
    rm README.md
    rm .gitignore

    rm -rf .git/

    find assets/ -type f -name "*.psd"          | xargs rm -f
    find vendor/ -type f -name "*.xml"          | xargs rm -f
    find vendor/ -type f -name "*.md"           | xargs rm -f
    find vendor/ -type f -name "*.sh"           | xargs rm -f
    find vendor/ -type f -name ".*"             | xargs rm -f
    find vendor/ -type f -name "*.json"         | xargs rm -f
    find vendor/ -type f -name "composer.lock"  | xargs rm -f
    find vendor/ -type d -name "tests"          | xargs rm -rf
    find vendor/ -type d -name ".git"           | xargs rm -rf

    cd ..

    mkdir -p ${PLUGIN_DIR}/releases/

    ZIP_FILE=${PLUGIN_DIR}/releases/${PLUGIN_FOLDER}.${CURRENT_VERSION_NUMBER}.zip

    if [ -f ${ZIP_FILE} ]; then
        printf "Removing old release of same version\n"
        rm ${ZIP_FILE}
    fi

    printf "Creating zip file "
    printf "$ZIP_FILE"
    printf "\n"
    zip -r9 --quiet ${ZIP_FILE} ${PLUGIN_FOLDER}/

    cd ${PLUGIN_DIR}

    printf "Removing temporary git folder\n"
    rm -rf ${LOCAL_GIT_FOLDER}
}

main()
{
    convert_readme_to_markdown
    make_translation_file
    checkout_plugin_to_temp

    printf "Done!\n"
}

main

