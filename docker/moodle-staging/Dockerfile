# Campus Asaka, image de staging : image Moodle du serveur + thème Asaka.
#
# Construite sur le VPS par /srv/bin/deploy-asaka (dépôt serenihost-server), contexte = le thème :
#   docker build -f docker/moodle-staging/Dockerfile --build-arg ASAKA_REV=<rev> moodle/theme/asaka
#
# L'image de base garde le code Moodle dans /usr/src/moodle et le recopie dans le volume
# /var/www/html (rsync --delete) dès que MOODLE_IMAGE_VERSION change. Le thème doit donc être
# dans l'image : un montage ou une copie directe dans le volume serait effacé à la synchronisation.
ARG BASE_IMAGE=moodle:5.2.3-php8.3-1
FROM ${BASE_IMAGE}

ARG ASAKA_REV=dev

COPY --chown=root:www-data . /usr/src/moodle/public/theme/asaka

# Nouvelle révision du thème = nouvelle valeur = synchronisation du code au prochain démarrage.
ENV MOODLE_IMAGE_VERSION=${MOODLE_IMAGE_VERSION}+asaka.${ASAKA_REV}
