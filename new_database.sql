/*
for postgres
*/

--https://stackoverflow.com/questions/31405743/how-do-i-remove-all-tables-and-not-the-schema-in-postgresql
DO $$
DECLARE
    r record;
BEGIN
    FOR r IN SELECT quote_ident(tablename) AS tablename, quote_ident(schemaname) AS schemaname FROM pg_tables WHERE schemaname = 'public'
    LOOP
        RAISE INFO 'Dropping table %.%', r.schemaname, r.tablename;
        EXECUTE format('DROP TABLE IF EXISTS %I.%I CASCADE', r.schemaname, r.tablename);
    END LOOP;
END$$;

--create tables
CREATE TABLE simple (
    rowid      serial       PRIMARY KEY,
    somenumber  integer     ,
    somestring  text
)
INSERT INTO simple (somenumber,somestring) VALUES (123,"I Like cheese");
INSERT INTO simple (somenumber,somestring) VALUES (345,"I Like potato");
INSERT INTO simple (somenumber,somestring) VALUES (555555,"some credit card info");
INSERT INTO simple (somenumber,somestring) VALUES (935,"Lorem Ipsum");
INSERT INTO simple (somenumber,somestring) VALUES (122,"some password");
