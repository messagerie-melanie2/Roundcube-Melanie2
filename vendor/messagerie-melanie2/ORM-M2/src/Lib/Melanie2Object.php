<?php
/**
 * Ce fichier est développé pour la gestion de la librairie Mélanie2
 * Cette Librairie permet d'accèder aux données sans avoir à implémenter de couche SQL
 * Des objets génériques vont permettre d'accèder et de mettre à jour les données
 *
 * ORM M2 Copyright © 2017  PNE Annuaire et Messagerie/MEDDE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace LibMelanie\Lib;

use LibMelanie\Exceptions;

/**
 * Objet Melanie2, implémente les getter/setter pour le mapping des données
 *
 * @author PNE Messagerie/Apitech
 * @package Librairie Mélanie2
 * @subpackage Lib
 */
abstract class Melanie2Object {
	/**
	 * Objet Melanie2
	 */
	protected $objectmelanie;
	/**
	 * Classe courante
	 * @var string
	 */
	protected $get_class;

	/**
	 * Défini l'objet Melanie
	 * @param ObjectMelanie $objectmelanie
	 * @ignore
	 */
	function setObjectMelanie($objectmelanie) {
		$this->objectmelanie = $objectmelanie;
	}

	/**
	 * Récupère l'objet Melanie
	 * @ignore
	 */
	function getObjectMelanie() {
		if (!isset($this->objectmelanie)) throw new Exceptions\ObjectMelanieUndefinedException();
		return $this->objectmelanie;
	}

	/**
	 * PHP magic to set an instance variable
	 *
	 * @param string $name Nom de la propriété
	 * @param mixed $value Valeur de la propriété
	 * @access public
	 * @return
	 * @ignore
	 */
	public function __set($name, $value) {
		$lname = strtolower($name);
		$uname = ucfirst($lname);

		// Call SetMapProperty
		if (method_exists($this, "setMap$uname")) {
			$this->{"setMap$uname"}($value);
		} else {
			$this->objectmelanie->$lname = $value;
		}
	}

	/**
	 * PHP magic to get an instance variable
	 *
	 * @param string $name Nom de la propriété
	 * @access public
	 * @return
	 * @ignore
	 */
	public function __get($name) {
		$lname = strtolower($name);
		$uname = ucfirst($lname);

		// Call GetMapProperty
		if (method_exists($this, "getMap$uname")) {
			return $this->{"getMap$uname"}();
		} elseif (isset($this->objectmelanie->$lname)) {
			return $this->objectmelanie->$lname;
		}
		return null;
	}

	/**
	 * PHP magic to check if an instance variable is set
	 *
	 * @param string $name Nom de la propriété
	 * @access public
	 * @return
	 * @ignore
	 */
	public function __isset($name) {
	    $lname = strtolower($name);
	    $uname = ucfirst($lname);

	    if (method_exists($this, "issetMap$uname")) {
	        return $this->{"issetMap$uname"}();
	    } else {
	        return isset($this->objectmelanie->$lname);
	    }
	}

	/**
	 * PHP magic to remove an instance variable
	 *
	 * @param string $name Nom de la propriété
	 * @access public
	 * @return
	 * @ignore
	 */
	public function __unset($name) {
		$lname = strtolower($name);
		if (isset($this->objectmelanie->$lname)) {
			unset($this->objectmelanie->$lname);
		}
	}

	/**
	 * PHP magic to implement any getter, setter, has and delete operations
	 * on an instance variable.
	 * Methods like e.g. "SetVariableName($x)" and "GetVariableName()" are supported
	 *
	 * @param string $name Nom de la methode
	 * @param array $arguments Arguments de la methode
	 * @access public
	 * @return mixed
	 * @ignore
	 */
	public function __call($name, $arguments) {
		$name = strtolower($name);

		// Call method
		if (method_exists($this, $name)) {
			return $this->{$name}(implode(',', $arguments));
		} else {
			return $this->objectmelanie->{$name}(implode(',', $arguments));
		}
	}
}