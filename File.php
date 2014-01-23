<?php
class File {

	protected $path = '';

	public function __construct($path) {
		$this->path = $path;
	}

	public function read($file_name = '') {

		try {

			if(!file_exists($this->path . $file_name)) {
				return false;
			}
			$file = fopen($this->path . $file_name, 'r');
			if($file == NULL) {
				return false;
			}
			$data = array();
			while(!feof($file)) {
				$data[] = fgets($file);
			}
			fclose($file);
			return $data;

		} catch(Exception $e) {

		}
	}

	public function write($file_name = '', $data = array()) {
		try {

			$file = fopen($this->path . $file_name, 'a+');
			foreach($data as $items) {
				fwrite($this->path . $file_name, $items);
			}
			fclose($file);
			return true;

		} catch(Exception $e) {

		}
	}

	public function move($oldname = '', $newname = '') {
		return rename($this->path . $oldname, $newname);
	}

	public function delete($file_name = '') {
		return unlink($this->path . $file_name);
	}

	public function permissions($file_name = '', $mode = '') {
		return chmod($this->path . $file_name, $mode);
	}

	public function owner($file_name = '', $owner = '') {
		return chown($this->path . $file_name, $owner);
	}

	public function upload($dom_name = '', $file_name = array(), $allow_file_type = array(), $allow_file_size = array()) {
		try{

			if(is_array($file_name)) {
				$count = count($file_name);
				for($i = 0; $i < $count; $i++) {
					if($_FILES[$dom_name]['error'][$i] > 0) {
						return array('type' => 'file error', 'no' => $i, 'message' => $_FILES[$dom_name]['error'][$i]);
					}
					if(!in_array($_FILES[$dom_name]['type'][$i], $allow_file_type)) {
						return array('type' => 'type error', 'no' => $i, 'message' => $_FILES[$dom_name]['type'][$i]);
					}
					if($_FILES[$dom_name]['size'][$i] > $allow_file_size) {
						return array('type' => 'size error', 'no' => $i, 'message' => $_FILES[$dom_name]['size'][$i]);
					}
					if(file_exists($this->path . $file_name[$i])) {
						return array('type' => 'file exists', 'no' => $i, 'message' => $_FILES[$dom_name]['name'][$i]);
					}
					move_uploaded_file($_FILES[$dom_name]['tmp_name'][$i], $this->path . $file_name[$i]);
				}
			} else {
				if($_FILES[$dom_name]['error'] > 0) {
					return array('type' => 'file error', 'no' => 0, 'message' => $_FILES[$dom_name]['error']);
				}
				if(!in_array($_FILES[$dom_name]['type'], $allow_file_type)) {
					return array('type' => 'type error', 'no' => 0, 'message' => $_FILES[$dom_name]['type']);
				}
				if($_FILES[$dom_name]['size'] > $allow_file_size) {
					return array('type' => 'size error', 'no' => 0, 'message' => $_FILES[$dom_name]['size']);
				}
				if(file_exists($this->path . $file_name)) {
					return array('type' => 'file exists', 'no' => 0, 'message' => $_FILES[$dom_name]['name']);
				}
				move_uploaded_file($_FILES[$dom_name]['tmp_name'], $this->path . $file_name);
			}
			
		} catch(Exception $e) {

		}
	}
}
?>
