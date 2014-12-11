
DROP TABLE IF EXISTS dimensao_tempo ;
CREATE TABLE dimensao_tempo(
  tempo_id    INT AUTO_INCREMENT,
  dia         INT NOT NULL,
  mes         INT NOT NULL,
  ano         INT NOT NULL,
  PRIMARY KEY (tempo_id)
);

DROP TABLE IF EXISTS dimensao_local ;
CREATE TABLE dimensao_local(
  local_id    INT AUTO_INCREMENT,
  regiao      VARCHAR(80) NOT NULL,
  concelho    VARCHAR(80) NOT NULL,
  PRIMARY KEY (tempo_id)
);

DROP TABLE IF EXISTS maior_lance ;
CREATE TABLE maior_lance(
  tempo_id    INT NOT NULL,
  local_id    INT NOT NULL,
  nif         INT NOT NULL,
  lid         INT NOT NULL,
  valor       INT NOT NULL,
  FOREIGN KEY (tempo_id) REFERENCES dimensao_tempo(tempo_id),
  FOREIGN KEY (local_id) REFERENCES dimensao_local(local_id),
  FOREIGN KEY (nif) REFERENCES lance(pessoa),
  FOREIGN KEY (lid) REFERENCES lance(leilao),
  FOREIGN KEY (valor) REFERENCES lance(valor)
);