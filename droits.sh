# droits pour vos accès
find . -type d -exec setfacl -m d:u:$USER:rwx {} \;
# droits pour l'admin du conteneur php
find . -type d -exec setfacl -m d:u:200000:rwx {} \;
find . -type d -exec setfacl -m u:200000:rwx {} \;
find . -type f -exec setfacl -m u:200000:rw {} \;
find bin -type f -exec setfacl -m u:200000:rx {} \;
# droits pour l'utilisateur www-data du conteneur php
find . -type d -exec setfacl -m d:u:200082:rx {} \;
find . -type d -exec setfacl -m u:200082:rx {} \;
find . -type f -exec setfacl -m u:200082:r {} \;
# droits pour l'utilisateur nginx du conteneur nginx
find public -type d -exec setfacl -m d:u:200101:rx {} \;
find public -type d -exec setfacl -m u:200101:rx {} \;
find public -type f -exec setfacl -m u:200101:r {} \;
