<?php
	class course {
		var $uv;
		var $type;
		var $day;
		var $begin;
		var $begin_integer;
		var $last;
		var $end_integer;
		var $end;
		var $room;
		var $group;
		var $color;

		function __construct($uv, $type, $day, $begin, $end, $room, $group){

		$this->uv = $uv;
		$this->type = $type;
		$this->day = $day;

		$this->begin = $begin;

		$begin_temp = explode(":", $begin);
		$begin_standard = $begin_temp['0'].$begin_temp['1'];
		$this->begin_integer = $begin_standard;

		$end_temp = explode(":", $end);
		$end_standard = $end_temp['0'].$end_temp['1'];
		$this->end_integer = $end_standard;

		$this->last = difheure($begin, $end);

		$this->end = $end;

		$this->room  = $room;
		$this->group = $group;
		}

		public function getuv(){
		return $this->uv ;
		}

		public function gettype(){
		return $this->type;
		}

		public function  getday(){
		return $this->day;
		}

		public function getbegin(){
		return $this->begin;
		}

		public function getend(){
		return $this->end;
		}

		public function getlast(){
		return $this->last;
		}

		public function getroom(){
		return $this->room;
		}

		public function getcolor(){
		return $this->color;
		}

		public function getgroup(){
		return $this->group;
		}

		public function setcolor($color){
		$this->color = $color;
		}
	};

?>
