<?php

namespace Wurst\History\Entity;

class Record {
	protected $name;
	protected $date;
	protected $status;
	protected $error;
	protected $notice;
	protected $fatal;
	protected $info;
	protected $description;
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
	public function getError() {
		return $this->error;
	}
	public function setError($error) {
		$this->error = $error;
		return $this;
	}
	public function getNotice() {
		return $this->notice;
	}
	public function setNotice($notice) {
		$this->notice = $notice;
		return $this;
	}
	public function getFatal() {
		return $this->fatal;
	}
	public function setFatal($fatal) {
		$this->fatal = $fatal;
		return $this;
	}
	public function getInfo() {
		return $this->info;
	}
	public function setInfo($info) {
		$this->info = $info;
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