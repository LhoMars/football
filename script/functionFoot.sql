-- 				Initialise les rencontre
create or replace function SET_RENCONTRE() returns boolean as $$
DECLARE
res boolean;
	nb_club integer;
BEGIN
	nb_club := (SELECT COUNT(*) FROM CLUB);

	IF nb_club < 20 OR nb_club > 20 THEN
	    return true;
END IF;

return false;

END ;
$$ LANGUAGE 'plpgsql';



-- 				Ajouter un des club à une saison
DROP FUNCTION IF EXISTS ADD_CLUB_SAISON();

create or replace function ADD_CLUB_SAISON(idClub integer, idChamp integer, anneeSaison integer, maxEquipe integer default 20) returns boolean as $$
DECLARE
nb_club integer;
    res boolean;
BEGIN
	nb_club = (SELECT COUNT(*) FROM saison WHERE id_championnat = idChamp AND annee = anneeSaison);
	IF nb_club >= maxEquipe THEN
	    raise notice 'saison complete';
	    res = false;
	ELSIF EXISTS (select id_club FROM club WHERE id_club = idClub) THEN
            IF NOT EXISTS (SELECT id_club FROM saison WHERE id_club = idClub AND annee = anneeSaison) THEN
                INSERT INTO SAISON (id_club, id_championnat, annee) values (idClub, idChamp, anneeSaison);
                res = true;
ELSE
                res = false;
                raise notice 'déjà présente';
END IF;
ELSE
        res = false;
	    raise notice 'pas de club avec cet id';
END IF;

return res;
END;$$ LANGUAGE 'plpgsql';


select ADD_CLUB_SAISON(2, 1, 2018);




-- 				Ajouter des club à une saison
DROP FUNCTION IF EXISTS ADD_CLUBS_SAISON();

create or replace function ADD_CLUBS_SAISON(idClub integer[], idChamp integer, anneeSaison integer, maxEquipe integer default 20) returns boolean as $$
DECLARE
nb_club integer;
    res boolean;
BEGIN
	nb_club = (SELECT COUNT(*) FROM saison WHERE id_championnat = idChamp AND annee = anneeSaison);
	IF nb_club + ARRAY_LENGTH(idClub,1) > maxEquipe THEN
	    raise notice 'Trop de club à ajouter ou saison complete';
	    res = false;
ELSE
	    FOR i in 0..ARRAY_LENGTH(idClub,1) LOOP
	        res := ADD_CLUB_SAISON(i, idChamp, anneeSaison, maxEquipe);
END LOOP;
	    res = true;
END IF;

return res;
END;$$ LANGUAGE 'plpgsql';


select ADD_CLUBS_SAISON(ARRAY[1,2], 1, 2018);


--				Generation des match saison
DROP FUNCTION IF EXISTS generate_saison;
create function generate_saison(idchamp integer, anneechamp integer, datematch date) returns boolean
    language plpgsql
as $$
DECLARE
liste_equipe integer[];
    liste_arbitre integer[];
    h integer;
    temp integer;
    res boolean;
BEGIN
    liste_equipe := ARRAY(
        SELECT saison.id_club
        FROM saison
        WHERE id_championnat = idChamp
        AND annee = anneeChamp
        ORDER BY RANDOM());

    h = ARRAY_LENGTH(liste_equipe,1);

    IF h < 20 OR h > 20 THEN
        res := false;
        raise notice 'Le nombre d équipe n est pas valable, nb équipes : %', h;
ELSE

        -- boucle du nombre de jour
        FOR j in 1..array_length(liste_equipe,1)-1 LOOP
            --raise notice 'jour : %', j;
            --raise notice 'tableau : %',liste_equipe;

            liste_arbitre := ARRAY(
                SELECT id_arbitre
                FROM arbitre
                ORDER BY RANDOM());

            h := ARRAY_LENGTH(liste_equipe,1);

            -- boucle du d'ajout des rencontres avec le tableau des équipe
FOR i in 1..ARRAY_LENGTH(liste_equipe,1)/2 LOOP
                --raise notice 'match : %', i;
                --raise notice 'domicile : %', liste_equipe[i];
                --raise notice 'visiteur : %', liste_equipe[h];
                INSERT INTO RENCONTRE values (liste_equipe[i], liste_equipe[h], liste_arbitre[i], dateMatch, 0, 0);
INSERT INTO RENCONTRE values (liste_equipe[h], liste_equipe[i], liste_arbitre[i], dateMatch+(ARRAY_LENGTH(liste_equipe,1)+7), 0, 0);
h := h-1;
END LOOP;

            dateMatch := dateMatch+7;

            -- refonte du tableau pour changer les matchs de la prochaine journées
            temp := liste_equipe[2];
FOR k in 2..array_length(liste_equipe,1) LOOP
                IF k = array_length(liste_equipe,1) THEN
                    liste_equipe[k] := temp;
ELSE
                liste_equipe[k] := liste_equipe[k+1];
END IF;
END LOOP;

END LOOP;
        res := true;
END IF;

return res;
END;
$$;

select GENERATE_SAISON(1,2018, date('09/12/2021'));
select * from rencontre where id_club_domicile = 12 OR id_club_visiteur = 12;






--              Liste rencontre d'une jourée
DROP FUNCTION IF EXISTS liste_rencontre(character varying);
CREATE OR REPLACE FUNCTION liste_rencontre(jour_match varchar)
RETURNS TABLE(id_club_domicile integer, id_club_visiteur integer, id_arbitre integer, date_match date, score_domicile integer, score_visiteur integer) AS $$
BEGIN
RETURN QUERY
Select * from rencontre r where r.date_match = date(jour_match);
END;$$ LANGUAGE'plpgsql';

Select * from liste_rencontre('2022-01-05');







--				Obtenir le nb de point pour un match
DROP FUNCTION IF EXISTS update_club_saison(id_club integer, id_champ integer, anne integer);
CREATE FUNCTION update_club_saison(id_club integer, id_champ integer, anne integer) RETURNS void as $$
DECLARE

BEGIN

END;$$ LANGUAGE 'plpgsql';


--              Modifier une rencontre
--              ("OM", "ASM", "2-1", "2013-02-09", "Mr Durand")
DROP FUNCTION IF EXISTS modif_rencontre();
CREATE FUNCTION modif_rencontre(id_domicile varchar, id_visiteur varchar, score varchar, date varchar, arbitre varchar) RETURNS void AS $$
BEGIN
    res = (SELECT * FROM rencontre r
            INNER JOIN club c ON r.id_club_domicile = c.id_club
            INNER JOIN arbitre a on r.id_arbitre = a.id_arbitre
            WHERE LOWER(c.nom_club) = LOWER(id_domicile)
              AND r.id_club_visiteur = (select club.id_club from club WHERE LOWER(club.nom_club) = LOWER(id_visiteur))
              AND r.date_match = DATE(date)
              AND LOWER(a.nom) = LOWER(arbitre));
END;$$ LANGUAGE 'plpgsql';