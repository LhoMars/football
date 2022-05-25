# Documentation Football

La fédération Française de Football souhaite refaire son site internet permettant la visualisation des matchs ainsi que leur gestion depuis un panel administrateur.  
Un script de création d’une base de données et insertion de données a été fourni pour la réalisation. Ce qui a amené à des modifications du schéma conceptuel de base pour qu’il puisse répondre à la demande exprimée.  
La demande attendue est de pouvoir générer automatiquement les matchs pour un championnat en fonction du résultat de la précédente saison. Il faut également pouvoir modifier les scores des matchs tout en affectant le classement de la saison.

### Architecture
L'architecture se base sur un serveur Apache et d'une base de données Postgres.

### Cas d'utilisation

Ce diagramme présente les fonctionnalités disponibles sur l'application

```mermaid
graph TB
intialise(intialise un championnat)
commente(commente un article)
serveurBdd[(Serveur de base de données)]

Admin:::user --> intialise
    classDef user fill:#0acf00;

Utilisateur:::commente 
    classDef user fill:#3355ff;

serveurBdd <--> intialise
```