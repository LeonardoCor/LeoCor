<?php
/*
 * Copyright (C) 2019 Leonardo Corazzi
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
namespace LeoCor\Data\Input;

/**
 * Description of Globals
 *
 * @author Leonardo Corazzi <leonardo.corazzi@outlook.it>
 */
class Globals
{
    /**
     * @var Globals
     */
    private $instance;
    
    private $get;
    
    private $post;
    
    /**
     * Constructor.
     */
    private function __construct()
    {}
    
    /**
     * Returns single instance.
     * 
     * __*Singleton*__.
     * @return Globals;
     */
    public function getInstance()
    {
        if (is_null($this->instance)) {
            $this->instance = new Globals();
        }
        return $this->instance;
    }
}
