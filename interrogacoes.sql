-- 3.1

SELECT pessoa,leilao FROM concorrente WHERE (pessoa,leilao) NOT IN (SELECT pessoa,leilao FROM lance);

--3.2

SELECT P.nome FROM pessoa P, pessoac PC WHERE P.nif = PC.nif AND (SELECT count(pessoa) FROM concorrente WHERE pessoa = P.nif) = 2;

--3.3

SELECT max(LA.valor / L.valorbase) FROM leilao L, lance LA;

--3.4

SELECT DISTINCT P.nif FROM pessoac P, pessoac P1 WHERE P.capitalsocial = P1.capitalsocial AND P.nif != P1.nif;



