<?php
class MetaData implements Iterator {
	private $_values = array();
	static public function fetch($URI) {
		$curl = curl_init($URI);

		curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        $response = curl_exec($curl);

        curl_close($curl);

		if(!empty($response)) {
			return self::_parse($response);
		}
		else {
			return false;
        }
	}

	static private function _parse($HTML) {
		$page = new self();
		$rawTags = array();

		preg_match_all("|<meta[^>]+=\"([^\"]*)\"[^>]" . "+content=\"([^\"]*)\"[^>]+>|i", $HTML, $rawTags, PREG_PATTERN_ORDER);

		if(!empty($rawTags)) {
			$multiValueTags = array_unique(array_diff_assoc($rawTags[1], array_unique($rawTags[1])));

			for($i=0; $i < sizeof($rawTags[1]); $i++) {
				$hasMultiValues = false;
				$tag = $rawTags[1][$i];

				foreach($multiValueTags as $mTag) {
					if($tag == $mTag)
						$hasMultiValues = true;
				}
				
				if($hasMultiValues) {
					$page->_values[$tag][] = $rawTags[2][$i];
				}
				else {
					$page->_values[$tag] = $rawTags[2][$i];
				}
			}
		}

		if (empty($page->_values)) { return false; }

		return $page;
	}

	public function tags() {
		return $this->_values;
	}
	public function __get($key) {
		if (array_key_exists($key, $this->_values)) {
			return $this->_values[$key];
		}
	}
	public function keys() {
		return array_keys($this->_values);
	}

	public function __isset($key) {
		return array_key_exists($key, $this->_values);
	}
	private $_position = 0;
	public function rewind() { reset($this->_values); $this->_position = 0; }
	public function current() { return current($this->_values); }
	public function key() { return key($this->_values); }
	public function next() { next($this->_values); ++$this->_position; }
	public function valid() { return $this->_position < sizeof($this->_values); }
}