<?php
/*
    This file is part of Erebot.

    Erebot is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Erebot is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Erebot.  If not, see <http://www.gnu.org/licenses/>.
*/

class   Erebot_Module_WatchList
extends Erebot_Module_Base
{
    protected $_watchedNicks;

    public function _reload($flags)
    {
        if ($flags & self::RELOAD_HANDLERS) {
            $handler    =   new Erebot_EventHandler(
                array($this, 'handleConnect'),
                new Erebot_Event_Match_InstanceOf('Erebot_Event_Connect')
            );
            $this->_connection->addEventHandler($handler);
        }

        if ($flags & self::RELOAD_MEMBERS) {
            $watchedNicks = $this->parseString('nicks', '');
            $watchedNicks = str_replace(',', ' ', $watchedNicks);
            $this->_watchedNicks = array_filter(array_map('trim',
                                    explode(' ', $watchedNicks)));
        }
    }

    protected function _unload()
    {
    }

    public function handleConnect(Erebot_Interface_Event_Generic &$event)
    {
        if (!count($this->_watchedNicks))
            return;

        $this->sendCommand('WATCH +'.implode(' +', $this->_watchedNicks));
    }
}

