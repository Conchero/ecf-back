# Installation du projet 



## Creation des packages 

1 - ```composer install``` dans le terminal depuis le fichier "ecf-back"

2 - ```php bin/console tailwind:build ``` dans le terminal depuis le fichier "ecf-back"


3 - ```php bin/console importmap:install ``` dans le terminal depuis le fichier "ecf-back"


## Creation de la base de données

1 - ```symfony || php/bin console doctrine:create:database``` dans le terminal depuis le fichier "ecf-back"

2 - ```symfony || php/bin console make:migration``` dans le terminal depuis le fichier "ecf-back"

3 - ```symfony || php/bin console doctrine:make:migration``` dans le terminal depuis le fichier "ecf-back"


4 - ```symfony || php/bin console d:f:l``` dans le terminal depuis le fichier "ecf-back", pour avoir accés à des élements préfait, Salles, Equipements etc.. ainsi que le compte admin


Et vous devriez être bon :) 