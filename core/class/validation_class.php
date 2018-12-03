<?php

class validation{

    /*
    * @errors array
    */
    public $errors = array();

    /*
    * @the validation rules array
    */
    private $validation_rules = array();

    /*
     * @the sanitized values array
     */
    public $sanitized = array();
     
    /*
     * @the source 
     */
    private $source = array();
	
	private static $_valid_jpeg_mimes=array('image/jpe', 'image/jpg', 'image/jpeg', 'image/pjpeg'),
				   $_valid_png_mimes=array('image/x-png','image/png'),
				   $_valid_bmp_mimes=array('image/x-windows-bmp','image/bmp'), 				
				   $_valid_img_mimes=array('image/gif', 'image/jpeg', 'image/png', 'image/bmp');			

    /**
     *
     * @the constructor, duh!
     *
     */
    public function __construct()
    {
    }

    /**
     *
     * @add the source
     *
     * @paccess public
     *
     * @param array $source
     *
     */
    public function addSource($source, $trim=false)
    {
        $this->source = $source;
    }


    /**
     *
     * @run the validation rules
     *
     * @access public
     *
     */
    public function run()
    {
        /*** set the vars ***/
        foreach( new ArrayIterator($this->validation_rules) as $var=>$opt)
        {
            if($opt['required'] == true)
            {
                $this->is_set($var);
            }
			if(isset($this->source[$var]) || ((isset($opt['type']) && ($opt['type']=="validate_multiple_image" || $opt['type']=="validate_image"|| $opt['type']=="file")))){
				/*** Trim whitespace from beginning and end of variable ***/
				if( array_key_exists('trim', $opt) && $opt['trim'] == true )
				{
					$this->source[$var] = trim( $this->source[$var] );
				}

				switch($opt['type'])
				{
					case 'email':
						$this->validateEmail($var, $opt['required']);
						if(!array_key_exists($var, $this->errors))
						{
							$this->sanitizeEmail($var);
						}
						break;

					case 'url':
						$this->validateUrl($var);
						if(!array_key_exists($var, $this->errors))
						{
							$this->sanitizeUrl($var);
						}
						break;

					case 'number':
						if(!isset($opt['min']))
							$opt['min']=0;
						if(!isset($opt['max']))
							$opt['max']=0;
						$this->validateNumeric($var, $opt['min'], $opt['max'], $opt['required']);
						if(!array_key_exists($var, $this->errors))
						{
							$this->sanitizeNumeric($var);
						}
						break;

					case 'string':
						$this->validateString($var, $opt['min'], $opt['max'], $opt['required']);
						if(!array_key_exists($var, $this->errors))
						{
							$this->sanitizeString($var);
						}
					break;

					case 'float':
						$this->validateFloat($var, $opt['required']);
						if(!array_key_exists($var, $this->errors))
						{
							$this->sanitizeFloat($var);
						}
						break;

					case 'ipv4':
						$this->validateIpv4($var, $opt['required']);
						if(!array_key_exists($var, $this->errors))
						{
							$this->sanitizeIpv4($var);
						}
						break;

					case 'ipv6':
						$this->validateIpv6($var, $opt['required']);
						if(!array_key_exists($var, $this->errors))
						{
							$this->sanitizeIpv6($var);
						}
						break;

					case 'bool':
						$this->validateBool($var, $opt['required']);
						if(!array_key_exists($var, $this->errors))
						{
							$this->sanitized[$var] = (bool) $this->source[$var];
						}
						break;
					case 'file':
						if($err=$this->validate_file($var, $opt))
							$this->errors[$var]=$err;
						break;
					case 'validate_image': 						if($err=$this->validate_image($var, $opt))
							$this->errors[$var]=$err;
						break;
					case 'validate_multiple_image':
						if($err=$this->validate_multiple_image($var, $opt))
							$this->errors[$var]=$err;
						break;
				}
			}
		}
    }


    /**
     *
     * @add a rule to the validation rules array
     *
     * @access public
     *
     * @param string $varname The variable name
     *
     * @param string $type The type of variable
     *
     * @param bool $required If the field is required
     *
     * @param int $min The minimum length or range
     *
     * @param int $max the maximum length or range
     *
     */
    public function addRule($varname, $type, $required=false, $min=0, $max=0, $trim=false)
    {
        $this->validation_rules[$varname] = array('type'=>$type, 'required'=>$required, 'min'=>$min, 'max'=>$max, 'trim'=>$trim);
        /*** allow chaining ***/
        return $this;
    }


    /**
     *
     * @add multiple rules to teh validation rules array
     *
     * @access public
     *
     * @param array $rules_array The array of rules to add
     *
     */
    public function AddRules(array $rules_array)
    {
        $this->validation_rules = array_merge($this->validation_rules, $rules_array);
    }

    /**
     *
     * @Check if POST variable is set
     *
     * @access private
     *
     * @param string $var The POST variable to check
     *
     */
    private function is_set($var)
    {
        if(!isset($this->source[$var]))
        {
            $this->errors[$var] = $var . ' is not set';
        }
    }

	/**
     *
     * @validating file
     *
     */
	 public function validate_file($file, $validate_arr=array())
    {
		$error_msg="";
		if(isset($validate_arr["frequired"]) && $validate_arr["frequired"]===true && (!isset($_FILES[$file]['name']))){
			$error_msg="File is not uploaded";
			return $error_msg;
		}
		if(isset($_FILES[$file]['name'])){
			$file_name=$_FILES[$file]['name'];
			$file_ext=strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
			$file_tmp=$_FILES[$file]['tmp_name'];
			$file_type=$_FILES[$file]['type'];
			$file_size=$_FILES[$file]['size'];
			if($file_size<=0){
				$error_msg="File size exceeded";
				return $error_msg;
			}
			if(isset($validate_arr["allow_file_type"]) && $validate_arr["allow_file_type"]!=""){
				if(isset($file_ext)){
					$allow_type_arr=explode(",",$validate_arr["allow_file_type"]);
					if(!in_array($file_ext,$allow_type_arr)){
						$error_msg="Invalid File Type ".$file_name;
						return $error_msg;
					}
				}else{
					$error_msg="Invalid File Extension";
					return $error_msg;
				}
			}
			if(isset($validate_arr["max_file_size"]) && $validate_arr["max_file_size"]!=""){
				if($file_size>$validate_arr["max_file_size"]){
					$error_msg="Maximum File size exceeded";
					return $error_msg;
				}
			}
		}
		else if(isset($validate_arr["frequired"]) && $validate_arr["frequired"]===true){
			$error_msg="File is not uploaded";
			return $error_msg;
		}
		return $error_msg;
	}
	/**
     *
     * @validating file
     *
     */
	 public function validate_image($file, $validate_arr=array())
    {
		$error_msg="";
		if(isset($validate_arr["required"]) && $validate_arr["required"]===true && (!isset($_FILES[$file]['name']))){
			$error_msg="File is not uploaded";
			return $error_msg;
		}
		if(isset($_FILES[$file]['name'])){
			$file_name=$_FILES[$file]['name'];
			$file_ext=strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
			$file_tmp=$_FILES[$file]['tmp_name'];
			$file_type=$_FILES[$file]['type'];
			$file_size=$_FILES[$file]['size'];
			if($file_size<=0){
				$error_msg="File size exceeded";
				return $error_msg;
			}
			if(isset($validate_arr["allow_file_type"]) && $validate_arr["allow_file_type"]!=""){
				if(isset($file_ext)){
					$allow_type_arr=explode(",",$validate_arr["allow_file_type"]);
					if(!in_array($file_ext,$allow_type_arr)){
						$error_msg="Invalid File Type ".$file_name;
						return $error_msg;
					}
				}else{
					$error_msg="Invalid File Extension";
					return $error_msg;
				}
			}
			if(isset($validate_arr["max_file_size"]) && $validate_arr["max_file_size"]!=""){
				if($file_size>$validate_arr["max_file_size"]){
					$error_msg="Maximum File size exceeded";
					return $error_msg;
				}
			}
		}
		else if(isset($validate_arr["required"]) && $validate_arr["required"]===true){
			$error_msg="File is not uploaded";
			return $error_msg;
		}
		return $error_msg;
	}
	public function validate_multiple_image($file, $validate_arr=array())
    {
		$error_msg="";
		if(isset($validate_arr["required"]) && $validate_arr["required"]===true && (!isset($_FILES[$file]['name']))){
			$error_msg="File is not uploaded";
			return $error_msg;
		}
		if(isset($_FILES[$file]['name'])){
			foreach($_FILES[$file]['name'] as $va=>$key){
				if(isset($_FILES[$file]['name'][$va]) && $_FILES[$file]['name'][$va]!=""){
					$file_name=$_FILES[$file]['name'][$va];
					$file_ext=strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
					$file_tmp=$_FILES[$file]['tmp_name'][$va];
					$file_type=$_FILES[$file]['type'][$va];
					$file_size=$_FILES[$file]['size'][$va];
					
					if($file_size<=0){
						$error_msg="File size exceeded";
						return $error_msg;
					}
					if(isset($validate_arr["allow_file_type"]) && $validate_arr["allow_file_type"]!=""){
						if(isset($file_ext)){
							$allow_type_arr=explode(",",$validate_arr["allow_file_type"]);
							if(!in_array($file_ext,$allow_type_arr)){
								$error_msg="Invalid File Type ".$file_name;
								return $error_msg;
							}
						}else{
							$error_msg="Invalid File Extension";
							return $error_msg;
						}
					}
					if(isset($validate_arr["max_file_size"]) && $validate_arr["max_file_size"]!=""){
						if($file_size>$validate_arr["max_file_size"]){
							$error_msg="Maximum File size exceeded";
							return $error_msg;
						}
					}
				}
				else if(isset($validate_arr["required"]) && $validate_arr["required"]===true){
					$error_msg="File is not uploaded";
					return $error_msg;
				}
				
			}
		}
		return $error_msg;
	}
	
    /**
     *
     * @validate an ipv4 IP address
     *
     * @access private
     *
     * @param string $var The variable name
     *
     * @param bool $required
     *
     */
    private function validateIpv4($var, $required=false)
    {
        if($required==false && strlen($this->source[$var]) == 0)
        {
            return true;
        }
        if(filter_var($this->source[$var], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === FALSE)
        {
            $this->errors[$var] = $var . ' is not a valid IPv4';
        }
    }

    /**
     *
     * @validate an ipv6 IP address
     *
     * @access private
     *
     * @param string $var The variable name
     *
     * @param bool $required
     *
     */
    public function validateIpv6($var, $required=false)
    {
        if($required==false && strlen($this->source[$var]) == 0)
        {
            return true;
        }

        if(filter_var($this->source[$var], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === FALSE)
        {
            $this->errors[$var] = $var . ' is not a valid IPv6';
        }
    }

    /**
     *
     * @validate a floating point number
     *
     * @access private
     *
     * @param $var The variable name
     *
     * @param bool $required
     */
    private function validateFloat($var, $required=false)
    {
        if($required==false && strlen($this->source[$var]) == 0)
        {
            return true;
        }
        if(filter_var($this->source[$var], FILTER_VALIDATE_FLOAT) === false)
        {
            $this->errors[$var] = $var . ' is an invalid float';
        }
    }

    /**
     *
     * @validate a string
     *
     * @access private
     *
     * @param string $var The variable name
     *
     * @param int $min the minimum string length
     *
     * @param int $max The maximum string length
     *
     * @param bool $required
     *
     */
    private function validateString($var, $min=0, $max=0, $required=false)
    {
        if($required==false && strlen($this->source[$var]) == 0)
        {
            return true;
        }

        if(isset($this->source[$var]))
        {
            if(strlen($this->source[$var]) < $min)
            {
                $this->errors[$var] = $var . ' is too short';
            }
            elseif(strlen($this->source[$var]) > $max)
            {
                $this->errors[$var] = $var . ' is too long';
            }
            elseif(!is_string($this->source[$var]))
            {
                $this->errors[$var] = $var . ' is invalid';
            }
        }
    }

    /**
     *
     * @validate an number
     *
     * @access private
     *
     * @param string $var the variable name
     *
     * @param int $min The minimum number range
     *
     * @param int $max The maximum number range
     *
     * @param bool $required
     *
     */
    private function validateNumeric($var, $min=0, $max=0, $required=false)
    {
        if($required==false && strlen($this->source[$var]) == 0)
        {
            return true;
        }
		if($min==0 && $max==0)
			$result=filter_var($this->source[$var], FILTER_VALIDATE_INT);
		else
			$result=filter_var($this->source[$var], FILTER_VALIDATE_INT,array("options" => array("min_range"=>$min, "max_range"=>$max)));
        if($result===FALSE)
        {
            $this->errors[$var] = $var . ' is an invalid number';
        }
    }

    /**
     *
     * @validate a url
     *
     * @access private
     *
      * @param string $var The variable name
     *
     * @param bool $required
     *
     */
    private function validateUrl($var, $required=false)
    {
        if($required==false && strlen($this->source[$var]) == 0)
        {
            return true;
        }
        if(filter_var($this->source[$var], FILTER_VALIDATE_URL) === FALSE)
        {
            $this->errors[$var] = $var . ' is not a valid URL';
        }
    }


    /**
     *
     * @validate an email address
     *
     * @access private
     *
     * @param string $var The variable name 
     *
     * @param bool $required
     *
     */
    private function validateEmail($var, $required=false)
    {
        if($required==false && strlen($this->source[$var]) == 0)
        {
            return true;
        }
        if(filter_var($this->source[$var], FILTER_VALIDATE_EMAIL) === FALSE)
        {
            $this->errors[$var] = $var . ' is not a valid email address';
        }
    }


    /**
     * @validate a boolean 
     *
     * @access private
     *
     * @param string $var the variable name
     *
     * @param bool $required
     *
     */
    private function validateBool($var, $required=false)
    {
        if($required==false && strlen($this->source[$var]) == 0)
        {
            return true;
        }
        filter_var($this->source[$var], FILTER_VALIDATE_BOOLEAN);
        {
            $this->errors[$var] = $var . ' is Invalid';
        }
    }

    ########## SANITIZING METHODS ############
    

    /**
     *
     * @santize and email
     *
     * @access private
     *
     * @param string $var The variable name
     *
     * @return string
     *
     */
    public function sanitizeEmail($var)
    {
        $email = preg_replace( '((?:\n|\r|\t|%0A|%0D|%08|%09)+)i' , '', $this->source[$var] );
        $this->sanitized[$var] = (string) filter_var($email, FILTER_SANITIZE_EMAIL);
    }


    /**
     *
     * @sanitize a url
     *
     * @access private
     *
     * @param string $var The variable name
     *
     */
    private function sanitizeUrl($var)
    {
        $this->sanitized[$var] = (string) filter_var($this->source[$var],  FILTER_SANITIZE_URL);
    }

    /**
     *
     * @sanitize a numeric value
     *
     * @access private
     *
     * @param string $var The variable name
     *
     */
    private function sanitizeNumeric($var)
    {
        $this->sanitized[$var] = (int) filter_var($this->source[$var], FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     *
     * @sanitize a string
     *
     * @access private
     *
     * @param string $var The variable name
     *
     */
    private function sanitizeString($var)
    {
        $this->sanitized[$var] = (string) filter_var($this->source[$var], FILTER_SANITIZE_STRING);
    }
	/**
     *
     * @getErrorMsg
     *
     * @access public
     *
     * @param string $var The variable name
     *
     */
    public function getErrorMsg()
    {
        $firstErr=key($this->errors);
		return $this->validation_rules[$firstErr]["error-msg"];
    }

} /*** end of class ***/

?>