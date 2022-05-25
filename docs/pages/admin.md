# Le Panel administrateur
Une fois connecté, l'administrateur peut accéder au panel admin.  
Cela lui permet à l'utilisateur de générer les saisons avec les match attribué.  
Il utilise des fonctions PlpgSQL.

### Générer une nouvelle saison

Un nouveau championnat se base sur les résultats du précédent championnat : on sélectionne les meilleurs équipes en fonction des points gagnées.

```sql
create function generate_saison(idchamp integer, anneechamp integer) returns boolean
    language plpgsql
as
$$
    -- Genere une saison pour un championnat
    -- Les équipes sont selectionnene fonction de celle de la précédente saison
-- Auteur : Marcel Lhote
DECLARE
    liste_club      integer[];
    anneeprecedente integer;
    res             boolean;
BEGIN

    anneeprecedente := anneechamp - 1;

    raise notice 'anneprece %', anneeprecedente;

    liste_club := ARRAY(
            SELECT saison.id_club
            FROM saison
            WHERE id_championnat = idChamp
              AND annee = anneeprecedente
            ORDER BY nb_points DESC
            limit 20);

    raise notice 'length %', array_length(liste_club, 1);

    IF array_length(liste_club, 1) = 20 THEN
        -- boucle insertion équipe dans la saison
        FOR i in 1..array_length(liste_club, 1)
            LOOP
                INSERT INTO saison (id_club, id_championnat, annee) values (liste_club[i], idchamp, anneechamp);
            END LOOP;
        res = true;
    ELSE
        res = false;
    END IF;

    return res;
END;
$$;

alter function generate_saison(integer, integer) owner to postgres;

```

### les renctontres

Après avoir générer la saisons il faut enregistrer les match. Toutes les semaines un match est jouer par tout les clubs.  
On initialise également les match retour.

```sql
create function generate_rencontre(idchamp integer, datematch date) returns boolean
    language plpgsql
as
$$
    -- Genere une les rencontres pour une saison en fonction de l'annee de la date avec la date du premier match, le reste des match est calculé automatiquement
-- Auteur : Marcel Lhote
DECLARE
    liste_equipe  integer[];
    liste_arbitre integer[];
    anneechamp    integer;
    h             integer;
    temp          integer;
    res           boolean;
BEGIN
    anneechamp := EXTRACT(YEAR FROM datematch);

    liste_equipe := ARRAY(
            SELECT saison.id_club
            FROM saison
            WHERE id_championnat = idChamp
              AND annee = anneechamp
            ORDER BY nb_points DESC
            limit 20);

    -- boucle du nombre de jour
    FOR j in 1..array_length(liste_equipe, 1) - 1
        LOOP
        --raise notice 'jour : %', j;
        --raise notice 'tableau : %',liste_equipe;

            liste_arbitre := ARRAY(
                    SELECT id_arbitre
                    FROM arbitre
                    ORDER BY RANDOM());

            h := ARRAY_LENGTH(liste_equipe, 1);

            -- boucle du d'ajout des rencontres avec le tableau des équipe
            FOR i in 1..ARRAY_LENGTH(liste_equipe, 1) / 2
                LOOP
                -- raise notice 'match : %', i;
                -- raise notice 'domicile : %', liste_equipe[i];
                -- raise notice 'visiteur : %', liste_equipe[h];
                    INSERT INTO RENCONTRE
                    values (liste_equipe[i], liste_equipe[h], liste_arbitre[i], dateMatch, 0, 0, false);
                    -- math retroure à lieu après les 19 match de chaques semaines (19*7 = 133)
                    INSERT INTO RENCONTRE
                    values (liste_equipe[h], liste_equipe[i], liste_arbitre[i], dateMatch + 133, 0, 0, false);
                    h := h - 1;
                END LOOP;

            dateMatch := dateMatch + 7;

            -- refonte du tableau pour changer les matchs de la prochaine journee
            temp := liste_equipe[2];
            FOR k in 2..array_length(liste_equipe, 1)
                LOOP
                    IF k = array_length(liste_equipe, 1) THEN
                        liste_equipe[k] := temp;
                    ELSE
                        liste_equipe[k] := liste_equipe[k + 1];
                    END IF;
                END LOOP;

        END LOOP;
    res := true;

    return res;
END;
$$;

alter function generate_rencontre(integer, date) owner to postgres;
```