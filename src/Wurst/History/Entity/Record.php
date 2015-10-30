<?php

namespace Wurst\History\Entity;

class Record {
	protected $name;
	protected $description;
	protected $date;
	protected $status;
	protected $info;
	protected $warning;
	protected $error;
	protected $stderr;
	protected $fatal;
	protected $logfile;

	public function getLogfile() {
		return $this->logfile;
	}
	public function setLogfile($logfile) {
		$this->logfile = $logfile;
		return $this;
	}
	
	public function getName() {
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	public function getDate() {
		return $this->date;
	}
	public function setDate($date) {
		$this->date = $date;
		return $this;
	}
	public function getStatus() {
		return $this->status;
	}
	public function setStatus($status) {
		$this->status = $status;
		return $this;
	}
	public function getInfo() {
		return $this->info;
	}
	public function setInfo($info) {
		$this->info = $info;
		return $this;
	}
	public function getWarning() {
		return $this->warning;
	}
	public function setWarning($warning) {
		$this->warning = $warning;
		return $this;
	}
	public function getError() {
		return $this->error;
	}
	public function setError($error) {
		$this->error = $error;
		return $this;
	}
	public function getStderr() {
		return $this->stderr;
	}
	public function setStderr($stderr) {
		$this->stder = $stderr;
		return $this;
	}
	public function getFatal() {
		return $this->fatal;
	}
	public function setFatal($fatal) {
		$this->fatal = $fatal;
		return $this;
	}
	public function getDescription() {
		return $this->description;
	}
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}
	public function __toString() {
		return $this->getDate ();
	}
}