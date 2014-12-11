-- 3.1

SELECT pessoa,leilao FROM concorrente WHERE (pessoa,leilao) NOT IN (SELECT pessoa,leilao FROM lance);

--3.2

SELECT P.nome FROM pessoa P, pessoac PC WHERE P.nif = PC.nif AND (SELECT count(pessoa) FROM concorrente WHERE pessoa = P.nif) = 2;

--3.3

SELECT max(LA.valor / L.valorbase) FROM leilao L, lance LA;

SELECT max(LA.valor / L.valorbase) FROM leilao L, leilaor R, lance LA WHERE (L.dia, L.nrleilaonodia, L.nif) = (R.dia, R.nrleilaonodia, R.nif) AND R.lid = LA.leilao;

--3.4

SELECT DISTINCT P.nif FROM pessoac P, pessoac P1 WHERE P.capitalsocial = P1.capitalsocial AND P.nif != P1.nif;



DELIMITER //
DROP TRIGGER IF EXISTS before_insert_lance//
CREATE TRIGGER before_insert_lance 
BEFORE INSERT ON lance
FOR EACH ROW
 BEGIN
    SET @valorlance := (SELECT valorbase FROM leilao WHERE (dia, nrleilaonodia,nif ) = (SELECT dia, nrleilaonodia, nif FROM leilaor WHERE lid = NEW.leilao));
IF NEW.valor < @valorlance THEN
CALL `'Erro: valor lance não pode ser inferior à base de licitação'`;
END IF; 
END//
DELIMITER ;
