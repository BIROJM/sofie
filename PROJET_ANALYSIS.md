# Project Analysis: SOFIE (Système d'Information sur les Forages)

L'analyse du projet SOFIE a permis d'identifier une architecture robuste conçue pour la gestion et la maintenance des points d'eau (forages) au Togo.

## 1. Architecture Technique

Le projet est divisé en deux applications distinctes partageant la même base de données :

- **Frontend (IHM) : Symfony 2.7**
    - Localisation : `ihm/`
    - Rôle : Interface d'administration web principale.
    - Modules clés : Exploitation (`ExpBundle`), Administration (`AdminBundle`), Statistiques (`StatBundle`).
    - Technologies : Twig, Doctrine ORM, Assetic, mPDF (génération de rapports).

- **API : Laravel 4.1**
    - Localisation : `api/`
    - Rôle : Probablement destiné aux applications mobiles et à la synchronisation.
    - Points d'entrée : `api/v1/auth`, `api/v1/createForage`, `api/v1/updateForageData`.
    - Synchronisation : Un contrôleur dédié (`SynchroController`) gère les échanges de données entre les instances régionales et centrales.

## 2. Entités et Logique Métier

### Core Domain : L'Ouvrage (Forage)
L'entité centrale est l'**Ouvrage**. Chaque ouvrage est lié à :
- Une **Localité** et une **Région**.
- Un **Comité** de gestion.
- Des données techniques : Coupe géologique, équipements de forage, essais de pompage, suivi physico-chimique.

### Workflow de Maintenance (Pannes)
Le système gère le cycle de vie des pannes :
1. **Signalement** : Une panne est créée (manuellement ou via API).
2. **Notification SMS** : Le système envoie automatiquement des SMS au **Réparateur** et à l'**Agent** concernés en utilisant une passerelle SMS configurée (`t_config`).
3. **Suivi** : Suivi des dates d'apparition, de prise en charge et de réparation.
4. **Statut visuel** : Les ouvrages sont représentés sur une carte avec des codes couleurs (Vert: Marche, Rouge: Panne, etc.).

### Synchronisation Régionale/Centrale
Le projet semble conçu pour fonctionner en mode distribué :
- Chaque table possède une colonne `sync`.
- Les données collectées sur le terrain (via mobile ou instance régionale) sont synchronisées avec une instance centrale.

## 3. Base de Données

Le schéma MySQL contient :
- Des tables préfixées par `t_` (ex: `t_ouvrage`, `t_panne`, `t_agent`).
- Des triggers (`t_comite_before_upd_tr`) et des procédures stockées (`P_SELECT_INFOS_OUVRAGE_BY_NUMPANNE`) pour assurer l'intégrité et automatiser certains processus métier.

## 4. Points d'Attention
- **Versions legacy** : PHP 5.x est requis (Laravel 4.1 et Symfony 2.7). 
- **Complexité SQL** : Une partie importante de la logique métier réside dans les procédures stockées MySQL.
