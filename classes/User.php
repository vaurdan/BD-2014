<?php
/**
 * Classe que representa o utilizador autenticado
 */
error_reporting(E_ERROR | E_WARNING | E_PARSE);

class User {

	/** @var string Nome do Utilizador */
	public $nome;

	/** @var int NIF do utilizador */
	public $nif;

	/** @var int PIN do utilizador */
	public $pin;

	public function __construct( $nif ) {
		$this->nif = $nif;

		$this->actualizar_utilizador();
	}

	protected function actualizar_utilizador() {
		global $db;

		$query = $db->prepare( "SELECT * FROM pessoa WHERE nif = ?");
		$query->execute( array( $this->nif ) );

		$result = $query->fetch();

		$this->nome = $result['nome'];
		$this->pin = $result['pin'];

	}

	public function is_empresa() {
		return false;
	}

}

class UserEmpresa extends User {

	public $capital;

	public function __construct ( $nif ) {
		parent::__construct( $nif );
	}

	protected function actualizar_utilizador() {
		global $db;

		$query = $db->prepare( "SELECT * FROM pessoa p, pessoac c WHERE c.nif = ? AND p.nif = c.nif");
		$query->execute( array( $this->nif ) );

		$result = $query->fetch();

		$this->nome = $result['nome'];
		$this->pin = $result['pin'];
		$this->capital = $result['capitalsocial'];

	}

	public function is_empresa() {
		return true;
	}
}